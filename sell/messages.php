<?php include 'header.php'; ?>

    <div class="panel clearfix">
        <h1 class="d-flex justify-content-center">View Messages</h1>
        
        <?php list_messages(); ?>
            
        <?php 
        if (isset($_GET['book_id'])) {
            echo'<div class="message-box border col col-lg-8 float-right">';
            show_messages($_GET['book_id'], $_GET['usertwo_id']); 
            echo '</div>';
        } 
        ?>
        
    </div>
        
<?php include('footer.php')?>