<?php include 'header.php'; ?> 
    <div class="panel form-panel border">
        <h1 class="d-flex justify-content-center">Manage Books</h1>
            
        <div>
            <form method="GET" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                <?php list_books(); ?>
            </form>
        </div>
    </div>
<?php include('footer.php')?>