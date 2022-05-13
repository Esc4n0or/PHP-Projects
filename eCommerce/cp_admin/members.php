<?php

    /* 

    ||=> Here You can Mange Members 
    ||=> You can Add / Update / Delete Members.

    */ 
    session_start(); // Start session; 

    if (isset($_SESSION['ADMIN'])){

        include 'init.php';
        
        $action = isset($_GET['action']) ? $_GET['action'] : 'manage';

        if ($action == 'manage') { // Manage member page 

            $query = '';

            if (isset($_GET['page']) && $_GET['page'] == 'pending') {

                $query = 'AND reg_status = 0';

            }
            // Select All Users Except Admin 

			$stmt = $con->prepare("SELECT * FROM users WHERE group_id != 1 $query ORDER BY user_id DESC");

			// Execute The Statement

			$stmt->execute();

			// Assign To Variable 

			$rows = $stmt->fetchAll();

        ?> 
            <h1 class="text-center">Manage Member</h1>
            <div class="container">
                <div class="table-responsive">
                    <table class="main-table manage-members text-center table table-bordered">
                        <tr>
                            <td>iD</td>
                            <td>Avatar</td>
                            <td>Username</td>
                            <td>Email</td>
                            <td>Full-Name</td>
                            <td>Registered Date</td>
                            <td>Control</td>
                        </tr>
                        <?php
							foreach($rows as $row) {
								echo "<tr>";
									echo "<td>" . $row['user_id'] . "</td>";
                                    echo "<td>";
									if (empty($row['avatar'])) {
										echo 'No Image';
									} else {
										echo "<img src='uploads/avatars/" . $row['avatar'] . "' alt='' />";
									}
									echo "</td>";
									echo "<td>" . $row['username'] . "</td>";
									echo "<td>" . $row['email'] . "</td>";
									echo "<td>" . $row['full_name'] . "</td>";
                                    echo "<td>" . $row['Date'] . "</td>";
                                    echo "<td>
										<a href='members.php?action=edit-profile&id=" . $row['user_id'] . "' class='btn btn-info'><i class='fa fa-edit'></i> Edit</a>
										<a href='members.php?action=delete&id=" . $row['user_id'] . "' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete </a>";

                                        if ($row['reg_status'] == 0) {

                                            echo "<a href='members.php?action=activate&id=" . $row['user_id'] . "'
                                            class='btn btn-success activate'><i class='fa fa-check'></i> Activate </a>";
                                        }

									echo "</td>";
								echo "</tr>";
							}
						?>
                        <tr>
                    </table>
                </div>
                <a href='?action=add' class="btn btn-primary"><i class="fa fa-plus"></i>Add Member</a>




       <?php
         } elseif ($action == 'add') { // Add New member page ?>
            <h1 class="text-center">Add New Member</h1>
                    <div class="container">
                        <form class="form-horizontal" action="?action=insert" method="POST" enctype="multipart/form-data">
                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-label">Username</label>
                                <div class="col-sm-10 col-md-4">
                                    <input type="text" class="form-control" name="username" autocomplete="off" required>
                                </div>
                            </div>
                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-label">Password</label>
                                <div class="col-sm-10 col-md-4">
                                    <input type="password" class="password form-control" name="password" autocomplete="new-password"  required>
                                    <i class="show-pass fa fa-eye fa-2x"></i>
                                </div>
                            </div>

                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-label">Email</label>
                                <div class="col-sm-10 col-md-4">
                                    <input type="email" class="form-control" name="email" autocomplete="off"  required>
                                </div>
                            </div>

                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-label">Full-name</label>
                                <div class="col-sm-10 col-md-4">
                                    <input type="text" class="form-control" name="full-name"  required>
                                </div>
                            </div>

                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-label">Profile Picture</label>
                                <div class="col-sm-10 col-md-4">
                                    <input type="file" class="form-control" name="avatar" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <input type="submit" class="btn btn-primary btn-lg" value="Add Member">
                                </div>
                            </div>   
                        </form>

                    </div>
    <?php  }

        elseif($action == 'insert') {
            
            if ($_SERVER['REQUEST_METHOD'] == 'POST'){

                echo "<h1 class='text-center'>Insert Profile</h1>";
                echo "<div class='container'>";

                
				$avatarName = $_FILES['avatar']['name'];
				$avatarSize = $_FILES['avatar']['size'];
				$avatarTmp	= $_FILES['avatar']['tmp_name'];
				$avatarType = $_FILES['avatar']['type'];

                // List Of Allowed File Typed To Upload

				$avatarAllowedExtension = array("jpeg", "jpg", "png");

                // Get Avatar Extension

				$avatarExtension = pathinfo($avatarName, PATHINFO_EXTENSION);

                // Get Values to insert/Add them to the database.

                $name   = $_POST['username'];
                $pass   = $_POST['password'];
                $email  = $_POST['email'];
                $f_name = $_POST['full-name'];

                $hash_pass = sha1($_POST['password']);

                $formErrors = array();

                if (empty($name)) {
                    $formErrors[] = "Username field can't be empty";
                }
                if ($name > 20 && $name < 5) {
                    $formErrors[] = "Username field Must be More than 5 char and less than 20";
                }
                if (empty($email)) {
                    $formErrors[] = "Email field can't be empty";
                }

                if (empty($f_name)) {
                    $formErrors[] = "Full_Name field can't be empty";
                }

                if (! empty($avatarName) && ! in_array($avatarExtension, $avatarAllowedExtension)) {
					$formErrors[] = 'This Extension Is Not <strong>Allowed</strong>';
				}

                if (empty($avatarName)) {
					$formErrors[] = 'Avatar Is <strong>Required</strong>';
				}

                if ($avatarSize > 4194304) {
					$formErrors[] = 'Avatar Cant Be Larger Than <strong>4MB</strong>';
				}

                foreach($formErrors as $error) {
                    echo "<div class='alert alert-danger'>" . $error . "</div>";
                }

                if (empty($formErrors)) {


                    $avatar = rand(0, 10000000000000) . '_' . $avatarName;

                    move_uploaded_file($avatarTmp, "uploads\avatars\\" . $avatar);
                    // Check if the user exist or not.
                    
                    $exist = checkExist("username", "users", $name);

                    if ($exist == 1) {

                        echo "<div class='container'>";

                        $faildAddUser =  "<div class='alert alert-danger'>Faild to add User.</div>";
                        
                        redirectBack($faildAddUser, $url='back');
                        echo "</div>";
                    } else {

                        $stmt = $con->prepare("INSERT INTO

                                            users(username, password, email, full_name, reg_status,  Date, avatar)

                                            VALUES(:user, :pass, :email, :fname, 1 ,now(), :avatar)" ); // You can choose any names in VALUES field.

                    $stmt->execute(array('user' => $name,'pass' => $hash_pass,'email' => $email,'fname' => $f_name, 'avatar' => $avatar));

                    echo "<div class='container'>";

                        $addedUser = "<div class='alert alert-success'>" . $stmt->rowCount() . " Record Inserted in the Database" . "</div>";
                        redirectBack($addedUser, $url='back');

                    echo "</div>";
                        }
                
                 }
                 
                    

                 
            } else {

                echo "<div class='container'>";

                $errorMg =  "<div class='alert alert-danger'>You can't browser this Page Directly</div>";
                redirectBack($errorMg);

                echo "</div>";
            }
         echo "</div>";   

        }
        
        elseif ($action == 'edit-profile') { // Edit user page. 

            $userid = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0; 

            $stmt = $con->prepare("SELECT * FROM users WHERE user_id = ? LIMIT 1"); // Select All info about the user due to his ID
            $stmt->execute(array($userid));
            $row = $stmt->fetch(); // Fetch the data
            $record = $stmt->rowCount(); // Check if the info exist in the DB or not.


            //  Show Data to user if the record was found.


            if ($record > 0) { ?>
                <h1 class="text-center">Edit Profile</h1>
                    <div class="container">
                        <form class="form-horizontal" action="?action=update" method="POST">
                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-label">Username</label>
                                <div class="col-sm-10 col-md-4">
                                    <input type="hidden" name="userid" value="<?php echo $userid ?>" />
                                    <input type="text" class="form-control" name="username" value="<?php echo $row['username'] ?>" autocomplete="off" required>
                                </div>
                            </div>
                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-label">Password</label>
                                <div class="col-sm-10 col-md-4">
                                    <input type="hidden" class="form-control" name="old_password" value="<?php echo $row['password'] ?>">
                                    <input type="password" class="form-control" name="new_password" autocomplete="new-password">
                                </div>
                            </div>
                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-label">Email</label>
                                <div class="col-sm-10 col-md-4">
                                    <input type="email" class="form-control" value="<?php echo $row['email'] ?>" name="email" autocomplete="off" required>
                                </div>
                            </div>
                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-label">Full-name</label>
                                <div class="col-sm-10 col-md-4">
                                    <input type="text" class="form-control" value="<?php echo $row['full_name'] ?>" name="full-name">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <input type="submit" class="btn btn-primary btn-lg" value="Save">
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

           // Update Page        
        } elseif ($action == 'update') {
            echo "<h1 class='text-center'>Update Profile</h1>";
            echo "<div class='container'>";
            if ($_SERVER['REQUEST_METHOD'] == 'POST'){

                // Get Updated Values to insert them to the database.
                $id = $_POST['userid'];
                $name = $_POST['username'];
                $email = $_POST['email'];
                $f_name = $_POST['full-name'];
                $pass = empty($_POST['new_password']) ? $_POST['old_password'] : sha1($_POST['new_password']) ; 

                $formErrors = array();

                if (empty($name)) {
                    $formErrors[] = "Username field can't be empty";
                }

                if (empty($email)) {
                    $formErrors[] = "Email field can't be empty";
                }

                if (empty($f_name)) {
                    $formErrors[] = "Full_Name field can't be empty";
                }

                foreach($formErrors as $error) {
                    echo "<div class='alert alert-danger'>" . $error . "</div>";
                }

                if (empty($formErrors)) {

                    $stmt1 = $con->prepare("SELECT * FROM users WHERE username = ? AND user_id != ?");
                    $stmt1->execute(array($name, $id));
                    $count = $stmt1->rowCount();

                    if ($count == 1) {

                        $duplicate = '<div class="alert alert-danger">Sorry This User Is Exist</div>';
						redirectBack($duplicate, 'back');

                    } else {

                        $stmt2 = $con->prepare("UPDATE users SET username = ?, email = ?, full_name = ?, password = ? WHERE user_id = ?");
                        $stmt2->execute(array($name, $email, $f_name,$pass, $id));

                        echo "<div class='container'>";

                            $updatedUser = "<div class='alert alert-success'>" . $stmt2->rowCount() . " Record Updated in the Database" . "</div>";
                            redirectBack($updatedUser, $url='back');

                        echo "</div>";
                    }

                    
                }

                 
            } else {

                echo "<div class='container'>";

                    $errorMsg =  "<div class='alert alert-danger'>You can't browser this Page Directly</div>";
                    redirectBack($errorMsg);

                echo "</div>";
            }
            echo "</div>";   

        } elseif ($action == 'delete') { // Delete Page.

            echo "<h1 class='text-center'>Delete Profile</h1>";
            echo "<div class='container'>";

                $userid = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0; // Check User ID 

                $exist = checkExist("user_id", "users", $userid);

                //  Show Data to user if the record was found.


                if ($exist > 0) {

                    $stmt = $con->prepare("DELETE FROM users WHERE user_id = :user_id");

                    $stmt->bindParam(":user_id", $userid);

                    $stmt->execute();

                    echo "<div class='container'>";

                        $deletedUser =  "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Deleted</div>';
                        redirectBack($deletedUser, $url='back');

                    echo "</div>";
                }

            echo "</div>";
     } elseif ($action= 'activate') {

            echo "<h1 class='text-center'>Activate Profile</h1>";
            echo "<div class='container'>";

                $userid = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0; // Check User ID 

                $exist = checkExist("user_id", "users", $userid);

                //  Show Data to user if the record was found.


                if ($exist > 0) {

                    $stmt = $con->prepare("UPDATE users SET reg_status = 1 WHERE user_id = ?");

                    $stmt->execute(array($userid));

                    echo "<div class='container'>";

                        $accUser =  "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Activated</div>';
                        redirectBack($accUser, $url='back');

                    echo "</div>";
                }

            echo "</div>";


     }

        else {

            echo "<div class='container'>";

                $NotFound =  "<div class='alert alert-danger'>ERORR 404</div>";
                redirectBack($NotFound, $url='back');

            echo "</div>";
    }
    
    

        include $tmps. "footer.php";

    } else {

        header('Location: index.php');

        exit();
    }