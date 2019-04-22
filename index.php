<?php include 'header.php';?>

        <div class="panel" id="main-content" class="row">
             
            <div class="search-panel">
                <form method="get" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                    <div class="btn-group col-lg-12" role="group">
                        <input type="text" class="col-lg-9" placeholder="Search book..." name="q" value=<?php if(isset($_GET['q'])) echo $_GET['q'];?>>
                        <input type="text" name="sortoption" value="<?php if(isset($_GET['sortoption'])) echo $_GET['sortoption'];?>"  hidden>
                        <input type="text" name="catid" value="<?php if(isset($_GET['catid']))  echo $_GET['catid'];?>"  hidden>
                        <button type="submit" class="btn btn-secondary" name="search_btn" value=1>Search</button>
                        <button type="submit" class="btn btn-secondary" name="search_btn" value=2>Title</button>
                        <button type="submit" class="btn btn-secondary" name="search_btn" value=3>Author</button>
                        
                    </div>
                </form>
            </div>
            <div class="filter-sort-panel">
                <div class="filter-panel">
<!--                    <span class="label">Filter by category: </span>-->

                    <form method="get" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                        <input type="text" name="sortoption" value="<?php if(isset($_GET['sortoption'])) echo $_GET['sortoption'];?>"  hidden>
                        <input type="text" name="q" value="<?php if(isset($_GET['q'])) echo $_GET['q'];?>"  hidden>
                        <input type="text" name="search_btn" value="<?php if(isset($_GET['search_btn'])) echo $_GET['search_btn'];?>"  hidden>
                    <?php list_category_as_filters(); ?>
                    </form>
                </div>
        
                <div class="sort-panel">
                    <form method="get" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" >
                        <span class="label">Sort by: </span>
                        <input type="text" name="q" value="<?php if(isset($_GET['q'])) echo $_GET['q'];?>"  hidden>
                        <input type="text" name="search_btn" value="<?php if(isset($_GET['search_btn'])) echo $_GET['search_btn'];?>"  hidden>
                        <input type="text" name="catid" value="<?php if(isset($_GET['catid']))  echo $_GET['catid'];?>"  hidden>

                        <select class="custom-select custom-select-sm" onChange="this.form.submit()" name="sortoption">
                            <option <?php if($sortby == 0) echo 'selected';?> value=0>Date Posted</option>
                            <option <?php if($sortby == 1) echo 'selected';?> value=1>Selling Price</option>
                            <option <?php if($sortby == 2) echo 'selected';?> value=2>Title (A to Z)</option>
                            <option <?php if($sortby == 3) echo 'selected';?> value=3>Title (Z to A)</option>
                            <option <?php if($sortby == 4) echo 'selected';?> value=4>Author (A to Z)</option>
                            <option <?php if($sortby == 5) echo 'selected';?> value=5>Author (Z to A)</option>
                        </select>
                        <noscript><input type="submit" value=""></noscript>
                    </form>
                </div>
            </div>
            
            
            <div class="catalog-panel clearfix">
                <!--book catalog here-->
                <?php 
                    display_catalog($filterby, $search, $sortby, ''); 
                ?>
            </div>
        </div>
        
<?php include 'footer.php';?>