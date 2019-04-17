<?php 
/**
 *  Displays the header of the SUBs website
 */
include '../functions.php';

//session_start(); 
if (!isset($_SESSION['user'])) {
    $_SESSION['msg'] = '<div class="alert alert-warning">You must log in first</div>';
    header('location: ../login.php');
}
if (isset($_GET['logout'])) {
  	session_destroy();
  	unset($_SESSION['user']);
  	header("location: ../login.php");
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
    </head>
    <body>
        <header class="navbar navbar-expand-lg navbar-dark bg-dark text-light sticky-top">
            <a class="navbar-brand" href="../index.php">Sell Used Books</a>
            
            <div class="collapse navbar-collapse" id="navbarMenu">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="#">Messages</a></li>
                    <li class="nav-item"><a class="nav-link" href="sell.php">Sell Books</a></li>
                    <li class="nav-item"><a class="nav-link" href="manage.php">Ad management </a></li>
                    <li class="nav-item"><a class="nav-link" href="settings.php">Settings</a></li>
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
            
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarMenu" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
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