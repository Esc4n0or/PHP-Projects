<?php

    // Cat = Category

	session_start();

	// $pageTitle = '';

	if (isset($_SESSION['ADMIN'])) {

		include 'init.php';

		$action = isset($_GET['action']) ? $_GET['action'] : 'manage';

		if ($action == 'manage') {

            $sort = 'ASC';
            $sort_array = array('ASC','DESC');

            if (isset($_GET['sort']) && in_array($_GET['sort'],$sort_array)) {
                $sort = $_GET['sort'];
            }
            
            $stmt = $con->prepare("SELECT * FROM categories WHERE parent = 0 ORDER BY Ordering $sort");

			$stmt->execute();

			$rows = $stmt->fetchAll(); ?>

            <h1 class="text-center">Manage Categories</h1>
            <div class="container categories">
                <div class="card card-default">
                    <div class="card-header">
                        <i class="fa fa-edit"></i>Manage Categories
                        <div class="option pull-right">
                                <i class="fa fa-sort"></i> Ordering: [
                                <a class="<?php if ($sort == 'ASC') { echo 'active'; } ?>" href="?sort=ASC">Asc</a> | 
                                <a class="<?php if ($sort == 'DESC') { echo 'active'; } ?>" href="?sort=DESC">Desc</a> ]
                                <i class="fa fa-eye"></i> View: [
                                <span class="active" data-view="full">Full</span> |
                                <span data-view="classic">Classic</span> ]
                            </div>
                        </div>
                    <div class="card-body">
                            <?php

                                foreach($rows as $row){
                                    echo "<div class='cat'>";

                                        echo "<div class='hidden-buttons'>";
										    echo "<a href='categories.php?action=edit&catid=" . $row['ID'] . "' class='btn btn-xs btn-primary'><i class='fa fa-edit'></i> Edit</a>";
										    echo "<a href='categories.php?action=delete&catid=" . $row['ID'] . "' class='confirm btn btn-xs btn-danger'><i class='fa fa-close'></i> Delete</a>";
									    echo "</div>";

                                        echo "<h3>"   . $row['Name'] . "</h3>";
                                        echo "<div class='full-view'>";
                                            echo "<p>"; if ($row['Description'] == '') {echo "This Description is Empty";} else {echo $row['Description'];} echo "</p>";
                                            if ($row['Visibility'] == 1) {echo "<span class='visibility cat-span'>Hidden</span>";}
                                            if ($row['Allow_Comment'] == 1) {echo "<span class='visibility cat-span'>Comment disable</span>";}
                                            if ($row['Allow_Ads'] == 1) {echo "<span class='visibility cat-span'>Ads disable</span>";}
                                        echo "</div>";
                                        $child_cats = getAll('*', 'categories', "Where parent = {$row['ID']}", '', 'ID', 'ASC');
                                        if(!empty($child_cats)) {

                                            echo "<h4 class='child-head'>Child Categories</h4>";
                                            echo "<ul class='list-unstyled child-cats'>";

                                                foreach($child_cats as $child){
                                                    echo "<li class='child-link'>
                                                            <a href='categories.php?action=edit&catid=" . $child['ID'] . "'>" . $child['Name'] . "</a>
                                                            <a href='categories.php?action=delete&catid=" . $child['ID'] . "' class='show-delete confirm'> Delete</a>
											            </li>";
                                                }
                                            echo "</ul>";
                                        }
                                    echo "</div>";
                                    echo "<hr>";

                                

                                    
                                    
                                }


                            ?>
                    </div>
                </div>
                <a class="add-category btn btn-primary" href="categories.php?action=add"><i class="fa fa-plus"></i> Add New Category</a>

            </div>




	<?php	} elseif ($action == 'add') { ?>

                    <h1 class="text-center">Add New Category</h1>
                    <div class="container">
                        <form class="form-horizontal" action="?action=insert" method="POST">
                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-label">Name</label>
                                <div class="col-sm-10 col-md-4">
                                    <input type="text" class="form-control" name="name" autocomplete="off" required>
                                </div>
                            </div>
                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-label">Description</label>
                                <div class="col-sm-10 col-md-4">
                                    <input type="text" class="form-control" name="description">
                                </div>
                            </div>
                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-label">Ordering</label>
                                <div class="col-sm-10 col-md-4">
                                    <input type="text" class="form-control" name="ordering">
                                </div>
                            </div>

                            <!-- Start Parent Category -->
                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-label">Parent?</label>
                                <div class="col-sm-10 col-md-4">
                                    <select name="parent">
                                        <option value="0">None</option>
                                        <?php
                                          $cats =  getAll('*', 'categories', 'where parent = 0', "" , 'ID', "ASC");
                                          foreach($cats as $cat) {
                                              echo "<option value='" . $cat['ID'] ."'>" .$cat['Name'] . "</option>";
                                          }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <!-- End Parent Category -->
                            <!-- Start Visibility Field -->
                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-label">Visibile</label>
                                <div class="col-sm-10 col-md-6">
                                    <div>
                                        <input id="vis-yes" type="radio" name="visibility"  value=0 checked>
                                        <label for="vis-yes">Yes</label>
                                    </div>
                                    <div>
                                        <input id="vis-no" type="radio" name="visibility"  value=1>
                                        <label for="vis-no">No</label>
                                    </div>
                                </div>
                            </div>
                            <!-- End Visibility Field -->

                            <!-- Start Allow_comment Field -->
                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-label">Allow Commenting</label>
                                <div class="col-sm-10 col-md-6">
                                    <div>
                                        <input id="com-yes" type="radio" name="allow_comment"  value=0 checked>
                                        <label for="com-yes">Yes</label>
                                    </div>
                                    <div>
                                        <input id="com-no" type="radio" name="allow_comment"  value=1>
                                        <label for="com-no">No</label>
                                    </div>
                                </div>
                            </div>
                            <!-- End Allow_comment Field -->

                            <!-- Start Allow_Ads Field -->
                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-label">Allow Ads</label>
                                <div class="col-sm-10 col-md-6">
                                    <div>
                                        <input id="ads-yes" type="radio" name="allow_ads"  value=0 checked>
                                        <label for="ads-yes">Yes</label>
                                    </div>
                                    <div>
                                        <input id="ads-no" type="radio" name="allow_ads"  value=1>
                                        <label for="ads-no">No</label>
                                    </div>
                                </div>
                            </div>
                            <!-- End Allow_Ads Field -->

                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <input type="submit" class="btn btn-primary btn-lg" value="Add Category">
                                </div>
                            </div>   
                        </form>

                    </div>


		<?php } elseif ($action == 'insert') {

                        if ($_SERVER['REQUEST_METHOD'] == 'POST'){

                            echo "<h1 class='text-center'>Insert Category</h1>";
                            echo "<div class='container'>";

                            // Get Values to insert/Add them to the database.

                            $name          = $_POST['name'];
                            $description   = $_POST['description'];
                            $order         = $_POST['ordering'];
                            $parent        = $_POST['parent'];
                            $visibile      = $_POST['visibility'];
                            $all_com       = $_POST['allow_comment'];
                            $all_ads       = $_POST['allow_ads'];

                                // Check if the Category exist or not.

                            $exist = checkExist("Name", "categories", $name);

                            if ($exist == 1) {

                                echo "<div class='container'>";

                                    $faildAddCat =  "<div class='alert alert-danger'>Faild to add Category.</div>";
                                    redirectBack($faildAddCat, $url='back');

                                echo "</div>";
                            } else {

                                $stmt = $con->prepare("INSERT INTO

                                                    categories(Name, Description, Ordering, parent, Visibility, Allow_Comment,  Allow_Ads)

                                                    VALUES(:name, :desc, :order, :parent, :visibile, :comment, :ads)" ); // You can choose any names in VALUES field.

                            $stmt->execute(array(
                                'name'      => $name,
                                'desc'      => $description,
                                'order'     => $order,
                                'parent'    => $parent,
                                'visibile'   => $visibile,
                                'comment'   => $all_com,
                                'ads'       => $all_ads));

                            echo "<div class='container'>";

                                $addedCat = "<div class='alert alert-success'>" . $stmt->rowCount() . " New Category Inserted in the Database" . "</div>";
                                redirectBack($addedCat, $url='back');

                            echo "</div>";
                            }

                                

                            
                        } else {

                            echo "<div class='container'>";

                                $errorMg =  "<div class='alert alert-danger'>You can't browser this Page Directly</div>";
                                redirectBack($errorMg);

                            echo "</div>";
                        }
                        echo "</div>";



		} elseif ($action == 'edit') {

            $cat_id = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0; 

            $stmt = $con->prepare("SELECT * FROM categories WHERE ID = ?"); // Select * about the Category due to its ID.
            $stmt->execute(array($cat_id));
            $row = $stmt->fetch(); // Fetch the data
            $record = $stmt->rowCount(); // Check if the Category exist in the DB or not.


            //  Show Data to Category if the record was found.


            if ($record > 0) { ?>

                <h1 class="text-center">Edit Category</h1>
                    <div class="container">
                        <form class="form-horizontal" action="?action=update" method="POST">
                            <input type="hidden" name="catid" value="<?php echo $cat_id ?>">
                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-label">Name</label>
                                <div class="col-sm-10 col-md-4">
                                    <input type="text" class="form-control" name="name" required value="<?php echo $row['Name'] ?>">
                                </div>
                            </div>
                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-label">Description</label>
                                <div class="col-sm-10 col-md-4">
                                    <input type="text" class="form-control" name="description" value="<?php echo $row['Description'] ?>">
                                </div>
                            </div>
                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-label">Ordering</label>
                                <div class="col-sm-10 col-md-4">
                                    <input type="text" class="form-control" name="ordering" value="<?php echo $row['Ordering'] ?>">
                                </div>
                            </div>

                            <!-- Start Parent Category -->
                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-label">Parent?</label>
                                <div class="col-sm-10 col-md-4">
                                    <select name="parent">
                                        <option value="0">None</option>
                                        <?php
                                          $cats =  getAll('*', 'categories', 'where parent = 0', "" , 'ID', "ASC");
                                          foreach($cats as $cat) {
                                              echo "<option value='" . $cat['ID'] ."'";
                                              if($row['parent'] == $cat['ID']){
                                                  echo "Selected";
                                              }
                                              echo ">" .$cat['Name'] . "</option>";
                                          }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <!-- End Parent Category -->

                            <!-- Start Visibility Field -->
                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-label">Visibile</label>
                                <div class="col-sm-10 col-md-6">
                                    <div>
                                        <input id="vis-yes" type="radio" name="visibility"  value=0 <?php if($row['Visibility'] == 0) {echo "checked";} ?>>
                                        <label for="vis-yes">Yes</label>
                                    </div>
                                    <div>
                                        <input id="vis-no" type="radio" name="visibility"  value=1 <?php if($row['Visibility'] == 1) {echo "checked";} ?>>
                                        <label for="vis-no">No</label>
                                    </div>
                                </div>
                            </div>
                            <!-- End Visibility Field -->

                            <!-- Start Allow_comment Field -->
                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-label">Allow Commenting</label>
                                <div class="col-sm-10 col-md-6">
                                    <div>
                                        <input id="com-yes" type="radio" name="allow_comment"  value=0 <?php if($row['Allow_Comment'] == 0) {echo "checked";} ?>>
                                        <label for="com-yes">Yes</label>
                                    </div>
                                    <div>
                                        <input id="com-no" type="radio" name="allow_comment"  value=1 <?php if($row['Allow_Comment'] == 0) {echo "checked";} ?>>
                                        <label for="com-no">No</label>
                                    </div>
                                </div>
                            </div>
                            <!-- End Allow_comment Field -->

                            <!-- Start Allow_Ads Field -->
                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-label">Allow Ads</label>
                                <div class="col-sm-10 col-md-6">
                                    <div>
                                        <input id="ads-yes" type="radio" name="allow_ads"  value=0 <?php if($row['Allow_Ads'] == 0) {echo "checked";} ?>>
                                        <label for="ads-yes">Yes</label>
                                    </div>
                                    <div>
                                        <input id="ads-no" type="radio" name="allow_ads"  value=1 <?php if($row['Allow_Ads'] == 1) {echo "checked";} ?>>
                                        <label for="ads-no">No</label>
                                    </div>
                                </div>
                            </div>
                            <!-- End Allow_Ads Field -->

                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <input type="submit" class="btn btn-primary btn-lg" value="Edit Category">
                                </div>
                            </div>   
                        </form>

                    </div>


        <?php 
        }  else {

                echo "<div class='container'>";

                    $notFoundID =  "<div class='alert alert-danger'>There is no Such ID with this Number</div>";
                    redirectBack($notFoundID);

                echo "</div>";
                }


		} elseif ($action == 'update') {

            echo "<h1 class='text-center'>Update Category</h1>";
            echo "<div class='container'>";
            if ($_SERVER['REQUEST_METHOD'] == 'POST'){

                // Get Updated Values to insert them to the database.
                $id         = $_POST['catid'];
                $name       = $_POST['name'];
                $desc       = $_POST['description'];
                $ord        = $_POST['ordering'];
                $parent     = $_POST['parent'];
                $visibile   = $_POST['visibility'];
                $comm       = $_POST['allow_comment'];
                $adver      = $_POST['allow_ads'];

                $stmt = $con->prepare("UPDATE categories
                                        SET
                                            Name = ?,
                                            Description = ?,
                                            Ordering = ?,
                                            parent = ?,
                                            Visibility = ?,
                                            Allow_Comment = ?,
                                            Allow_Ads = ?
                                        WHERE
                                            ID = ?");
                $stmt->execute(array($name, $desc, $ord, $parent, $visibile, $comm, $adver, $id));

                echo "<div class='container'>";

                    $updatedCat = "<div class='alert alert-success'>" . $stmt->rowCount() . " Category Updated in the Database" . "</div>";
                    redirectBack($updatedCat, $url='back');

                echo "</div>";
                

                 
            } else {

                echo "<div class='container'>";

                    $errorMsg =  "<div class='alert alert-danger'>You can't browser this Page Directly</div>";
                    redirectBack($errorMsg);

                echo "</div>";
            }
         echo "</div>";   


		} elseif ($action == 'delete') {

            echo "<h1 class='text-center'>Delete Category</h1>";
            echo "<div class='container'>";

                $cat_id = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0; // Check User ID 

                $exist = checkExist("ID", "categories", $cat_id);

                //  Show Data to user if the record was found.


                if ($exist > 0) {

                    $stmt = $con->prepare("DELETE FROM categories WHERE ID = :cat_id");

                    $stmt->bindParam(":cat_id", $cat_id);

                    $stmt->execute();

                    echo "<div class='container'>";

                        $deletedCat =  "<div class='alert alert-success'>" . $stmt->rowCount() . ' Category Deleted</div>';

                        redirectBack($deletedCat, $url='back');

                    echo "</div>";
                }

            echo "</div>";

		}

		include $tmps . 'footer.php';

	} else {

		header('Location: index.php');

		exit();
	}

?>