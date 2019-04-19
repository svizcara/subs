<?php include 'header.php';?>

<div class="panel form-panel border">
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <h1 class="d-flex justify-content-center">Reset password</h1>
        <div class="form-group">
            <label for="email">Email address</label>
            <input type="email" class="form-control" name="email" placeholder="Enter your email address" required>
        </div>

        <button type="submit" class="btn btn-primary btn-block" name="resetpwd_btn">Submit </button>
    </form>
</div>

<?php include 'footer.php';?>
