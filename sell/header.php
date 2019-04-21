<?php 
/**
 *  Displays the header of the SUBs website
 */
include '../config.php';
include '../functions.php';

//session_start(); 
if (!isset($_SESSION['user'])) {
    $_SESSION['msg'] = '<div class="alert alert-warning">You must log in first</div>';
    exit(header('location: ../login.php'));
}else {
    if ( $_SESSION['user']['user_type'] != 'user' ) {
        $_SESSION['msg'] = '<div class="alert alert-warning">You must log as site user to access this page</div>';
        exit(header('location: ../login.php'));
    }
}

if (isset($_GET['logout'])) {
  	session_destroy();
  	exit(header("location: ../login.php"));
    unset($_SESSION['user']);
}
if (isset($_GET['pub'])) {
  	$book_id = $_GET['pub'];
    $query = "UPDATE books SET isPublished=1, date_published=now() WHERE book_id='$book_id'";
    if ( mysqli_query($db, $query) ){
        $_SESSION['success']  = '<div class="alert alert-success"><strong>Book successfully published!</strong></div>';
    } 
}    
if (isset($_GET['unpub'])) {
  	$book_id = $_GET['unpub'];
    $query = "UPDATE books SET isPublished=0 WHERE book_id='$book_id'";
    mysqli_query($db, $query);
}
?>

<!doctype html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Sell Used Books website</title>
        <link rel="stylesheet" href="../include/css/bootstrap.min.css">
        <link rel="stylesheet" href="../include/style.css">
        <link rel="stylesheet" href="../themes/default.css">
    </head>
    <body>
        <header class="navbar navbar-expand-lg navbar-user sticky-top">
            <a class="navbar-brand" href="../index.php">Sell Used Books</a>
            
            <div class="collapse navbar-collapse" id="navbarMenu">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="messages.php">Messages</a></li>
                    <li class="nav-item"><a class="nav-link" href="sell.php">Sell Books</a></li>
                    <li class="nav-item"><a class="nav-link" href="manage.php">Ad management </a></li>
                    <li class="nav-item"><a class="nav-link" href="settings.php">Settings</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>?logout='1'">Logout</a></li>
                </ul>
            </div>
            
            <div class="welcome-user">
                    Welcome, 
                    <?php  if(isset($_SESSION['user'])) : ?>
                        <strong>
                            <?php echo $_SESSION['user']['username']; ?>
                        </strong>!

                        <small>
                            <i>(<?php echo ucfirst($_SESSION['user']['user_type']); ?>)</i> 
                            <br>
                        </small>

                    <?php endif ?>
                </div>
            
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarMenu" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </header>
        
<?php 
    if(isset($_SESSION['success'])) { 
        echo $_SESSION['success'];
        unset($_SESSION['success']);
    }
    if(isset($_SESSION['msg'])) { 
        echo $_SESSION['msg'];
        unset($_SESSION['msg']);
    }
?>
<?php echo display_error(); ?>