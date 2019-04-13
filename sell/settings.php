<?php include 'header.php'; ?> 
    <div class="panel border d-flex flex-row">
        <div class="sidenav">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link" href="settings.php">Edit Profile</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="changepwd.php">Change Password</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="#">Email and SMS</a>
                  </li>
            </ul>
        </div>

 <!-- logged in user information -->
		<div class="settings-panel flex-grow-1">
                <h1 class="d-flex justify-content-center">Edit profile</h1>
                <div class="d-flex flex-column">
                    <button class="photo-frame align-self-center">
                        <img alt="Profile Photo" src="../content/uploads/profile-photo.png"/>
                    </button>
                    <a href="#" class="align-self-center">Change Profile Photo</a>
                </div>
            
            
                
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                
                    
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" class="form-control" name="username" placeholder="Enter username" value="<?php echo $_SESSION['user']['username']; ?>" disabled required>
                </div>    
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-6">
                        <label for="firstname">First Name</label>
                        <input type="text" class="form-control" name="firstname" placeholder="First name" pattern="[A-Za-z]+" value="<?php echo $_SESSION['user']['first_name']; ?>" required>
                    </div>
                    <div class="col-sm-6">
                        <label for="lastname">Last Name</label>
                        <input type="text" class="form-control" name="lastname" placeholder="Last name" pattern="[A-Za-z]+" value="<?php echo$_SESSION['user']['last_name']; ?>" required>
                    </div>
                </div>
            </div>    
            <div class="form-group">
                <label for="location">Location</label>
                <input type="text" class="form-control" name="location" placeholder="Enter location" value="<?php echo $_SESSION['userinfo']['location']; ?>" pattern="[A-Za-z0-9 .]+" required>
            </div>
            
            <div class="form-group">
                <label for="mobile">Mobile number</label>
                <input type="tel" class="form-control" name="mobile" pattern="[0-9]{11}" placeholder="09xxxxxxxxx" value="<?php echo $_SESSION['userinfo']['mobile'] ; ?>" required>
            </div>
                    
            <div class="form-group">
                <label for="shortbio">Short Bio</label>
                <textarea class="form-control" name="shortbio" placeholder="Give a brief description of yourself."><?php echo $_SESSION['userinfo']['shortbio']; ?></textarea>
            </div>
                    
  <!-- private information -->       
            <h2 class="d-flex justify-content-center">Private information</h2>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" name="email" placeholder="Please enter a valid email address" value="<?php  echo $_SESSION['user']['email']; ?>" required>
            </div>
                    
                    
            <div class="form-group">
                <label for="bday">Birthday</label>
                <input type="date" class="form-control" name="bday" placeholder="Birthdate" value="<?php echo $_SESSION['userinfo']['birthdate'] ; ?>">
            </div>
                    
            <div class="form-group">
                <label for="gender">Gender</label>
                <select class="form-control" name="gender">
                    <option <?php if($_SESSION['userinfo']['gender']=='male') echo "selected";?> value="male">Male</option>
                    <option <?php if($_SESSION['userinfo']['gender']=='female') echo "selected";?> value="female">Female</option>
                    <option <?php if($_SESSION['userinfo']['gender']=='') echo "selected";?> value="">Not Specified</option>
                </select>
            </div>
            

        <button type="submit" class="btn btn-primary btn-block" name="saveprofile_btn">Save</button>
    </form>
    </div>
    </div>
<?php include('footer.php')?>