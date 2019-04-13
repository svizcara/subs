<?php include 'header.php';?>

<div class="panel form-panel border">
    <form method="post" action="login.php">
        <h1 class="d-flex justify-content-center">Login</h1>
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" class="form-control" name="username" placeholder="Enter username" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" name="password" placeholder="Enter password" required>
        </div>

        <button type="submit" class="btn btn-primary btn-block" name="login_btn">Login</button>
        <span class="d-flex justify-content-center"> <a href=""> Forgot your password? </a> </span>

    </form>
</div>

<?php include 'footer.php';?>
