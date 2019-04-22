<?php session_start();
// Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//require '/home/bitnami/vendor/autoload.php';
require '/Applications/MAMP/htdocs/vendor/autoload.php';

// connect to database
$db = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}


// global variable declaration
$username = "";
$firstname = "";
$lastname = "";
$email1    = "";
$email2   = "";
$password = "";
$newpwd1 = "";
$newpwd2 = "";
$book_id = "";
$search = 0;
$page = htmlspecialchars($_SERVER['PHP_SELF']);
$errors   = array(); 

//---------------------- FUNCTIONS START HERE ----------------------

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

// call the reset_password() function if resetpwd_btn is clicked
if (isset($_POST['resetpwd_btn'])) {
    reset_password();
}

// call the submit_feedback() function if submitfeedback_btn is clicked
if (isset($_POST['submitfeedback_btn'])) {
    submit_feedback();
}

// check the book id if an item from the book catalog is selected
if (isset($_GET['view'])) {
  	$book_id = $_GET['view'];
}

// set default value for sortby
if ( isset($_GET['sortoption']) ){
    $sortby = $_GET['sortoption'];
} else {
    $sortby = 0;
}

if ( isset($_GET['search_btn'])) $search = $_GET['search_btn'];

if ( isset($_GET['catid']) ){
    $filterby = $_GET['catid'];
} else {
    $filterby = 0;
}

// call the new_password() function if newpwd_btn is clicked
if ( isset($_POST['newpwd_btn']) ) {
    new_password();
}

if ( isset($_POST['send_btn']) ) {
    send_message($_POST['view']);
}

if ( isset($_POST['msgsend_btn']) ) {
    send_message($_POST['book_id'],$_POST['usertwo_id']);
}

if ( isset($_POST['savecfg_btn']) ) {
    save_config();
}


// REGISTER USER
function register(){
	// call these variables with the global keyword to make them available in function
	global $db, $errors, $username, $firstname, $lastname, $email1, $email2, $password, $admin_email, $admin_pwd, $mail_host, $site_url;

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
		$password_hash = md5($password);//TO-DO make this more secure

		if (isset($_POST['user_type'])) {
			$user_type = e($_POST['user_type']);
			$query = "INSERT INTO users (username, user_type, password, email, first_name, last_name) VALUES('$username', '$user_type', '$password_hash','$email1', '$firstname', '$lastname')";
			mysqli_query($db, $query);
            
			$_SESSION['success']  = "New user successfully created!!";
			header('location: index.php');
		}else{
			$query = "INSERT INTO users (username, user_type, password, email, first_name, last_name) VALUES('$username', 'user', '$password_hash','$email1', '$firstname', '$lastname')";
            if ($retval=mysqli_query($db, $query)){
                $query = "SELECT id FROM users WHERE username='$username'";
                $results = mysqli_query($db, $query);
                $row = mysqli_fetch_assoc($results);
                $user_id = $row['id'];

                $query = "INSERT INTO userinfo (user_id, mobile, shortbio, gender, profile_photo) VALUES('$user_id','','', '', '')";
                if($retval = mysqli_query($db, $query)){
                    
                    $mail = new PHPMailer(true);
                    try {
                        //Server settings
                        $mail->isSMTP();                        // Set mailer to use SMTP
                        $mail->Host       = $mail_host;         // Specify main and backup SMTP servers
                        $mail->SMTPAuth   = true;               // Enable SMTP authentication
                        $mail->Username   = $admin_email;       // SMTP username
                        $mail->Password   = $admin_pwd;         // SMTP password
                        $mail->SMTPSecure = 'tls';              // Enable TLS encryption, `ssl` also accepted
                        $mail->Port       = 587;                // TCP port to connect to

                        //Recipient
                        $mail->setFrom($admin_email, 'SUBs Website');
                        $mail->addAddress($email1);

                        // Content
                        $mail->isHTML(true);                                  // Set email format to HTML
                        $mail->Subject = 'Thanks for creating your account.';
                        $mail->Body    = 'Welcome <b>'.$firstname.'</b>!<br/><br/>You have registered to our website with the username: <b>'.$username.'</b><br/>You may login to your account using the temporary password: <b>'.$password.'</b> <br/> Please remember to change your password and update your profile upon login.';
                        $mail->AltBody = 'Welcome '.$firstname.'! You have registered to our website with the username:'.$username.'. You may login to your account using the temporary password: '.$password.'. Please remember to change your password and update your profile upon login.';

                        $mail->send();
                        $_SESSION['success']  = '<div class="alert alert-success alert-dismissible"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> <strong>Successfully registered! </strong> An initial password has been sent to your email address.</div>';
                    } catch (Exception $e) {
                        array_push($errors,'<div class="alert alert-success alert-dismissible"> Email not sent. Please contact site administrator. </div>');
                    }
                    exit(header('location: register.php'));	
                } else {
                    array_push($errors, mysqli_error($db)); 
                }
            } else {
                array_push($errors, mysqli_error($db)); 
            }
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
        
        $retval = mysqli_query($db, $query);
        
        if (mysqli_num_rows($retval) == 1) { // user found
            $logged_in_user = mysqli_fetch_assoc($retval);
            // check if user deactivated or not
            
            if ( $logged_in_user['isDeactivated'] ){
                array_push($errors, "<strong>Account deactivated!</strong> Please contact site administrator.");
            } else {
                // check if user is admin or user
                if ($logged_in_user['user_type'] == 'admin') {
                    $_SESSION['user'] = $logged_in_user;
                    $_SESSION['success']  = '<div class="alert alert-success"><strong>Login successful!</strong> You are now logged in. </div>';
                    exit(header('location: admin/index.php'));		

                } else{
                    $user_id = $logged_in_user['id'];
                    $query = "SELECT * FROM userinfo WHERE user_id='$user_id'";
                    $results = mysqli_query($db, $query);

                    $_SESSION['user'] = $logged_in_user;
                    $_SESSION['userinfo'] = mysqli_fetch_assoc($results);
                    $_SESSION['success']  = '<div class="alert alert-success"><strong>Login successful!</strong> You are now logged in. </div>';
                    header('location: sell/index.php');
                    exit;
                }
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

// FORGOT PASSWORD
function reset_password() {
    global $db, $errors, $admin_email, $admin_pwd, $mail_host, $site_url;
    
    $email = e($_POST['email']);
    
    $query = "SELECT id FROM users WHERE email='$email'";
    $retval = mysqli_query($db,$query);
    
    
    if ( mysqli_num_rows($retval) == 1 ) {
        $token = bin2hex(openssl_random_pseudo_bytes(50));
        
        $query = "SELECT id FROM password_resets WHERE email='$email'";
        $retval = mysqli_query($db,$query);
        if ( mysqli_num_rows($retval) == 1 ) {
            $query = "UPDATE password_resets SET reset_token = '$token' WHERE email = '$email'";
        } else {
            $query = "INSERT INTO password_resets(email, reset_token) VALUES ('$email', '$token')";
        }
        
        if ($retval = mysqli_query($db, $query)) {
            $mail = new PHPMailer(true);
            try {
                //Server settings
                $mail->isSMTP();                        // Set mailer to use SMTP
                $mail->Host       = $mail_host;         // Specify main and backup SMTP servers
                $mail->SMTPAuth   = true;               // Enable SMTP authentication
                $mail->Username   = $admin_email;       // SMTP username
                $mail->Password   = $admin_pwd;         // SMTP password
                $mail->SMTPSecure = 'tls';              // Enable TLS encryption, `ssl` also accepted
                $mail->Port       = 587;                // TCP port to connect to

                //Recipient
                $mail->setFrom($admin_email, 'SUBs Website');
                $mail->addAddress($email);

                // Content
                $mail->isHTML(true);                                  // Set email format to HTML
                $mail->Subject = 'Reset your password.';
                $mail->Body    = 'Hi there, click on this <a href='.$site_url.'new-password.php?token='.$token.'>link</a> to reset your password on our site.<br/>If the link above did not work, please copy-paste the following to your web browser: '.$site_url.'new-password.php?token='.$token;
                $mail->AltBody = 'Hi there, please visit the following link to reset your password: '.$site_url.'new-password.php?token='.$token;

                $mail->send();
                $_SESSION['success'] = '<div class="alert alert-success"> Password reset link sent to your email.</div>';
            } catch (Exception $e) {
                array_push($errors,'Email not sent. Please contact site administrator.');
            }
        } else {
            array_push($errors, "Sorry, something went wrong. Please contact site administrator.");
        }
    } else {
        array_push($errors, "Sorry, email is not yet registered.");
    }
    
    mysqli_close($db);
}

// NEW PASSWORD
function new_password() {
    global $db, $errors;
    
    if ( $_POST['token'] != '') {
        $token      =  e($_POST['token']);
        $newpwd1    =  e($_POST['newpwd']);
        $newpwd2    =  e($_POST['confirmpwd']);
        
        $query = "SELECT email FROM password_resets WHERE reset_token='$token'";
        $retval = mysqli_query($db, $query);
        
        if ( mysqli_num_rows($retval) == 1 ){
            if ( $newpwd1 == $newpwd2 ){
                $passwordhash = md5($newpwd1);
                $row = mysqli_fetch_assoc($retval);
                $email = $row['email'];
                
                $query = "UPDATE users SET password = '$passwordhash' WHERE email = '$email'";
                if ( mysqli_query($db, $query) ) {
                    $_SESSION['success'] = '<div class="alert alert-success"> <strong> Password changed!</strong> You may now <a href="login.php">login to your account using the new password</a>.</div>';
                    header('location: login.php');
                    exit();
                } else {
                    array_push($errors, "Error updating password. Please inform site administrator. ERROR DETAILS: ".mysqli_error($db));        
                }
            } else {
                array_push($errors, "Passwords don't match.");    
            }
        } else {
            array_push($errors, "Token is missing or invalid.");
        }
    } else {
        array_push($errors, "Token is missing or invalid.");
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
    if (isset($_POST['displaymobile'])){
        $display = 1;
    } else {
        $display = 0;
    }
    
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
        
        $query = "UPDATE users SET first_name='$firstname',last_name='$lastname',email='$email' WHERE id='$user_id'";
        mysqli_query($db, $query);
    
        
        $query = "UPDATE userinfo SET location='$location', mobile='$mobile', shortbio='$shortbio', birthdate='$bday', gender='$gender', display_mobile=$display WHERE user_id=$user_id";
        if(mysqli_query($db, $query)){
            $_SESSION['user']['first_name'] = $firstname;
            $_SESSION['user']['last_name'] = $lastname;
            $_SESSION['user']['email'] = $email;
            $_SESSION['userinfo']['location'] = $location;
            $_SESSION['userinfo']['mobile'] = $mobile;
            $_SESSION['userinfo']['shortbio'] = $shortbio;
            $_SESSION['userinfo']['birthdate'] = $bday;
            $_SESSION['userinfo']['gender'] = $gender;
            $_SESSION['userinfo']['display_mobile'] = $display;
            $_SESSION['success']  = '<div class="alert alert-success"><strong>Profile updated!</strong></div>';
        } else {
            array_push($errors, "Error updating database");
        }
    }
    
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
            if ($_FILES["photo"]["size"] > 5000000) {
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
        echo "<table class='table table-sm table-hover custom-responsive'>";
        echo "<thead class='table-header'><tr><th>Book ID</th><th>Book Photo</th><th>Date Created</th><th>Title</th><th>Author</th><th>Category</th><th>Status</th><th>Actions</th></tr></thead><tbody>";
        while($row = mysqli_fetch_assoc($retval)) {
            echo "<tr><td>".$row['book_id']."</td><td>";
            echo "<div class='thumbnail thumbnail-sm'><img src='../content/uploads/".(empty($row['photo']) ? 'book-icon.png' : $row['photo'])."' alt=".$row['title']."/></div></td><td>";
            echo $row['date_created']."</td><td>".$row['title']."</td><td>".$row['author']."</td><td>".get_category($row['category'])."</td><td>";
            if ($row['isPublished']){
                echo "<span class='badge badge-success'>Published</span>";
            } else {
                echo "<span class='badge badge-light'>Draft</span>";
            }
            if ($row['bookDeactivated']){
                echo "<span class='badge badge-danger'>Deactivated</span>";
            } else {
                echo "<span class='badge badge-success'>Active</span>";
            }
            echo "</td><td><button class='btn btn-outline-secondary btn-sm' ";
            if ( $row['bookDeactivated'] ) echo 'disabled'; 
            echo "><a href='edit.php?id=".$row['book_id']."' ";
            if ( $row['bookDeactivated'] ) echo "onclick='return false;'"; 
            echo ">Edit</a></button>";
            if ($row['isPublished']){
                echo "<button class='btn btn-outline-warning btn-sm' name='unpub' value=".$row['book_id']." ";
                if ( $row['bookDeactivated'] ) echo 'disabled'; 
                echo ">Unpublish</button>";
            } else {
                echo "<button class='btn btn-primary btn-sm' name='pub' value=".$row['book_id']." ";
                if ( $row['bookDeactivated'] ) echo 'disabled'; 
                echo ">Publish</button>";
            }
            echo "</td></tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<div class='wide-panel'><a class='btn btn-primary btn-block' href='sell.php'>  Start selling </a></div>";
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

// DISPLAY CATALOG FUNCTION WITH FUNCTIONALITY FOR SEARCH, FILTER, AND SORT
function display_catalog($filterby=0, $search=0, $sortby=0, $q='') {
    global $db, $errors, $q;
    
    if ( $sortby ){ 
        if ( $sortby == 1 ) $sortphrase = "price ASC"; 
        if ( $sortby == 2 ) $sortphrase = "title ASC"; 
        if ( $sortby == 3 ) $sortphrase = "title DESC"; 
        if ( $sortby == 4 ) $sortphrase = "author ASC"; 
        if ( $sortby == 5 ) $sortphrase = "author DESC"; 
    } else {
        $sortphrase = "date_published ASC";
    }
    
    if ( $filterby ){
        $filterphrase = "AND category = $filterby";
    } else {
        $filterphrase = "";
    }
    
    if ( $search ) {
        $q = e($_GET['q']);    
        
        if ( $search == 1 ) { 
            $query = "SELECT * FROM books WHERE isPublished=1 AND bookDeactivated=0 $filterphrase AND (author  LIKE'%$q%' OR title LIKE '%$q%') ORDER BY $sortphrase"; 
        }
        if ( $search == 2 ) {
            $query = "SELECT * FROM books WHERE isPublished=1 AND bookDeactivated=0 $filterphrase AND title LIKE '%$q%' ORDER BY date_published DESC"; 
        }
        if ( $search == 3 ) {
            $query = "SELECT * FROM books WHERE isPublished=1 AND bookDeactivated=0 $filterphrase AND author  LIKE'%$q%' ORDER BY date_published DESC"; 
        }
    } else {
        $query = "SELECT * FROM books WHERE isPublished=1 AND bookDeactivated=0 $filterphrase ORDER BY $sortphrase";
    }
    
    $retval = mysqli_query($db, $query);
    
    if (mysqli_num_rows($retval) > 0) {
        echo "<div class='catalog-container'>";
        while($row = mysqli_fetch_assoc($retval)) {
            echo "<div class='catalog-item float-left'><a href='view.php?view=".$row['book_id']."'>";
            echo "<div class='thumbnail'><img src='./content/uploads/".(empty($row['photo']) ? 'book-icon.png' : $row['photo'])."' alt='".$row['title']."'/></div>";
            echo "<div class='caption'><span class='book-title'>".$row['title']."</span>";
            echo "<span class='book-author'>".$row['author']."</span></div>";
            echo "<div class='book-price'><hr/><span>Php ".$row['price']."</span></div>";
            echo "</a></div>";
        }
        echo "</div>";
    } else {
        echo "No search results. ";
    }
    mysqli_close($db);
}

// VIEW BOOK 
function display_book($book_id) {
    global $db, $errors;
    
    $query = "SELECT * FROM books WHERE book_id=".$book_id;
    $retval = mysqli_query($db, $query); 
    $book = mysqli_fetch_assoc($retval);
    
    $query = "SELECT * FROM users JOIN userinfo ON id = user_id WHERE id=".$book['seller_id'];
    $retval = mysqli_query($db, $query); 
    $seller = mysqli_fetch_assoc($retval);
    
    $query = "SELECT * FROM book_category WHERE cat_id=".$book['category'];
    $retval = mysqli_query($db, $query); 
    $cat = mysqli_fetch_assoc($retval);
    
    //display photo large size
    echo "<div class='view-book-container col-lg-8'><img src='./content/uploads/".(empty($book['photo']) ? 'book-icon.png' : $book['photo'])."'/></div>";
    echo "<div class='view-book-primary-details col-lg-4'>";
    echo "<span class='view-book-title caption'>".$book['title']."</span>";
    echo "<span class='view-book-author caption'> ".$book['author']."</span><hr/>";
    echo "<span class='view-book-price caption'>Php ".$book['price']."</span>";
    echo "<span class='view-book-seller caption'>Sold by ".$seller['first_name']." ".$seller['last_name']."</span>";
    echo "<span class='view-seller-location caption'>".get_location($seller['location'])."</span>";
    echo "<h2>Contact Seller</h2>";
    echo "<div class='d-flex flex-column border' id='contact-seller-container'>";
    echo "<button class='btn btn-primary btn-sm' data-toggle='modal' data-target='#sendMessage' id='sendmsgModal'>Send online message</button>"; 
    echo "<span align='center'>OR</span>";
    echo "<button class='btn btn-light btn-sm' id='displayMobile' name=".$seller['display_mobile']." value='".$seller['mobile']."'>09XX-XXX-XXXX</button>";
    echo "</div></div>";
    
    echo '
        <div class="modal fade" id="sendMessage" tabindex="-2" role="dialog" aria-labelledby="sendMessage" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="dialog">';
    if ( !isset($_SESSION['user']) ){
        echo '<div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="sendMessageLabel">Login to send seller a message</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                You must be logged in to send an online message!
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <a class="btn btn-primary" href="login.php">Login</a>
              </div>
            </div>
          </div>
        </div>';
    } else {
        echo '
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="sendMessageLabel">Send seller a message</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <form method="post" action="view.php">
                  <div class="modal-body">
                    <input type="number" name="view" value='.$book_id.' hidden>
                    <textarea class="form-control" name="message" placeholder="Write your message to the seller here">I am interested in buying this book.</textarea>
                    
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" name="send_btn">Send message</button>
                  </div>
                  </form>
                </div>
              </div>
            </div>
        ';
    }
    
    echo "<div class='view-book-secondary-details col-lg-8'>";
    echo "<h2>Book Details</h2>";
    echo "<table class='table table-sm'>";
    if (!empty($book['edition'])){
        echo "<tr><td>Book edition</td><td>".$book['edition']."</td></tr>";
    } else {
        echo "<tr><td>Book edition</td><td>No information</td></tr>";
    }
    if (!empty($book['year_published'])){
        echo "<tr><td>Year published</td><td>".$book['year_published']."</td></tr>";
    } else {
        echo "<tr><td>Year published</td><td> No information </td></tr>";
    }
    if (!empty($book['publisher'])){
        echo "<tr><td>Book publisher</td><td>".$book['publisher']."</td></tr>";
    } else {
        echo "<tr><td>Book publisher</td><td>No information</td></tr>";
    }
    if (!empty($book['details'])){
        echo "<tr><td>Other details</td><td>".$book['details']."</td></tr>";
    } else {
        echo "<tr><td>Other details</td><td></td></tr>";
    }
    echo "</table>";
    
    
    echo "<table class='table table-sm'>";
    echo "<h2>Item Details</h2>";
    echo "<tr><td>Category</td><td>".$cat['category']."</td></tr>";
    echo "<tr><td>Condition</td><td>".$book['book_condition']."</td></tr>";
    echo "<tr><td>Location</td><td>".get_location($book['location'])."</td></tr>";
    echo "<tr><td>Date posted</td><td>".date('j M Y', strtotime($book['date_published']))."</td></tr>";
    echo "<tr><td>Book ID</td><td>".$book['book_id']."</td></tr>";
    echo "</table>";
    echo "</div>";
    
    echo"</div>";
}

// SEND SELLER A MESSAGE
function send_message($book_id,$usertwo_id=0){
    global $db, $errors;
    
    $sent_by = $_SESSION['user']['id'];
    $message = e($_POST['message']);
    
    $query = "SELECT * FROM books WHERE book_id=".$book_id;
    $retval = mysqli_query($db, $query); 
    $book = mysqli_fetch_assoc($retval);
    
    $page = htmlspecialchars($_SERVER['PHP_SELF']);
    
    if ($sent_by == $book['seller_id']){
        $sent_to = $usertwo_id;
    } else {
        $sent_to = $book['seller_id'];
    }
    
    if ( count($errors) == 0) {
        $query = "INSERT INTO mbox ( book_id, sent_by, sent_to, message) VALUES ($book_id, $sent_by, $sent_to, '$message')";
        if ( mysqli_query($db, $query) ) {
            if (!$usertwo_id){
                $_SESSION['success']  = '<div class="alert alert-success alert-dismissible"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> Message sent! </div>';
            }
            exit(header('location:'.$page.'?'.($page == '/subs/view.php' ? 'view='.$book_id : ($page == '/subs/sell/messages.php' ? 'book_id='.$book_id.'&usertwo_id='.$usertwo_id : ''))));		
        } else {
            array_push($errors, "Something went wrong. :( <br/>".mysqli_error($db));
        }
    }
}

// LIST MESSAGES PER BOOK ITEM PER USER
function list_messages(){
    global $db, $errors, $usertwo_id;
    
    $user_id = $_SESSION['user']['id'];
    $page = htmlspecialchars($_SERVER['PHP_SELF']);
    if (isset($_GET['book_id'])){
        $q1 = $_GET['book_id'];
        $q2 = $_GET['usertwo_id'];
    } else {
        $q1 = 0;
        $q2 = 0;
    }
    
    $query = "SELECT DISTINCT book_id FROM mbox WHERE sent_to=$user_id OR sent_by=$user_id";
    $retval = mysqli_query($db, $query);

    if ( mysqli_num_rows($retval) > 0) {
        echo '<div class="messages-list col col-lg-4 float-left">';
        echo '<div class="message-header">Your messages. <small>Click an item to see messages.</small></div>';
        echo '<div class="list-group">';
        
        while ($row = mysqli_fetch_assoc($retval)){
            $query = "SELECT * FROM books WHERE book_id=".$row['book_id'];
            $retval_book = mysqli_query($db, $query);
            $book = mysqli_fetch_assoc($retval_book);
            
            
            if ( $user_id == $book['seller_id'] ) {
                $query = "SELECT sent_by, ANY_VALUE(date_created) as date, ANY_VALUE(message) as msg FROM (SELECT sent_by, date_created, message FROM mbox WHERE sent_to=$user_id AND book_id=".$row['book_id']." ORDER BY date_created DESC) AS m GROUP BY sent_by";
                if ( $retval_buyers = mysqli_query($db, $query) ) {
                    while ($msgs = mysqli_fetch_assoc($retval_buyers)){
                        $buyer_id = $msgs['sent_by'];
                        $query = "SELECT * FROM users WHERE id=$buyer_id";
                        $retval_buyer = mysqli_query($db, $query);
                        $buyer = mysqli_fetch_assoc($retval_buyer);

                        echo '<a href="'.$page.'?book_id='.$row['book_id'].'&usertwo_id='.$buyer_id.'" class="message-item list-group-item '.($q1==$row['book_id'] && $q2==$buyer_id ? 'list-group-item-secondary' : '').' list-group-item-action"><span class="tag badge badge-danger">selling</span><span class="timestamp">'.$msgs['date'].'</span><span class="sender-name">'.$buyer['username'].'</span><span class="book-title">'.$book['title'].'</span></a>';
                    } 
                } else {
                    array_push($errors, 'Error fetching data from database. ERROR DETAILS'.mysqli_error($db));
                }
            } 
            else {
                $query = "SELECT sent_to, ANY_VALUE(date_created) as date, ANY_VALUE(message) as msg FROM (SELECT sent_to, date_created, message FROM mbox WHERE sent_by=$user_id AND book_id=".$row['book_id']." ORDER BY date_created DESC) AS m GROUP BY sent_to";
                
                if( $retval_seller = mysqli_query($db, $query) ){
                    while ( $msgs = mysqli_fetch_assoc($retval_seller) ){
                        $seller_id = $msgs['sent_to'];
                        $query = "SELECT * FROM users WHERE id=$seller_id";
                        $retval_seller = mysqli_query($db, $query);
                        $seller = mysqli_fetch_assoc($retval_seller);

                        echo '<a href="'.$page.'?book_id='.$row['book_id'].'&usertwo_id='.$seller_id.'" class="message-item list-group-item '.($q1==$row['book_id'] && $q2==$seller_id ? 'list-group-item-secondary' : '').' list-group-item-action"><span class="tag badge badge-success">buying</span><span class="timestamp">'.$msgs['date'].'</span><span class="sender-name">'.$seller['username'].'</span><span class="book-title">'.$book['title'].'</span></a>';
                    }
                } else {
                    array_push($errors, 'Error fetching data from database. ERROR DETAILS'.mysqli_error($db));
                }
            }
        }
        echo '</div></div>';
    } else {
        echo "<div class='filler'>No messages to show.";
        echo "<br/><a href='sell.php'>Start selling</a> OR <a href='../index.php'>Find books to buy.</a></div>";
    }
}

// SHOW MESSAGES
function show_messages($book_id=0, $usertwo_id=0){
    global $db, $errors;
    
    $user_id = $_SESSION['user']['id'];
    
    $query = "SELECT * FROM books WHERE book_id=".$book_id;
    $retval_book = mysqli_query($db, $query);
    $book = mysqli_fetch_assoc($retval_book);
    
    $query = "SELECT * FROM users WHERE id=$usertwo_id";
    $retval_usertwo = mysqli_query($db, $query);
    $usertwo = mysqli_fetch_assoc($retval_usertwo);
    
    $query = "SELECT * FROM mbox WHERE book_id=$book_id AND (sent_to=$usertwo_id OR sent_by=$usertwo_id) ORDER BY date_created ASC";
    $retval = mysqli_query($db, $query);
    
    
    if ( mysqli_num_rows($retval) > 0) {
        echo '<div class="message-header">Viewing conversation with <strong>'.$usertwo['username'].'</strong> about <strong>'.$book['title'].'</strong></div><div class="message-body">';
        while ($row = mysqli_fetch_assoc($retval)){
            
            if ( $row['sent_to'] == $user_id){
                echo '<div class="sender-bubble">';
                echo '<p>'.$row['message'].'</p>';
                echo '<span class="timestamp">'.$row['date_created'].'</span>';
                echo '</div>';
            } 
            
            if ( $row['sent_by'] == $user_id) {
                echo '<div class="receiver-bubble">';
                echo '<p>'.$row['message'].'</p>';
                echo '<span class="timestamp">'.$row['date_created'].'</span>';
                echo '</div>';
            }
        }
        echo '</div>';
        echo '<div class="message-response">';
        echo '<form method="post" action="messages.php">';
        echo '
        <input type="number" name="book_id" value='.$book_id.' hidden>
        <input type="number" name="usertwo_id" value='.$usertwo_id.' hidden>
        <textarea class="form-control col col-lg-10 float-left" name="message" placeholder="Write your response here..."></textarea>
        <button type="submit" class="btn btn-dark col col-lg-2 float-right" name="msgsend_btn">Send</button>
        </div>';
        echo '</form>';
        
    } else {
        echo "No messages to show.";
    }
    mysqli_close($db);
}

// LIST USERS FOR USER MANAGEMENT
function list_users() {
    global $db, $errors;
    
    $query = "SELECT id, username, email, date_registered, isDeactivated, COUNT(book_id) AS books FROM (users JOIN books ON id=seller_id) GROUP BY id";
    $retval = mysqli_query($db, $query); 
    
    if (mysqli_num_rows($retval) > 0) {
        echo "<table class='table table-hover table-sm'>";
        echo "<thead class='thead-dark'><tr><th>User ID</th><th>Username</th><th>Email</th><th>Date Created</th><th># of posted books</th><th>Status</th><th>Actions</th></tr></thead><tbody>";
        
        while($row = mysqli_fetch_assoc($retval)) {
            echo "<tr><td>".$row['id']."</td><td>";
            echo $row['username']."</td><td>";
            echo $row['email']."</td><td>".$row['date_registered']."</td><td>";
            echo "<a href='view-posts.php?manage=1&id=".$row['id']."'>".$row['books']."</a></td><td>";
            
            if ( $row['isDeactivated'] ){
                echo "<span class='badge badge-danger'>Deactivated</span>";
            } else {
                echo "<span class='badge badge-success'>Active</span>";
            }
            echo"</td><td>";
            if ($row['isDeactivated']){
                echo "<a class='btn btn-outline-warning btn-sm' href='manage-users.php?activate=".$row['id']."'>Activate</button>";
            } else {
                echo "<a class='btn btn-outline-danger btn-sm' href='manage-users.php?deactivate=".$row['id']."'>Deactivate</a>";
            }
            echo "</td></tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<a href='#'> No results. </a>";
    }
    mysqli_close($db);
}

// MANAGE BOOKS POSTED BY SELECTED USER
function manage_books($seller_id='') {
    global $db, $errors;
    
    $query = "SELECT * FROM users WHERE id='$seller_id'";
    $retval = mysqli_query($db, $query); 
    $row = mysqli_fetch_assoc($retval);
    
    echo "<div class='panel-description' >Managing published books of <strong>".$row['username']."</strong>.</div>";
        
    $query = "SELECT * FROM books WHERE seller_id='$seller_id'";
    $retval = mysqli_query($db, $query); 
    
    if (mysqli_num_rows($retval) > 0) {
        echo "<table class='table table-sm table-hover'>";
        echo "<thead class='table-header'><tr><th>Book ID</th><th>Book Photo</th><th>Date Created</th><th>Title</th><th>Author</th><th>Category</th><th>Status</th><th>Actions</th></tr></thead><tbody>";
        while($row = mysqli_fetch_assoc($retval)) {
            echo "<tr><td>".$row['book_id']."</td><td>";
            echo "<div class='thumbnail thumbnail-sm'><img src='../content/uploads/".(empty($row['photo']) ? 'book-icon.png' : $row['photo'])."' alt=".$row['title']."/></div></td><td>";
            echo $row['date_created']."</td><td>".$row['title']."</td><td>".$row['author']."</td><td>".$row['category']."</td><td>";
            if ($row['isPublished']){
                echo "<span class='badge badge-success'>Published</span>";
            } else {
                echo "<span class='badge badge-light'>Draft</span>";
            }
            if ($row['bookDeactivated']){
                echo "<span class='badge badge-danger'>Deactivated</span>";
            } else {
                echo "<span class='badge badge-success'>Active</span>";
            }
            echo "</td><td>";
            if ($row['bookDeactivated']){
                echo "<button class='btn btn-outline-warning btn-sm' name='activate_book' value=".$row['book_id'].">Activate</button>";
            } else {
                echo "<button class='btn btn-outline-danger btn-sm' name='deactivate_book' value=".$row['book_id'].">Deactivate</button>";
            }
            echo "</td></tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<a href='sell.php'> Sell your book. </a>";
    }
    mysqli_close($db);
}

// SUBMIT FEEDBACK
function submit_feedback() {
    global $db, $errors;
    
    $name       = e($_POST['name']);
    $email      = e($_POST['email']);
    $feedback   = e($_POST['feedback']);
    
    if ( count($errors) == 0 ) {
        $query = "INSERT INTO feedback (name, email, feedback) VALUES ('$name', '$email', '$feedback')";
        if ( mysqli_query($db, $query) ) {
            $_SESSION['success']  = '<div class="alert alert-success alert-dismissible"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> Feedback submitted! </div>';
        } else {
            array_push($errors, "Error updating database. ERROR DETAILS:".mysqli_error($db));
        }
    }
        
}

// LIST FEEDBACK FOR FEEDBACK MANAGEMENT
function list_feedback() {
    global $db, $errors;
    
    $query = "SELECT * FROM feedback";
    $retval = mysqli_query($db, $query); 
    
    if (mysqli_num_rows($retval) > 0) {
        echo "<table class='table table-hover table-sm'>";
        echo "<thead class='thead-dark'><tr><th>Feedback ID</th><th>Name</th><th>Email</th><th>Feedback</th><th>Date submitted</th><th>Actions</th></tr></thead><tbody>";
        
        while($row = mysqli_fetch_assoc($retval)) {
            echo "<tr><td>".$row['feedback_id']."</td><td>";
            echo $row['name']."</td><td>";
            echo $row['email']."</td><td>".$row['feedback']."</td><td>";
            echo $row['date_submitted']."</td><td>";
            echo "</td></tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<a href='#'> No results. </a>";
    }
    mysqli_close($db);
}

// escape string function for processing input texts
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

// list category as options
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

// list regions
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

// get location
function get_location($loc_id) {
    global $db, $errors;
    
    $query = "SELECT * FROM location WHERE loc_id=".$loc_id;
    if ($retval = mysqli_query($db, $query)){
        $loc = mysqli_fetch_assoc($retval);
        return $loc['region'];
    } else {
        array_push($errors, mysqli_error($db));
    }        
    
}

// get category
function get_category($cat_id) {
    global $db, $errors;
    
    $query = "SELECT * FROM book_category WHERE cat_id=".$cat_id;
    if ($retval = mysqli_query($db, $query)){
        $cat = mysqli_fetch_assoc($retval);
        return $cat['category'];
    } else {
        array_push($errors, mysqli_error($db));
    }        
    
}


// list category as filters
function list_category_as_filters() {
    global $db,$errors,$filterby;
    $query = "SELECT * FROM book_category";
    $retval = mysqli_query($db, $query);
    $page = htmlspecialchars($_SERVER['PHP_SELF']);
//    echo "<a class='btn btn-secondary btn-sm filter-button' href='$page?catid=0'>Show all</a>";
    echo "<button type='submit' class='btn ".($filterby==0 ?  'btn-secondary':'btn-light')." btn-sm filter-button' name='catid' value=0>Show all</button>";
    while($row = mysqli_fetch_assoc($retval)) {
//        echo "<a class='btn btn-light btn-sm filter-button' href='$page?catid=".$row['cat_id']."'>".$row['category']."</a>";
        echo "<button type='submit' class='btn btn-sm filter-button ".($filterby==$row['cat_id'] ?  'btn-secondary':'btn-light')."' name='catid' value='".$row['cat_id']."'>".$row['category']."</button>";
    }
//    echo "</div>";
}

// get site information
function get_siteinfo() {
    global $db, $errors;
    
    $query = "SELECT * FROM site";
    if ($retval = mysqli_query($db, $query)){
        while($row = mysqli_fetch_assoc($retval)){
            $_SESSION['siteinfo'][$row['meta_key']] = $row['meta_value'];
        }
    } else {
        array_push($errors, mysqli_error($db));
    }        
    
}

function save_config() {
    global $db, $errors;
    
    $theme      = e($_POST['theme']);
    $site_title = e($_POST['site_title']);
    $site_name  = e($_POST['site_name']);
    $admin_email = e($_POST['admin_email']);
    
     // get file input for site logo
    if ( $_FILES['logo']['tmp_name'] != '' ) { 
        $uploads_dir = "../content/site_logo.png";
    
        // check if file is really an image
        $check = getimagesize($_FILES["logo"]["tmp_name"]);
        if($check !== false) {
            //  check if file size of photo is within 1MB
            if ($_FILES["logo"]["size"] > 1000000) {
                array_push($errors, "Sorry, your file is too large.");
            }
        } else {
            array_push($errors, "File is not an image.");
        }
    }
    
    $query = "UPDATE site SET meta_value='$theme' WHERE meta_key='theme'; ";
    $query .= "UPDATE site SET meta_value='$site_title' WHERE meta_key='site_title'; ";
    $query .= "UPDATE site SET meta_value='$site_name' WHERE meta_key='site_name'; ";
    $query .= "UPDATE site SET meta_value='$admin_email' WHERE meta_key='admin_email'; ";
    if ($retval = mysqli_multi_query($db, $query)){
        if ( $_FILES['logo']['tmp_name'] != '' ) {
                if (move_uploaded_file($_FILES['logo']['tmp_name'], $uploads_dir)) {
                    //no file upload error
                }else{
  		            array_push($errors, "Failed to upload");
                }
            }
        $_SESSION['success']  = '<div class="alert alert-success"><strong>Site configuration saved!</strong></div>';
        exit(header('location:configure.php'));
    } else {
        array_push($errors, mysqli_error($db));
    }        
    
}

