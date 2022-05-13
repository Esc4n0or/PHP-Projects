<?php



	session_start();


	if (isset($_SESSION['ADMIN'])) {

		include 'init.php';

		$action = isset($_GET['action']) ? $_GET['action'] : 'manage';

		if ($action == 'manage') {

		    $stmt = $con->prepare("SELECT 
                    
                                        items.*, categories.Name as Category_Name, users.username
                                    FROM 
                                        items
                                    INNER JOIN 
                                        categories ON categories.ID = items.cat_id
                                    INNER JOIN 
                                        users ON users.user_id = items.member_id
                                    ORDER BY 
                                        item_id  DESC    
                                        ");

			// Execute The Statement

			$stmt->execute();

			// Assign To Variable 

			$items = $stmt->fetchAll();

            if(!empty($items)) {
                ?> 
            <h1 class="text-center">Manage Item</h1>
            <div class="container">
                <div class="table-responsive">
                    <table class="main-table text-center table table-bordered">
                        <tr>
                            <td>iD</td>
                            <td>Name</td>
                            <td>Description</td>
                            <td>Price</td>
                            <td>Date</td>
                            <td>Category</td>
                            <td>Member</td>
                            <td>Control</td>
                        </tr>
                        <?php
							foreach($items as $item) {
								echo "<tr>";
									echo "<td>" . $item['item_id'] . "</td>";
									echo "<td>" . $item['name'] . "</td>";
									echo "<td>" . $item['description'] . "</td>";
									echo "<td>" . $item['price'] . "</td>";
                                    echo "<td>" . $item['date'] . "</td>";
                                    echo "<td>" . $item['Category_Name'] . "</td>";
                                    echo "<td>" . $item['username'] . "</td>";
                                    echo "<td>
										<a href='items.php?action=edit&item_id=" . $item['item_id'] . "' class='btn btn-info'><i class='fa fa-edit'></i> Edit</a>
										<a href='items.php?action=delete&item_id=" . $item['item_id'] . "' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete </a>";
                                        
                                        if ($item['approve'] == 0) {

                                            echo "<a href='items.php?action=approve&item_id=" . $item['item_id'] . "' class='btn btn-success activate'><i class='fa fa-check'></i> Approve </a>";
                                        }
									echo "</td>";
								echo "</tr>";
							}
						?>
                        <tr>
                    </table>
                </div>
                <a href='?action=add' class="btn btn-primary"><i class="fa fa-plus"></i>Add Item</a>




       <?php
            } else {
                echo '<div class="container">';
					echo '<div class="alert alert-info">There\'s No Items To Show</div>';
					echo '<a href="items.php?action=add" class="btn btn-sm btn-primary">
							<i class="fa fa-plus"></i> New Item
						</a>';
				echo '</div>';
            } ?>

        

        

		 <?php } elseif ($action == 'add') { ?>


            <h1 class="text-center">Add New Item</h1>
            <div class="container">
                <form class="form-horizontal" action="?action=insert" method="POST">
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label" style="color:white;">Name: </label>
                        <div class="col-sm-10 col-md-4">
                            <input type="text" class="form-control" name="name" required placeholder="Name of the Item">
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label" style="color:white;">Description: </label>
                        <div class="col-sm-10 col-md-4">
                            <input type="text" class="form-control" name="description" required placeholder="Description of the Item">
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label" style="color:white;">Price: </label>
                        <div class="col-sm-10 col-md-4">
                            <input type="text" class="form-control" name="price" required placeholder="Price of the Item">
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label" style="color:white;">Country: </label>
                        <div class="col-sm-10 col-md-4">
                            <input type="text" class="form-control" name="made" autocomplete="off" required placeholder="Country of the Item">
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label" style="color:white;">Rating: </label>
                        <div class="col-sm-10 col-md-4">
                            <select name="status">
                                <option value="0">....</option>
                                <option value="1">New</option>
                                <option value="2">Used</option>
                                <option value="3">Old</option>
                                <option value="4">Very old</option>
                            </select>
                        </div>
                    </div>


                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label" style="color:white;">Member: </label>
                        <div class="col-sm-10 col-md-4">
                            <select name="member">
                                <option value="0">....</option>
                                <?php   

                                    $stmt = $con->prepare("SELECT * FROM users");
                                    $stmt->execute();
                                    $users = $stmt->fetchAll();

                                    foreach($users as $user){

                                        echo "<option value='" . $user['user_id'] . "'>" . $user['username'] . "</option>";

                                    }
                                ?>

                            </select>
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label" style="color:white;">Categories: </label>
                        <div class="col-sm-10 col-md-4">
                            <select name="category">
                                <option value="0">....</option>
                                <?php   

                                    $stmt2 = $con->prepare("SELECT * FROM categories");
                                    $stmt2->execute();
                                    $cats = $stmt2->fetchAll();

                                    foreach($cats as $cat){

                                        echo "<option value='" . $cat['ID'] . "'>" . $cat['Name'] . "</option>";

                                    }
                                ?>

                            </select>
                        </div>
                    </div>
                    <br>

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" class="btn btn-primary btn-sm" value="Add Item">
                        </div>
                    </div>   
                </form>
            </div>
		<?php } elseif ($action == 'insert') {

                if ($_SERVER['REQUEST_METHOD'] == 'POST'){

                    echo "<h1 class='text-center'>Insert Item</h1>";
                    echo "<div class='container'>";

                    // Get Values to insert/Add them to the database.

                    $name   = $_POST['name'];
                    $desc   = $_POST['description'];
                    $price  = $_POST['price'];
                    $country = $_POST['made'];
                    $status  = $_POST['status'];
                    $member  = $_POST['member'];
                    $category  = $_POST['category'];


                    $formErrors = array();

                    if (empty($name)) {
                        $formErrors[] = "Name field can't be empty";
                    }
                    if (empty($desc)) {
                        $formErrors[] = "Description field can't be empty";
                    }
                    if (empty($price)) {
                        $formErrors[] = "Price field can't be empty";
                    }
                    if (empty($country)) {
                        $formErrors[] = "Country field can't be empty";
                    }
                    if ($status === 0 ) {
                        $formErrors[] = "You have to choose status";
                    }
                    if ($member === 0 ) {
                        $formErrors[] = "You have to choose specific Member";
                    }
                    if ($category === 0 ) {
                        $formErrors[] = "You have to choose specific Category";
                    }

                    foreach($formErrors as $error) {

                        echo "<div class='alert alert-danger'>" . $error . "</div>";
                        
                    }

                    if (empty($formErrors)) {

                            $stmt = $con->prepare("INSERT INTO

                                                items(name, description, price, country_made, status, date, member_id, cat_id)

                                                VALUES(:Pname, :Pdescription, :Pprice, :Pmade, :Pstatus, now(), :Pmember, :Pcat )" ); // You can choose any names in VALUES field.

                        $stmt->execute(array('Pname' => $name,
                                             'Pdescription' => $desc,
                                             'Pprice' => $price,
                                             'Pmade' => $country,
                                             'Pstatus' => $status,
                                             'Pmember' => $member,
                                             'Pcat' => $category));

                        echo "<div class='container'>";

                        $addedUser = "<div class='alert alert-success'>" . $stmt->rowCount() . " Item Inserted in the Database" . "</div>";
                        redirectBack($addedUser, $url='back');

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

            $item_id = isset($_GET['item_id']) && is_numeric($_GET['item_id']) ? intval($_GET['item_id']) : 0; 

            $stmt = $con->prepare("SELECT * FROM items WHERE item_id  = ?"); // Select All info about the user due to his ID
            $stmt->execute(array($item_id));
            $item = $stmt->fetch(); // Fetch the data
            $record = $stmt->rowCount(); // Check if the info exist in the DB or not.


            //  Show Data to user if the record was found.


            if ($record > 0) { ?>
                
                <h1 class="text-center">Edit Item</h1>
                <div class="container">
                    <form class="form-horizontal" action="?action=update" method="POST">
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label" style="color:white;">Name: </label>
                            <div class="col-sm-10 col-md-4">
                                <input type="hidden" name="itemID" value="<?php echo $item_id ?>" />
                                <input type="text" class="form-control" name="name" required value="<?php echo $item['name'] ?>">
                            </div>
                        </div>

                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label" style="color:white;">Description: </label>
                            <div class="col-sm-10 col-md-4">
                                <input type="text" class="form-control" name="description" required value="<?php echo $item['description'] ?>">
                            </div>
                        </div>

                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label" style="color:white;">Price: </label>
                            <div class="col-sm-10 col-md-4">
                                <input type="text" class="form-control" name="price" required value="<?php echo $item['price'] ?>">
                            </div>
                        </div>

                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label" style="color:white;">Country: </label>
                            <div class="col-sm-10 col-md-4">
                                <input type="text" class="form-control" name="country" autocomplete="off" required value="<?php echo $item['country_made'] ?>">
                            </div>
                        </div>

                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label" style="color:white;">Rating: </label>
                            <div class="col-sm-10 col-md-4">
                                <select name="status">
                                    <option value="1" <?php if ($item['status'] == 1) {echo 'selected';} ?>>New</option>
                                    <option value="2" <?php if ($item['status'] == 2) {echo 'selected';} ?>>Used</option>
                                    <option value="3" <?php if ($item['status'] == 3) {echo 'selected';} ?>>Old</option>
                                    <option value="4" <?php if ($item['status'] == 4) {echo 'selected';} ?>>Very old</option>
                                </select>
                            </div>
                        </div>


                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label" style="color:white;">Member: </label>
                            <div class="col-sm-10 col-md-4">
                                <select name="member">
                                    <?php   

                                        $stmt = $con->prepare("SELECT * FROM users");
                                        $stmt->execute();
                                        $users = $stmt->fetchAll();

                                        foreach($users as $user){

                                            echo "<option value='" . $user['user_id'] . "'";
                                            if ($item['member_id'] == $user['user_id']) {echo 'selected';}
                                            echo ">" . $user['username'] . "</option>";

                                        }
                                    ?>

                                </select>
                            </div>
                        </div>

                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label" style="color:white;">Categories: </label>
                            <div class="col-sm-10 col-md-4">
                                <select name="category">
                                    <?php   

                                        $stmt2 = $con->prepare("SELECT * FROM categories");
                                        $stmt2->execute();
                                        $cats = $stmt2->fetchAll();

                                        foreach($cats as $cat){

                                            echo "<option value='" . $cat['ID'] . "'";
                                            if ($item['cat_id'] == $cat['ID']) {echo 'selected';}
                                            echo ">" . $cat['Name'] . "</option>";

                                        }
                                    ?>

                                </select>
                            </div>
                        </div>
                        <br>

                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <input type="submit" class="btn btn-primary btn-sm" value="Save Item">
                            </div>
                        </div>   
                    </form>
                            <?php 

                            // Select All Comments Except Admin 

                            $stmt = $con->prepare("SELECT 
                                                    comments.*, users.username AS Member
                                                FROM 
                                                    comments
                                                INNER JOIN 
                                                    users ON users.user_id = comments.user_id
                                                WHERE 
                                                    item_id = ?");

                        // Execute The Statement

                        $stmt->execute(array($item_id));

                        // Assign To Variable 

                        $comms = $stmt->fetchAll();

                        if (!empty($comms)) {

                            ?> 
                                <h1 class="text-center">Manage  {<?php echo $item['name'] ?>} Comments</h1>
                                    <div class="table-responsive">
                                        <table class="main-table text-center table table-bordered">
                                            <tr>
                                                <td>Comment</td>
                                                <td>Member Name</td>
                                                <td>Date</td>
                                                <td>Control</td>
                                            </tr>
                                            <?php
                                                foreach($comms as $comm) {
                                                    echo "<tr>";
                                                        echo "<td>" . $comm['comment'] . "</td>";
                                                        echo "<td>" . $comm['Member'] . "</td>";
                                                        echo "<td>" . $comm['comm_date'] . "</td>";
                                                        echo "<td>
                                                            <a href='comments.php?action=edit-comm&comid=" . $comm['comm_id'] . "' class='btn btn-info'><i class='fa fa-edit'></i> Edit</a>
                                                            <a href='comments.php?action=delete&comid=" . $comm['comm_id'] . "' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete </a>";

                                                            if ($comm['status'] == 0) {

                                                                echo "<a href='comments.php?action=activate&id=" . $comm['comm_id'] . "'
                                                                class='btn btn-success activate'><i class='fa fa-check'></i> Approve </a>";
                                                            }

                                                        echo "</td>";
                                                    echo "</tr>";
                                                }
                                            ?>
                                            <tr>
                                        </table>
                            <?php } ?>
                        </div>



        <?php 
        }  else {

                echo "<div class='container'>";

                $notFoundID =  "<div class='alert alert-danger'>There is no Such ID with this Number</div>";
                redirectBack($notFoundID);

                echo "</div>";
                   }

		} elseif ($action == 'update') {

            echo "<h1 class='text-center'>Update item</h1>";
            echo "<div class='container'>";
            if ($_SERVER['REQUEST_METHOD'] == 'POST'){

                // Get Updated Values to insert them to the database.
                $id = $_POST['itemID'];
                $name = $_POST['name'];
                $desc = $_POST['description'];
                $price = $_POST['price'];
                $country = $_POST['country'];
                $rate = $_POST['status'];
                $category = $_POST['category'];
                $member = $_POST['member'];
                

                

                $formErrors = array();

                if (empty($name)) {
                    $formErrors[] = "Username field can't be empty";
                }

                if (empty($desc)) {
                    $formErrors[] = "Description field can't be empty";
                }

                if (empty($price)) {
                    $formErrors[] = "Price field can't be empty";
                }
                if (empty($country)) {
                    $formErrors[] = "Country_made field can't be empty";
                }
                if ($rate === 0 ) {
                    $formErrors[] = "You have to choose status";
                }
                if ($member === 0 ) {
                    $formErrors[] = "You have to choose specific Member";
                }
                if ($category === 0 ) {
                    $formErrors[] = "You have to choose specific Category";
                }

                foreach($formErrors as $error) {
                    echo "<div class='alert alert-danger'>" . $error . "</div>";
                }

                if (empty($formErrors)) {

                    $stmt = $con->prepare("UPDATE
                                             items
                                            SET 
                                                name = ?, description = ?, price = ?, country_made = ?, status = ?, cat_id = ?, member_id = ? WHERE item_id  = ?");
                    $stmt->execute(array($name, $desc, $price, $country, $rate, $category, $member, $id));

                    echo "<div class='container'>";

                    $updatedUser = "<div class='alert alert-success'>" . $stmt->rowCount() . " Item Updated in the Database" . "</div>";
                    redirectBack($updatedUser, $url='back');

                    echo "</div>";
                }

                 
            } else {

                echo "<div class='container'>";

                $errorMsg =  "<div class='alert alert-danger'>You can't browser this Page Directly</div>";
                redirectBack($errorMsg);

                echo "</div>";
            }
            echo "</div>";  

		} elseif ($action == 'delete') {

            echo "<h1 class='text-center'>Delete Item</h1>";
            echo "<div class='container'>";

                $itemId = isset($_GET['item_id']) && is_numeric($_GET['item_id']) ? intval($_GET['item_id']) : 0; // Check User ID 

                $exist = checkExist("item_id ", "items", $itemId);

                //  Show Data to user if the record was found.


                if ($exist > 0) {

                    $stmt = $con->prepare("DELETE FROM items WHERE item_id = :item_id");

                    $stmt->bindParam(":item_id", $itemId);

                    $stmt->execute();

                    echo "<div class='container'>";

                    $deletedItem =  "<div class='alert alert-success'>" . $stmt->rowCount() . ' Item Deleted</div>';
                    redirectBack($deletedItem, $url='back');

                    echo "</div>";
                }

            echo "</div>";

		} elseif ($action == 'approve') {

            echo "<h1 class='text-center'>Approve Item</h1>";
            echo "<div class='container'>";

                $itemId = isset($_GET['item_id']) && is_numeric($_GET['item_id']) ? intval($_GET['item_id']) : 0; // Check User ID 

                $exist = checkExist("item_id", "items", $itemId);

                //  Show Data to user if the record was found.


                if ($exist > 0) {

                    $stmt = $con->prepare("UPDATE items SET approve = 1 WHERE item_id = ?");

                    $stmt->execute(array($itemId));

                    echo "<div class='container'>";

                    $ApproveItem =  "<div class='alert alert-success'>" . $stmt->rowCount() . ' Item Approved</div>';

                    redirectBack($ApproveItem, $url='back');

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