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

// call the sell_save() function if sell_save_btn is clicked
if (isset($_POST['savebook_btn'])) {
	save_book();
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
    
    $username  = $_SESSION['user']['username'];
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
        
        $query = "SELECT id FROM users WHERE username='$username'";
        $retval = mysqli_query($db, $query); 
        $user_id = mysqli_fetch_assoc($retval)['id'];
        
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

// SAVE BOOK SELL
function save_book(){
    global $db, $errors;
    
    $username  = $_SESSION['user']['username'];
    $title = e($_POST['title']);
    $author = e($_POST['author']);
    $edition = e($_POST['edition']);
    $pubyear = e($_POST['pubyear']);
    $publisher = e($_POST['publisher']);
    $category = e($_POST['category']);
    $condition = e($_POST['condition']);
    $details = e($_POST['details']);
    $price = e($_POST['price']);
    $location = e($_POST['location']);
    
    $query = "SELECT id FROM users WHERE username='$username'";
    $retval = mysqli_query($db, $query); 
    $seller_id = mysqli_fetch_assoc($retval)['id'];
    
    //$datetime = date('Y-m-d H:i:s');
    
    if (count($errors) == 0) {
        $query = "INSERT INTO books (seller_id, title, author, edition, year_published, publisher, category, book_condition, details, price, location, date_created) VALUES($seller_id, '$title', '$author','$edition', $pubyear, '$publisher', '$category', '$condition', '$details', $price, '$location', now())";
        
        if(mysqli_query($db, $query)){
            $_SESSION['success']  = '<div class="alert alert-success"><strong>Book saved!</strong></div>';
        } else {
            array_push($errors, mysqli_error($db));
        }
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


