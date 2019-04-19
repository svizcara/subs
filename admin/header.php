<?php 
/**
 *  Displays the header of the SUBs website
 */
include '../functions.php';

if ( !isset($_SESSION['user']) ) {
    $_SESSION['msg'] = '<div class="alert alert-warning">You must log in first</div>';
    exit(header('location: ../login.php'));
} else {
    if ( $_SESSION['user']['user_type'] != 'admin' ) {
        $_SESSION['msg'] = '<div class="alert alert-warning">You must log as administrator to access this page</div>';
        exit(header('location: ../login.php'));
    }
}

if ( isset($_GET['logout']) ) {
  	session_destroy();
  	exit(header("location: ../login.php"));
    unset($_SESSION['user']);
}

if (isset($_GET['activate'])) {
  	$user_id = $_GET['activate'];
    $query = "UPDATE users SET isDeactivated=0 WHERE id='$user_id'";
    if ( mysqli_query($db, $query) ){
        $_SESSION['success']  = '<div class="alert alert-success"><strong>User account activated!</strong></div>';
    } 
}    
if (isset($_GET['deactivate'])) {
  	$user_id = $_GET['deactivate'];
    $query = "UPDATE users SET isDeactivated=1 WHERE id='$user_id'";
    if ( mysqli_query($db, $query) ){
        $_SESSION['success']  = '<div class="alert alert-success"><strong>User account deactivated!</strong></div>';
    } 
}

if (isset($_GET['activate_book'])) {
  	$book_id = $_GET['activate_book'];
    $query = "UPDATE books SET bookDeactivated=0 WHERE book_id='$book_id'";
    if ( mysqli_query($db, $query) ){
        $_SESSION['success']  = '<div class="alert alert-success"><strong>Book posting activated!</strong></div>';
    } 
}    
if (isset($_GET['deactivate_book'])) {
  	$book_id = $_GET['deactivate_book'];
    $query = "UPDATE books SET bookDeactivated=1 WHERE book_id='$book_id'";
    if ( mysqli_query($db, $query) ){
        $_SESSION['success']  = '<div class="alert alert-success"><strong>Book posting deactivated!</strong></div>';
    } 
}
?>

<!doctype html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Sell Used Books website</title>
        <link rel="stylesheet" href="../include/css/bootstrap.min.css">
        <link rel="stylesheet" href="../include/style.css">
    </head>
    <body>
        <header class="navbar navbar-light navbar-expand-lg text-dark bg-light sticky-top">
            <a class="navbar-brand" href="../index.php">Sell Used Books</a>
            
            <div class="collapse navbar-collapse" id="navbarMenu">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="configure.php">Site configuration</a></li>
                    <li class="nav-item"><a class="nav-link" href="manage-users.php">User management </a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Feedback management</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php?logout='1'">Logout</a></li>
                </ul>
            </div>
            
            <div>
                    Welcome, 
                    <?php  if(isset($_SESSION['user'])) : ?>
                        <strong>
                            <?php echo $_SESSION['user']['username']; ?>
                        </strong>!

                        <small>
                            <i  style="color: #888;">(<?php echo ucfirst($_SESSION['user']['user_type']); ?>)</i> 
                            <br>
                        </small>

                    <?php endif ?>
                </div>
            
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarMenu" aria-controls="navbarMenu" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </header>
        
<?php 
    if(isset($_SESSION['success'])) { 
        echo $_SESSION['success'];
        unset($_SESSION['success']);
    }
?>
<?php echo display_error(); ?>