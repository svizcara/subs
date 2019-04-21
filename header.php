<?php session_unset();
include 'config.php';
include 'functions.php';
?>

<!doctype html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Sell Used Books website</title>
        
<!--include stylesheets-->
        <link rel="stylesheet" href="include/css/bootstrap.min.css">
        <link rel="stylesheet" href="include/style.css">
        <link rel="stylesheet" href="themes/default.css">
    </head>
    <body>
<!-- WEBSITE NAVIGATION BAR AND HEADER STARTS HERE-->
        <header class="navbar navbar-expand-lg sticky-top">
            <a class="navbar-brand" href="index.php">Sell Used Books</a>
            
<!--TOGGLE ICON-->
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarMenu" aria-controls="navbarMenu" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

<!--Check if user is logged in-->
            <?php if ( isset($_SESSION['user']) ) {
                //display custom homepage navbar for admin if logged in user is admin
                if ( $_SESSION['user']['user_type'] == 'admin'){
                    echo'
                    <div class="collapse navbar-collapse" id="navbarMenu">
                        <div class="mr-auto">
                            <ul class="navbar-nav">
                                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                                <li class="nav-item"><a class="nav-link" href="./catalog.php">Book Catalog</a></li>
                            </ul>
                        </div>
                        <div class="">
                            <ul class="navbar-nav">
                                <li class="nav-item"><a class="nav-link" href="./admin/index.php">Go to Dashboard</a></li>
                            </ul>
                        </div>
                    </div>';
                //else display custom homepage navbar for user
                } else {
                    echo'
                    <div class="collapse navbar-collapse" id="navbarMenu">
                        <div class="mr-auto">
                            <ul class="navbar-nav">
                                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                                <li class="nav-item"><a class="nav-link" href="./catalog.php">Book Catalog</a></li>
                            </ul>
                        </div>
                        <div class="">
                            <ul class="navbar-nav">
                                <li class="nav-item"><a class="btn btn-primary" href="./sell/sell.php">Sell book</a></li>
                                <li class="nav-item"><a class="nav-link" href="./feedback.php">Give Feedback</a></li>
                                <li class="nav-item"><a class="nav-link" href="./sell/index.php">Go to Dashboard</a></li>
                            </ul>
                        </div>
                    </div>';
                }
            //else display default homepage for not logged in users
            } else {
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
                                <li class="nav-item"><a class="nav-link" href="./feedback.php">Give Feedback</a></li>
                            </ul>
                        </div>
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