<?php 
/**
 *  Displays the header of the SUBs website
 */
session_unset();
session_start();
include 'functions.php';
?>

<!doctype html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Sell Used Books website</title>
        <link rel="stylesheet" href="include/css/bootstrap.min.css">
        <link rel="stylesheet" href="include/style.css">
    </head>
    <body>
        <header class="navbar navbar-expand-lg navbar-dark bg-dark text-light sticky-top">
            <a class="navbar-brand" href="#">Sell Used Books</a>
            
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarMenu" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarMenu">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Book Catalog</a></li>
                    <li class="nav-item"><a class="nav-link" href="login.php">Log in </a></li>
                    <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>
                </ul>
            </div>
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