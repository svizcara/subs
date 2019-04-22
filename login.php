<?php include 'header.php';?>

<div class="panel form-panel clearfix">
    <div class="" id="login-panel-design-image">
    </div>
    <div class="narrow-panel">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <h1 class="d-flex justify-content-center">Login</h1>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" name="username" placeholder="Enter username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" name="password" placeholder="Enter password" required>
            </div>

            <button type="submit" class="btn btn-primary" name="login_btn">Login</button>
            <span class="alternative">  Not yet registered? <a href="register.php"> Sign up </a> <br/><a href="forgot-password.php"> Forgot your password? </a> </span>
        </form>
    </div>
</div>

<?php include 'footer.php';?>
