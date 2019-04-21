<?php include 'header.php';
if (isset($_SESSION['user'])) {
  	if (empty($_SESSION['userinfo']['location']) || empty($_SESSION['userinfo']['mobile'])){
        $_SESSION['msg'] = '<div class="alert alert-warning">Please update your information first.</div>';
        exit(header("location: settings.php"));
    } 
}
?> 
    <div class="panel form-panel">
        <div class="wide-panel">
        <h1 class="d-flex justify-content-center">Sell Book</h1>
         <form method="post" enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
             <div class="form-group">
                <label for="title">Title</label>
                <span class="required-label">(required)</span>
                <input type="text" class="form-control" name="title" placeholder="Enter book title" required>
             </div>
             <div class="form-group">
                <label for="author">Author</label>
                <span class="required-label">(required)</span>
                <input type="text" class="form-control" name="author" placeholder="Enter book author" required>
             </div>
             <div class="form-group">
                <label for="edition">Book Edition</label>
                <span class="required-label">(if applicable)</span>
                <input type="text" class="form-control" name="edition" placeholder="Enter book edition">
             </div>
             <div class="form-group">
                <label for="pubyear">Year published</label>
                <input type="number" min="0" max="<?php echo date('Y');?>" class="form-control" name="pubyear" placeholder="Enter publication year">
             </div>
             <div class="form-group">
                <label for="publisher">Publisher</label>
                <input type="text" class="form-control" name="publisher" placeholder="Enter book publisher">
             </div>
             <div class="form-group">
                <label for="category">Book category</label>
                <span class="required-label">(required)</span>
                <select class="form-control custom-select" name="category" required>
                    <?php list_category(); ?>
                </select>
             </div>
             <div class="form-group">
                <label for="condition">Book condition</label>
                <span class="required-label">(required)</span>
                <select class="form-control custom-select" name="condition" required>
                    <option value="As New">As New</option>
                    <option value="Fine">Fine</option>
                    <option value="Very Good">Very Good</option>
                    <option value="Good">Good</option>
                    <option value="Fair">Fair</option>
                    <option value="Poor">Poor</option>
                    <option value="Ex-library">Ex-library</option>
                    <option value="Book Club">Book Club</option>
                    <option value="Binding Copy">Binding Copy</option>
                </select>
             </div>
             <div class="form-group">
                <label for="details">Additional Details</label>
                <textarea class="form-control" name="details" placeholder="Enter additional details"></textarea>
             </div>
             <div class="form-group">
                <label for="photo">Book actual photo</label>
                <input type="file" class="btn form-control"  accept="image/*" name="photo" onchange="displayImage(this);">
                <img id="photo-preview" src="#" alt="Book photo" hidden/>
             </div>
             <div class="form-group">
                <label for="price">Selling price</label>
                <span class="required-label">(required)</span>
                <input type="number" min="1" class="form-control" name="price" placeholder="Enter your selling price" required>
             </div>
             <div class="form-group">
                <label for="location">Location</label>
                <span class="required-label">(required)</span>
                <select class="form-control" name="location" required>
                    <?php list_regions(); ?>
                </select> 
             </div>
             
             <button type="submit" class="btn btn-primary" name="savebook_btn">Save</button>
             <button type="submit" class="btn btn-primary" name="publish_btn">Publish</button>
        </form>
        </div>
    </div>
<?php include('footer.php')?>