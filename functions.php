<?php 
session_start();
// connect to database
$db = mysqli_connect('localhost', 'root', 'root', 'subsdbtest');
if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}

// variable declaration
$username = "";
$firstname = "";
$lastname = "";
$email1    = "";
$email2   = "";
$password = "";
$newpwd1 = "";
$newpwd2 = "";
$email_noreply = "sheryl.vizcara@gmail.com";
$book_id = "";
$errors   = array(); 

// call the register() function if register_btn is clicked
if (isset($_POST['register_btn'])) {
	register();
}

// call the login() function if login_btn is clicked
if (isset($_POST['login_btn'])) {
	login();
}

// call the chpwd() function if chpwd_btn is clicked
if (isset($_POST['chpwd_btn'])) {
	chpwd();
}

// call the update_profile() function if saveprofile_btn is clicked
if (isset($_POST['saveprofile_btn'])) {
	update_profile();
}

// call the save_book() function if savebook_btn is clicked
if (isset($_POST['savebook_btn'])) {
	save_book();
}

// call the save_book() function with argument '1' if publish_btn is clicked
if (isset($_POST['publish_btn'])) {
	//publish_book();
    save_book(1);
}

// call the edit_book() function if editsave_btn is clicked
if (isset($_POST['editsave_btn'])) {
    edit_book();
}

// call the edit_book() function with argument '1'  if editpublish_btn is clicked
if (isset($_POST['editpublish_btn'])) {
    edit_book(1);
}


// REGISTER USER
function register(){
	// call these variables with the global keyword to make them available in function
	global $db, $errors, $username, $firstname, $lastname, $email1, $email2, $password, $email_noreply;

	// receive all input values from the form. Call the e() function
    // defined below to escape form values
	$username    =  e($_POST['username']);
    $firstname   =  e($_POST['firstname']);
    $lastname    =  e($_POST['lastname']);
	$email1      =  e($_POST['email1']);
    $email2      =  e($_POST['email2']);

     //prepare a select statement
    $sql = "SELECT id FROM users WHERE username = ?";
    
    if($stmt = mysqli_prepare($db, $sql)){
        // bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "s", $param_username);

        // set parameters
        $param_username = $username;

        // attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            /* store result */
            mysqli_stmt_store_result($stmt);

            if(mysqli_stmt_num_rows($stmt) == 1){
                array_push($errors, "This username is already taken.");
            } else{
                //Close statement
                mysqli_stmt_close($stmt);
                
                //prepare a select statement
                $sql = "SELECT id FROM users WHERE email = ?";
        
                if($stmt = mysqli_prepare($db, $sql)){
                    // bind variables to the prepared statement as parameters
                    mysqli_stmt_bind_param($stmt, "s", $param_email);

                    // set parameters
                    $param_email = e($_POST['email1']);

                    // attempt to execute the prepared statement
                    if(mysqli_stmt_execute($stmt)){
                    /* store result */
                        mysqli_stmt_store_result($stmt);

                        if(mysqli_stmt_num_rows($stmt) == 1){
                            array_push($errors, "Email address is already registered.");
                        } else{
                            $email1 = e($_POST['email1']);
                            
                             if ($email1 != $email2) { 
                                 array_push($errors, "Email addresses do not match!"); 
                             }

                        }
                    } else{
                        echo "Oops! Something went wrong. Please try again later.";
                    }
                } 
            }
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }
        mysqli_stmt_close($stmt);
    }
    
	// register user if there are no errors in the form
	if (count($errors) == 0) {
        $password = random_pwd(12);
		$password_hash = md5($password);//encrypt the password before saving in the database

		if (isset($_POST['user_type'])) {
			$user_type = e($_POST['user_type']);
			$query = "INSERT INTO users (username, user_type, password, email, first_name, last_name) VALUES('$username', '$user_type', '$password_hash','$email1', '$firstname', '$lastname')";
			mysqli_query($db, $query);
            
			$_SESSION['success']  = "New user successfully created!!";
			header('location: index.php');
		}else{
			$query = "INSERT INTO users (username, user_type, password, email, first_name, last_name) VALUES('$username', 'user', '$password_hash','$email1', '$firstname', '$lastname')";
			mysqli_query($db, $query);
            
            $query = "SELECT id FROM users WHERE username='$username'";
            $results = mysqli_query($db, $query);
            $row = mysqli_fetch_assoc($results);
            $user_id = $row['id'];
                
            $query = "INSERT INTO userinfo (user_id, location, mobile, shortbio, birthdate, gender, profile_photo) VALUES('$user_id', '', '','', '', '', '')";
			mysqli_query($db, $query);
            
            $to = $email1;
            $subject = 'You have registered to SUBs website';
            $message = 'Welcome' . $firstname . ''
                . 'You have registered with username:'
                . 'Username: ' . $username . ''
                . 'You may login using this password: '. $password . '';
            $headers = 'From: SUBs team <'.$email_noreply .'>' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n"; 

            mail($to, $subject, $message, $headers);
            
			$_SESSION['success']  = '<div class="alert alert-success alert-dismissible"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> <strong>Successfully registered! </strong> An initial password has been sent to your email address.  '.$password.'</div>';
			exit(header('location: register.php'));		
		}
	}
    mysqli_close($db);
}

// USER LOGIN
function login(){
    global $db, $errors, $username, $password;

	// receive all input values from the form. Call the e() function
    // defined below to escape form values
	$username    =  e($_POST['username']);
    $password    =  e($_POST['password']);

    
    if (empty($username)) { 
		array_push($errors, "Please enter a username."); 
	}
    if (empty($password)) {
        array_push($errors, "Please enter a password."); 
    }

    // attempt login if no errors on form
    if (count($errors) == 0) {
        $password_hash = md5($password);
        
        $query = "SELECT * FROM users WHERE username='$username' AND password='$password_hash' LIMIT 1";
        
        $results = mysqli_query($db, $query);
        
        if (mysqli_num_rows($results) == 1) { // user found
        // check if user is admin or user
			$logged_in_user = mysqli_fetch_assoc($results);
        
			if ($logged_in_user['user_type'] == 'admin') {
				$_SESSION['user'] = $logged_in_user;
				$_SESSION['success']  = '<div class="alert alert-success"><strong>Login successful!</strong> You are now logged in." </div>';
				header('location: admin/home.php');		
                exit;
			} else{
                $user_id = $logged_in_user['id'];
                $query = "SELECT * FROM userinfo WHERE user_id='$user_id'";
                $results = mysqli_query($db, $query);
            
				$_SESSION['user'] = $logged_in_user;
                $_SESSION['userinfo'] = mysqli_fetch_assoc($results);
				$_SESSION['success']  = '<div class="alert alert-success"><strong>Login successful!</strong> You are now logged in. </div>';
				exit(header('location: sell/index.php'));
			}
		} else {
			array_push($errors, "Wrong username or password.");
        }
    }
    mysqli_close($db);
}

// CHANGE PASSWORD
function chpwd(){
    global $db, $errors, $username, $password, $newpwd1, $newpwd2;
    
    $currpwd   =  e($_POST['currentpwd']);
    $newpwd1    =  e($_POST['newpwd']);
    $newpwd2    =  e($_POST['confirmpwd']);
    $username    =  $_SESSION['user']['username'];
    $password    =  $_SESSION['user']['password'];
    $password_hash = md5($currpwd);
    
    
    if ($password_hash != $password) { 
        array_push($errors, "Please enter current password.");
    } else {
        if ($newpwd1 != $newpwd2) {
            array_push($errors, "New password values do not match.");
        }    
    } 
    $newpwd_hash = md5($newpwd1);
    if (count($errors) == 0) {
        $query = "UPDATE users SET password='$newpwd_hash' WHERE username='$username'";
        mysqli_query($db, $query);
        
        $_SESSION['user']['password'] = $newpwd_hash;
        $_SESSION['success']  = '<div class="alert alert-success"><strong>Password successfully changed!</strong></div>';
        exit(header('location: settings.php'));
    }
    mysqli_close($db);
}

// UPDATE PROFILE
function update_profile(){
    global $username, $firstname, $lastname, $location, $mobile, $shortbio, $bday, $email, $gender, $errors, $db;
    
    //$username  = $_SESSION['user']['username'];
    $user_id   = $_SESSION['user']['id'];
    $firstname = e($_POST['firstname']);
    $lastname  = e($_POST['lastname']);
    $location  = e($_POST['location']);
    $mobile    = e($_POST['mobile']);
    $shortbio  = e($_POST['shortbio']);
    $email     = e($_POST['email']);
    $bday      = e($_POST['bday']);
    $gender    = e($_POST['gender']);
    
    if (empty($firstname)){
        array_push($errors, "First name is required.");
    }
    
    if (empty($lastname)){
        array_push($errors, "Last name is required.");
    }
    
    if (empty($email)){
        array_push($errors, "Email address is required.");
    }
    
    if (count($errors) == 0) {
        
        $query = "UPDATE users SET first_name='$firstname',last_name='$lastname',email='$email' WHERE username='$username'";
        mysqli_query($db, $query);
        
        //$query = "SELECT id FROM users WHERE username='$username'";
        //$retval = mysqli_query($db, $query); 
        //$user_id = mysqli_fetch_assoc($retval)['id'];
        
        $query = "UPDATE userinfo SET location='$location', mobile='$mobile', shortbio='$shortbio', birthdate='$bday', gender='$gender' WHERE user_id=$user_id";
        if(mysqli_query($db, $query)){
            $_SESSION['user']['first_name'] = $firstname;
            $_SESSION['user']['last_name'] = $lastname;
            $_SESSION['user']['email'] = $email;
            $_SESSION['userinfo']['location'] = $location;
            $_SESSION['userinfo']['mobile'] = $mobile;
            $_SESSION['userinfo']['shortbio'] = $shortbio;
            $_SESSION['userinfo']['birthdate'] = $bday;
            $_SESSION['userinfo']['gender'] = $gender;
            $_SESSION['success']  = '<div class="alert alert-success"><strong>Profile updated!</strong></div>';
        } else {
            array_push($errors, "Error updating database");
        }
    }
    
    mysqli_close($db);
}

// SAVE OR PUBLISH BOOK FOR SALE
function save_book($isPublished=0){
    global $db, $errors;
    
    // get form values
    $seller_id  = $_SESSION['user']['id'];
    $title = e($_POST['title']);
    $author = e($_POST['author']);
    $edition = e($_POST['edition']);
    $pubyear = intval(e($_POST['pubyear']));
    $publisher = e($_POST['publisher']);
    $category = intval(e($_POST['category']));
    $condition = e($_POST['condition']);
    $details = e($_POST['details']);
    $price = e($_POST['price']);
    $location = e($_POST['location']);
    $seller_photo = '';
    
    // get file input for book photo
    if ( $_FILES['photo']['tmp_name'] != '' ) { 
        $photo = $_FILES['photo']['name'];
        $seller_photo = $seller_id."_".random_pwd(12).basename($photo);
        $uploads_dir = "../content/uploads/".$seller_photo;
    
        // check if file is really an image
        $check = getimagesize($_FILES["photo"]["tmp_name"]);
        if($check !== false) {
            //  check if file size of photo is within 1MB
            if ($_FILES["photo"]["size"] > 1000000) {
                array_push($errors, "Sorry, your file is too large.");
            }
        } else {
            array_push($errors, "File is not an image.");
        }
    }
    
    // perform SQL query if no errors
    if (count($errors) == 0) {
        
        if ($isPublished) {
            // perform this SQL query if publish button is clicked
            $query = "INSERT INTO books (seller_id, title, author, edition, year_published, publisher, category, book_condition, details, price, photo, location, date_created, isPublished, date_published) VALUES($seller_id, '$title', '$author','$edition', $pubyear, '$publisher', $category, '$condition', '$details', $price, '$seller_photo', $location, now(), $isPublished, now())";
            
        } else {
            // perform this SQL query if save button is clicked
            $query = "INSERT INTO books (seller_id, title, author, edition, year_published, publisher, category, book_condition, details, price, photo, location, date_created) VALUES($seller_id, '$title', '$author','$edition', $pubyear, '$publisher', $category, '$condition', '$details', $price, '$seller_photo', $location, now())";
        }
        
        // if no SQL error, return success message
        if(mysqli_query($db, $query)){
            // if there is an image selected for upload, perform file upload
            if ( $_FILES['photo']['tmp_name'] != '' ) { 
                if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploads_dir)) {
  		            // no file upload error
                } else{
  		            array_push($errors, "Failed to upload");
                }
            }
            if ($isPublished){
                // display this message if publish button is clicked
                $_SESSION['success']  = '<div class="alert alert-success"><strong>Book published!</strong></div>';
            } else {
                // display this message if save button is clicked
                $_SESSION['success']  = '<div class="alert alert-success"><strong>Book saved!</strong></div>';
            }
        } else {
            array_push($errors, mysqli_error($db));
        }
    }
    //don't close SQL db connection yet as the select in sell book form will need it
    //mysqli_close($db);
}

// LIST BOOKS FOR AD MANAGEMENT
function list_books() {
    global $db, $errors;
    
    $seller_id  = $_SESSION['user']['id'];
    
    $query = "SELECT * FROM books WHERE seller_id='$seller_id'";
    $retval = mysqli_query($db, $query); 
    
    if (mysqli_num_rows($retval) > 0) {
        echo "<table class='table'>";
        echo "<thead class='thead-dark'><tr><th>Book ID</th><th>Book Photo</th><th>Date Created</th><th>Title</th><th>Author</th><th>Category</th><th>Status</th><th>Actions</th></tr></thead><tbody>";
        while($row = mysqli_fetch_assoc($retval)) {
            echo "<tr><td>".$row['book_id']."</td><td>";
            echo "<div class='thumbnail thumbnail-sm'><img src='../content/uploads/".$row['photo']."' alt=".$row['title']."/></div></td><td>";
            echo $row['date_created']."</td><td>".$row['title']."</td><td>".$row['author']."</td><td>".$row['category']."</td><td>";
            if ($row['isPublished']){
                echo "<span class='badge badge-success'>Published</span>";
            } else {
                echo "<span class='badge badge-light'>Draft</span>";
            }
            echo "</td><td><a class='btn btn-outline-secondary btn-sm' href='edit.php?id=".$row['book_id']."'>Edit</a>";
            if ($row['isPublished']){
                echo "<a class='btn btn-outline-warning btn-sm' href='manage.php?unpub=".$row['book_id']."'>Unpublish</button>";
            } else {
                echo "<a class='btn btn-primary btn-sm' href='manage.php?pub=".$row['book_id']."'>Publish</a>";
            }
            echo "</td></tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<a href='sell.php'> Sell your book. </a>";
    }
    mysqli_close($db);
}

// EDIT OR PUBLISH SAVED BOOK
function edit_book($isPublished=0){
    global $db, $errors;
    
    // get form values
    $book_id  = $_SESSION['book']['book_id'];
    $title = e($_POST['title']);
    $author = e($_POST['author']);
    $edition = e($_POST['edition']);
    $pubyear = intval(e($_POST['pubyear']));
    $publisher = e($_POST['publisher']);
    $category = intval(e($_POST['category']));
    $condition = e($_POST['condition']);
    $details = e($_POST['details']);
    $price = e($_POST['price']);
    $location = e($_POST['location']);
    $seller_photo = '';
        
    // get file input for book photo
    if ( $_FILES['photo']['tmp_name'] != '' ) { 
        $photo = $_FILES['photo']['name'];
        $seller_photo = $seller_id."_".random_pwd(12).basename($photo);
        $uploads_dir = "../content/uploads/".$seller_photo;
    
        // check if file is really an image
        $check = getimagesize($_FILES["photo"]["tmp_name"]);
        if($check !== false) {
            //  check if file size of photo is within 1MB
            if ($_FILES["photo"]["size"] > 1000000) {
                array_push($errors, "Sorry, your file is too large.");
            }
        } else {
            array_push($errors, "File is not an image.");
        }
    }
    
        
    if (count($errors) == 0) {
        if ( $isPublished ){
            // perform this query if to UPDATE and publish
            $query = "UPDATE books SET title='$title',author='$author', edition='$edition', year_published=$pubyear, publisher='$publisher', category=$category, book_condition='$condition', details='$details', price=$price, location=$location, date_created=now(), isPublished='$isPublished', date_published=now() WHERE book_id=$book_id";
        } else {
            // perform this SQL query if to UPDATE only
            $query = "UPDATE books SET title='$title',author='$author', edition='$edition', year_published=$pubyear, publisher='$publisher', category=$category, book_condition='$condition', details='$details', price=$price, location=$location, date_created=now() WHERE book_id=$book_id";
        }
        
        if( mysqli_query($db, $query) ){
            if ( $_FILES['photo']['tmp_name'] != '' ) {
                if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploads_dir)) {
                    //no file upload error
                }else{
  		            array_push($errors, "Failed to upload");
                }
            }
            if ($isPublished) {
                // display this message if to publish 
                $_SESSION['success']  = '<div class="alert alert-success"><strong>Book successfully published!</strong></div>';
                exit(header('location: manage.php'));
            } else {
                // display this message if to UPDATE book only
                $_SESSION['success']  = '<div class="alert alert-success"><strong>Book successfully edited!</strong></div>';
                exit(header('location: manage.php'));
            }
        } else {
            array_push($errors, mysqli_error($db));
        }
    }
    
    //mysqli_close($db);
}

// DISPLAY BOOK CATALOG
function display_catalog() {
    global $db, $errors;
        
    $query = "SELECT * FROM books WHERE seller_id='$seller_id'";
    $retval = mysqli_query($db, $query); 
    
    if (mysqli_num_rows($retval) > 0) {
        echo "<table class='table'>";
        echo "<thead class='thead-dark'><tr><th>Book ID</th><th>Book Photo</th><th>Date Created</th><th>Title</th><th>Author</th><th>Category</th><th>Status</th><th>Actions</th></tr></thead><tbody>";
        while($row = mysqli_fetch_assoc($retval)) {
            echo "<tr><td>".$row['book_id']."</td><td>";
            echo "<div class='thumbnail thumbnail-sm'><img src='../content/uploads/".$row['photo']."' alt=".$row['title']."/></div></td><td>";
            echo $row['date_created']."</td><td>".$row['title']."</td><td>".$row['author']."</td><td>".$row['category']."</td><td>";
            if ($row['isPublished']){
                echo "<span class='badge badge-success'>Published</span>";
            } else {
                echo "<span class='badge badge-light'>Draft</span>";
            }
            echo "</td><td><a class='btn btn-outline-secondary btn-sm' href='edit.php?id=".$row['book_id']."'>Edit</a>";
            if ($row['isPublished']){
                echo "<a class='btn btn-outline-warning btn-sm' href='manage.php?unpub=".$row['book_id']."'>Unpublish</button>";
            } else {
                echo "<a class='btn btn-primary btn-sm' href='manage.php?pub=".$row['book_id']."'>Publish</a>";
            }
            echo "</td></tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<a href='sell.php'> Sell your book. </a>";
    }
    mysqli_close($db);
}

// escape string
function e($val){
	global $db;
	return mysqli_real_escape_string($db, trim($val));
}

// display error
function display_error() {
	global $errors;
    
	if (count($errors) > 0){
		echo '<div class="error alert alert-warning alert-fixed d-flex justify-content-center">';
			foreach ($errors as $error){
				echo $error .'<br>';
			}
		echo '</div>';
	}
}

// generate password
function random_pwd($chars) {
  $pwdchars = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz';
  return substr(str_shuffle($pwdchars), 0, $chars);
}

// list category
function list_category($selected=0) {
    global $db;
    $query = "SELECT * FROM book_category";
    $retval = mysqli_query($db, $query);
    while($row = mysqli_fetch_assoc($retval)) {
        if ($selected && $selected==$row['cat_id']){
            echo "<option selected value=".$row['cat_id'].">".$row['category']."</option>";
        } else {
            echo "<option value=".$row['cat_id'].">".$row['category']."</option>";
        }
    }
}

// list category
function list_regions($selected=0) {
    global $db;
    $query = "SELECT loc_id, region FROM location";
    $retval = mysqli_query($db, $query);
    while($row = mysqli_fetch_assoc($retval)) {
        if ($selected && $selected==$row['loc_id']){
            echo "<option selected value=".$row['loc_id'].">".$row['region']."</option>";
        }else{
            echo "<option value=".$row['loc_id'].">".$row['region']."</option>";
        }
    }
}
