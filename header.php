<?php 
session_unset();
//session_start();
include 'functions.php';

if (isset($_GET['view'])) {
  	$book_id = $_GET['view'];
}

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
            <a class="navbar-brand" href="index.php">Sell Used Books</a>
            
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarMenu" aria-controls="navbarMenu" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <?php if (!isset($_SESSION['user'])) {
                echo'
                <div class="collapse navbar-collapse" id="navbarMenu">
                    <div class="mr-auto">
                        <ul class="navbar-nav">
                            <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                            <li class="nav-item"><a class="nav-link" href="catalog.php">Book Catalog</a></li>
                        </ul>
                    </div>
                    <div class="">
                        <ul class="navbar-nav">
                            <li class="nav-item"><a class="nav-link"
                            href="login.php">Log in </a></li>
                            <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>
                        </ul>
                    </div>
                </div>';
            } else {
                echo'
                <div class="collapse navbar-collapse" id="navbarMenu">
                    <ul class="navbar-nav">
                        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="./sell/sell.php">Sell book</a></li>
                        <li class="nav-item"><a class="nav-link" href="./sell/index.php">Dashboard</a></li>
                    </ul>
                </div>';
            }
            ?>
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