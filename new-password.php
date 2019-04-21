<?php include 'header.php'; ?>
        
<div class="panel form-panel">
    <div class="wide-panel">
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <h1 class="d-flex justify-content-center">Set New Password</h1>
        <div class="form-group">
            <label for="newpwd">New Password</label>
            <input type="password" class="form-control" name="newpwd" placeholder="Enter new password" required>
        </div>
        <div class="form-group">
            <label for="confirmpwd">Confirm New Password</label>
            <input type="password" class="form-control" name="confirmpwd" placeholder="Confirm new password" required>
        </div>
        <input type="text" value="<?php if(isset($_GET['token'])) echo $_GET['token'];?>" name="token" hidden>
        <button type="submit" class="btn btn-primary btn-block" name="newpwd_btn">Save</button>
    </form>
    </div>
</div>

<?php include('footer.php')?>