<?php include 'header.php'; ?>
        
<div class="panel d-flex flex-row">
        <div class="sidenav">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link" href="settings.php">Edit Profile</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="changepwd.php">Change Password</a>
                  </li>
            </ul>
        </div>

<div class="settings-panel flex-grow-1">
    <form method="post" action="changepwd.php">
        <h1 class="d-flex justify-content-center">Change Password</h1>
        <div class="form-group">
            <label for="currentpwd">Current password</label>
            <input type="password" class="form-control" name="currentpwd" placeholder="Enter current password" required>
        </div>
        <div class="form-group">
            <label for="newpwd">New Password</label>
            <input type="password" class="form-control" name="newpwd" placeholder="Enter new password" required>
        </div>
        <div class="form-group">
            <label for="confirmpwd">Confirm New Password</label>
            <input type="password" class="form-control" name="confirmpwd" placeholder="Confirm new password" required>
        </div>
        
        <button type="submit" class="btn btn-primary btn-block" name="chpwd_btn">Save</button>
    </form>
</div>
</div>

<?php include('footer.php')?>