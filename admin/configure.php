<?php include 'header.php'; ?> 
<?php get_siteinfo(); ?>
    <div class="panel form-panel ">
        <h1 class="d-flex justify-content-center">Manage Site Configuration</h1>
        <div>
            <form method="post" enctype="multipart/form-data" action="<?php echo $page?>">
            <h2>Appearance</h2>
            <div class="form-group">
                <label for="theme">Select Theme</label>
                <select class="form-control custom-select" name="theme">
                    <option <?php echo ( $_SESSION['siteinfo']['theme']=='default' ? 'selected' : '')?> value="default">Default</option>
                    <option <?php echo ( $_SESSION['siteinfo']['theme']=='light' ? 'selected' : '')?> value="light">Light</option>
                    <option <?php echo ( $_SESSION['siteinfo']['theme']=='dark' ? 'selected' : '')?> value="dark">Dark</option>
                </select>
            </div>
            
            <h2>Website Details</h2>
            
            <div class="form-group">
                <label for="site_title">Site Title</label>
                <input type="text" class="form-control" name="site_title" placeholder="Enter site title" value="<?php echo $_SESSION['siteinfo']['site_title']?>" required>
             </div>
             <div class="form-group">
                <label for="name">Site Name</label>
                <input type="text" class="form-control" name="site_name" placeholder="Enter site name" value="<?php echo $_SESSION['siteinfo']['site_name']?>" required>
             </div>
            <div class="form-group">
                <label for="logo">Site logo</label>
                <input type="file" class="btn form-control"  accept="image/*" name="logo" onchange="displayImage(this);" value="">
                <img id="photo-preview" src="<?php echo "../content/".$_SESSION['siteinfo']['site_logo']?>" alt="Site Logo"/>
             </div>
             <div class="form-group">
                <label for="admin_email">Email Address</label>
                <input type="email" class="form-control" name="admin_email" placeholder="Enter site email address" value="<?php echo $_SESSION['siteinfo']['admin_email']?>">
             </div>
             <button type="submit" class="btn btn-primary" name="savecfg_btn">Save</button>
            </form>
        </div>
    </div>
<?php include('footer.php')?>