<?php

    /* 

    ||=> Here You can Mange Comments 
    ||=> You can Update / Delete Comments.

    */ 
    session_start(); // Start session; 

    if (isset($_SESSION['ADMIN'])){

        include 'init.php';
        
        $action = isset($_GET['action']) ? $_GET['action'] : 'manage';

        if ($action == 'manage') { // Manage Comment page 
            
            // Select All Comments Except Admin 

			$stmt = $con->prepare("SELECT 
                                        comments.*, items.name AS item_Name, users.username AS Member
                                    FROM 
                                        comments
                                    INNER JOIN 
                                        items ON items.item_id = comments.item_id
                                    INNER JOIN 
                                        users ON users.user_id = comments.user_id
                                    ORDER BY 
                                        comm_id DESC");

			// Execute The Statement

			$stmt->execute();

			// Assign To Variable 

			$comms = $stmt->fetchAll();

            if(!empty($comms)) {
                ?> 
                <h1 class="text-center">Manage Comments</h1>
                <div class="container">
                    <div class="table-responsive">
                        <table class="main-table text-center table table-bordered">
                            <tr>
                                <td>iD</td>
                                <td>Comment</td>
                                <td>Item Name</td>
                                <td>Member Name</td>
                                <td>Date</td>
                                <td>Control</td>
                            </tr>
                            <?php
                                foreach($comms as $comm) {
                                    echo "<tr>";
                                        echo "<td>" . $comm['comm_id'] . "</td>";
                                        echo "<td>" . $comm['comment'] . "</td>";
                                        echo "<td>" . $comm['item_Name'] . "</td>";
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
                    </div>
                    <?php }  else {
                echo '<div class="container">';
                echo '<div class="alert alert-info">There\'s No comment To Show</div>';
                    '</a>';
            echo '</div>';
            }
            

        




            ?> 
        <?php 
        } elseif ($action == 'edit-comm') { // Edit user page. 

            $comID = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0; 

            $stmt = $con->prepare("SELECT * FROM comments WHERE comm_id = ?"); // Select All info about the user due to his ID
            $stmt->execute(array($comID));
            $row = $stmt->fetch(); // Fetch the data
            $record = $stmt->rowCount(); // Check if the info exist in the DB or not.


            //  Show Data to user if the record was found.


            if ($record > 0) { ?>
                <h1 class="text-center">Edit Comment</h1>
                    <div class="container">
                        <form class="form-horizontal" action="?action=update" method="POST">
                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-label">Comment</label>
                                <div class="col-sm-10 col-md-4">
                                    <input type="hidden" name="commID" value="<?php echo $comID ?>" />
                                    <textarea class="form-control" name="comment"><?php echo $row['comment'] ?></textarea>
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
            echo "<h1 class='text-center'>Update Comment</h1>";
            echo "<div class='container'>";
            if ($_SERVER['REQUEST_METHOD'] == 'POST'){

                // Get Updated Values to insert them to the database.
                $id = $_POST['commID'];
                $data = $_POST['comment'];

                $formErrors = array();

                if (empty($data)) {

                    echo "<div class='alert alert-danger'>Comment Area field can't be empty</div>";

                } else {

                    $stmt = $con->prepare("UPDATE comments SET comment = ? WHERE comm_id = ?");
                    $stmt->execute(array($data,$id));

                    echo "<div class='container'>";

                    $updatedCom = "<div class='alert alert-success'>" . $stmt->rowCount() . " Comment Updated in the Database" . "</div>";
                    redirectBack($updatedCom, $url='back');

                    echo "</div>";
                }
                 
            } else {

                echo "<div class='container'>";

                $errorMsg =  "<div class='alert alert-danger'>You can't browser this Page Directly</div>";
                redirectBack($errorMsg);

                echo "</div>";
            }
            echo "</div>";   

        } elseif ($action == 'delete') { // Delete Page.

            echo "<h1 class='text-center'>Delete Comment</h1>";
            echo "<div class='container'>";

                $comID = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0; 

                $exist = checkExist("comm_id", "comments", $comID);

                //  Show Data to Comment if the record was found.


                if ($exist > 0) {

                    $stmt = $con->prepare("DELETE FROM comments WHERE comm_id = :com_id");

                    $stmt->bindParam(":com_id", $comID);

                    $stmt->execute();

                    echo "<div class='container'>";

                        $deletedCom =  "<div class='alert alert-success'>" . $stmt->rowCount() . ' Comment Deleted</div>';
                        redirectBack($deletedCom, $url='back');

                    echo "</div>";
                }

            echo "</div>";
     } elseif ($action= 'approve') {

            echo "<h1 class='text-center'>Approve Comment</h1>";
            echo "<div class='container'>";

                $comID = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0; 

                $exist = checkExist("comm_id", "comments", $comID);

                //  Show Data to user if the record was found.


                if ($exist > 0) {

                    $stmt = $con->prepare("UPDATE comments SET approve = 1 WHERE comm_id = :com_id");

                    $stmt->bindParam(":com_id", $comID);

                    $stmt->execute();

                    echo "<div class='container'>";

                        $accUser =  "<div class='alert alert-success'>" . $stmt->rowCount() . ' Comment Approved</div>';
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