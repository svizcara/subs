<?php include 'header.php'; ?> 
    <div class="panel form-panel border">
        <h1 class="d-flex justify-content-center">Sell Book</h1>
         <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
             <div class="form-group">
                <label for="title">Title</label>
                <input type="text" class="form-control" name="title" placeholder="Enter book title" required>
             </div>
             <div class="form-group">
                <label for="author">Author</label>
                <input type="text" class="form-control" name="author" placeholder="Enter book author" required>
             </div>
             <div class="form-group">
                <label for="edition">Book Edition (if applicable)</label>
                <input type="text" class="form-control" name="edition" placeholder="Enter book edition">
             </div>
             <div class="form-group">
                <label for="pubyear">Year published</label>
                <input type="number" class="form-control" name="pubyear" placeholder="Enter publication year" required>
             </div>
             <div class="form-group">
                <label for="publisher">Publisher</label>
                <input type="text" class="form-control" name="publisher" placeholder="Enter book publisher">
             </div>
             <div class="form-group">
                <label for="category">Book category</label>
                <input type="text" class="form-control" name="category" placeholder="Enter book category" required>
             </div>
             <div class="form-group">
                <label for="condition">Book condition</label>
                <input type="text" class="form-control" name="condition" placeholder="Enter book condition" required>
             </div>
             <div class="form-group">
                <label for="details">Details</label>
                <textarea class="form-control" name="details" placeholder="Enter additional details"></textarea>
             </div>
             <div class="form-group">
                <label for="photo">Book actual photo</label>
                <input type="text" class="form-control" name="photo" placeholder="Enter book photo" required>
             </div>
             <div class="form-group">
                <label for="price">Selling price</label>
                <input type="text" class="form-control" name="price" placeholder="Enter your selling price" required>
             </div>
             <div class="form-group">
                <label for="location">Location</label>
                <input type="text" class="form-control" name="location" placeholder="Enter your location" required>
             </div>
             
             <button type="submit" class="btn btn-primary" name="savebook_btn">Save</button>
             <button type="submit" class="btn btn-primary" name="publish_btn">Publish</button>
        </form>
    </div>
<?php include('footer.php')?>