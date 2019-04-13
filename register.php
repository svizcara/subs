<?php include 'header.php';?>

<div class="panel form-panel border">
   
    <h1 class="d-flex justify-content-center">Register</h1>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    
        
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" class="form-control" name="username" placeholder="Enter username" value="<?php echo $username; ?>" pattern="[A-Za-z0-9]+" required>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-sm-6">
                    <label for="firstname">First Name</label>
                    <input type="text" class="form-control" name="firstname" placeholder="First name" value="<?php echo $firstname; ?>" required>
                </div>
                <div class="col-sm-6">
                    <label for="lastname">Last Name</label>
                    <input type="text" class="form-control" name="lastname" placeholder="Last name" value="<?php echo $lastname; ?>" required>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="email1">Email</label>
            <input type="email" class="form-control" name="email1" placeholder="Please enter a valid email address" value="<?php echo $email1; ?>" required>
        </div>
        <div class="form-group">
            <label for="email2">Confirm Email</label>
            <input type="email" class="form-control" name="email2" placeholder="Confirm your email address" value="<?php echo $email2; ?>" required>
        </div>

        <button type="submit" class="btn btn-primary btn-block" name="register_btn">Register</button>
        
        <span class="d-flex justify-content-center">
            Already a member? <a href="login.php">Sign in</a>
        </span>
    </form>
</div>

<?php include 'footer.php';?>