<?php include 'header.php'; 
if (isset($_GET['id'])) {
    $book_id = $_GET['id'];
    $query = "SELECT * FROM books WHERE book_id='$book_id'";
    $retval = mysqli_query($db, $query);
    $_SESSION['book'] = mysqli_fetch_assoc($retval); 
}
$book = $_SESSION['book'];
?>

    <div class="panel form-panel border">
        <h1 class="d-flex justify-content-center">Edit Book</h1>
         <form method="post" enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
             <div class="form-group">
                <label for="bookID">Book ID</label>
                <input type="text" class="form-control" name="bookID" value="<?php echo $book['book_id']?>" readonly>
             </div>
             
             <div class="form-group">
                <label for="title">Title</label>
                <span class="required-label">(required)</span>
                <input type="text" class="form-control" name="title" placeholder="Enter book title" value="<?php echo $book['title']?>" required>
             </div>
             <div class="form-group">
                <label for="author">Author</label>
                <span class="required-label">(required)</span>
                <input type="text" class="form-control" name="author" placeholder="Enter book author" value="<?php echo $book['author']?>" required>
             </div>
             <div class="form-group">
                <label for="edition">Book Edition (if applicable)</label>
                <input type="text" class="form-control" name="edition" placeholder="Enter book edition" value="<?php echo $book['edition']?>" >
             </div>
             <div class="form-group">
                <label for="pubyear">Year published</label>
                <input type="number" min="0" max="<?php echo date('Y');?>" class="form-control" name="pubyear" placeholder="Enter publication year" value="<?php echo $book['year_published']?>" >
             </div>
             <div class="form-group">
                <label for="publisher">Publisher</label>
                <input type="text" class="form-control" name="publisher" placeholder="Enter book publisher" value="<?php echo $book['publisher']?>" >
             </div>
             <div class="form-group">
                <label for="category">Book category</label>
                <span class="required-label">(required)</span>
                <select class="form-control" name="category" required>
                    <?php list_category($book['category']); ?>
                </select>
             </div>
             <div class="form-group">
                <label for="condition">Book condition</label>
                <span class="required-label">(required)</span>
                <select class="form-control" name="condition" required>
                    <option <?php if ($book['book_condition']=="as new") echo "selected";?> value="as new" >As New</option>
                    <option <?php if ($book['book_condition']=="f") echo "selected";?> value="f">Fine</option>
                    <option <?php if ($book['book_condition']=="vg") echo "selected";?> value="vg">Very Good</option>
                    <option <?php if ($book['book_condition']=="g") echo "selected";?> value="g">Good</option>
                    <option <?php if ($book['book_condition']=="fair") echo "selected";?> value="fair">Fair</option>
                    <option <?php if ($book['book_condition']=="poor") echo "selected";?> value="poor">Poor</option>
                    <option <?php if ($book['book_condition']=="el") echo "selected";?> value="el">Ex-library</option>
                    <option <?php if ($book['book_condition']=="bcl") echo "selected";?> value="bcl">Book Club</option>
                    <option <?php if ($book['book_condition']=="bcp") echo "selected";?> value="bcp">Binding Copy</option>
                </select>
             </div>
             <div class="form-group">
                <label for="details">Details</label>
                <textarea class="form-control" name="details" placeholder="Enter additional details"><?php echo $book['details']?></textarea>
             </div>
             <div class="form-group">
                <label for="photo">Book actual photo</label>
                <input type="file" class="btn form-control"  accept="image/*" name="photo" onchange="displayImage(this);">
                <img id="photo-preview" src="<?php echo "../content/uploads/".$book['photo']?>" alt="Book photo"/>
             </div>
             <div class="form-group">
                <label for="price">Selling price</label>
                <span class="required-label">(required)</span>
                <input type="number" class="form-control" name="price" placeholder="Enter your selling price" value="<?php echo $book['price']?>"  required>
             </div>
             <div class="form-group">
                <label for="location">Location</label>
                <span class="required-label">(required)</span>
                <select class="form-control" name="location" required>
                    <?php list_regions($book['location']); ?>
                </select> 
             </div>
             
             <button type="submit" class="btn btn-primary" name="editsave_btn">Save</button>
             <button type="submit" class="btn btn-primary" name="editpublish_btn">Publish</button>
        </form>
    </div>
<?php include('footer.php')?>