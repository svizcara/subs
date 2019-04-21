<?php include 'header.php'; ?> 
    <div class="panel form-panel clearfix">
        <div class="" id="feedback-panel-design-image">
        </div>
        <div class="narrow-panel">
            <h1 class="d-flex justify-content-center">Give Feedback</h1>
            <form method="post" enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                 <div class="form-group">
                    <label for="name">Name</label>
                    <span class="required-label">(required)</span>
                    <input type="text" class="form-control" name="name" placeholder="Please provide a name" value="<?php echo ( isset($_SESSION['user']) ? $_SESSION['user']['first_name'].' '.$_SESSION['user']['last_name'] : '')?>" required>
                 </div>
                 <div class="form-group">
                    <label for="email">Email</label>
                    <span class="required-label">(required)</span>
                    <input type="email" class="form-control" name="email" placeholder="Enter email address" value="<?php echo ( isset($_SESSION['user']) ? $_SESSION['user']['email'] : '')?>" required>
                 </div>
                 <div class="form-group">
                    <label for="feedback">Your feedback</label>
                    <span class="required-label">(required)</span>
                    <textarea class="form-control" name="feedback" placeholder="Please help us improve our website by giving your feedback here." required></textarea>
                 </div>

                 <button type="submit" class="btn btn-primary" name="submitfeedback_btn">Submit</button>
            </form>
        </div>
    </div>
<!--<?php include('footer.php')?>-->