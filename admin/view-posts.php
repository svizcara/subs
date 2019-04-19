<?php include 'header.php'; ?> 
    <div class="panel form-panel border">
        <h1 class="d-flex justify-content-center">Manage Books</h1>
            
        <div>
            <form method="GET" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                <input type="text" name="id" value="<?php if(isset($_GET['id'])) echo $_GET['id'];?>" hidden>
                
                <?php
                if ( isset($_GET['id'])){
                        manage_books($_GET['id']);
                } else {
                    echo "Select user from <a href='manage-users.php'>User Management</a> to manage book.";
                } 
                ?>
            </form>
        </div>
    </div>
<?php include('footer.php')?>