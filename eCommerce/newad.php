<?php
    session_start();
    include "init.php"; 

    if(isset($_SESSION['user'])) { 

    
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $formErrors = array();

            $title              = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
            $desc               = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
            $price              = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_INT);
            $country            = filter_var($_POST['made'], FILTER_SANITIZE_STRING);
            $status             = filter_var($_POST['status'], FILTER_SANITIZE_NUMBER_INT);
            $category           = filter_var($_POST['category'], FILTER_SANITIZE_NUMBER_INT);



            if (strlen($title) < 4) {

				$formErrors[] = 'Item Title Must Be At Least 4 Characters';

			}

			if (strlen($desc) < 10) {

				$formErrors[] = 'Item Description Must Be At Least 10 Characters';

			}

			if (strlen($country) < 2) {

				$formErrors[] = 'Item Title Must Be At Least 2 Characters';

			}

			if (empty($price)) {

				$formErrors[] = 'Item Price Cant Be Empty';

			}

			if (empty($status)) {

				$formErrors[] = 'Item Status Cant Be Empty';

			}

			if (empty($category)) {

				$formErrors[] = 'Item Category Cant Be Empty';

			}

            if (empty($formErrors)) {

                $stmt = $con->prepare("INSERT INTO

                                    items(name, description, price, country_made, status, date, member_id, cat_id)

                                    VALUES(:Pname, :Pdescription, :Pprice, :Pmade, :Pstatus, now(), :Pmember, :Pcat )" ); // You can choose any names in VALUES field.

            $stmt->execute(array('Pname' => $title,
                                 'Pdescription' => $desc,
                                 'Pprice' => $price,
                                 'Pmade' => $country,
                                 'Pstatus' => $status,
                                 'Pmember' => $_SESSION['uid'],
                                 'Pcat' => $category));

            if ($stmt) {
               $done = "Item added";
            }
         } else {

            echo "<div class='container'>";
    
                $errorMg =  "<div class='alert alert-danger'>You can't browser this Page Directly</div>";
                redirectBack($errorMg);
    
            echo "</div>";
                }   
        
        
    }
            

        ?>
        <!--  -->  
        <h1 class="text-center">Create New Item</h1>
        <div class="create-ad block">
            <div class="container">
                <div class="card border-primary mb-3">
                    <h4 class="card-header"><i class="fa fa-edit"></i> Create New Item</h4>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                        <form class="form-horizontal" action="?action=insert" method="POST">
                                            <div class="form-group form-group-lg">
                                                <label class="col-sm-2 control-label" style="color:white;">Name: </label>
                                                <div class="col-sm-10 col-md-8">
                                                    <input pattern=".{4,}" title="Name must be 4 charcters or more" type="text" class="form-control live-name" name="name" required placeholder="Name of the Item">
                                                </div>
                                            </div>

                                            <div class="form-group form-group-lg">
                                                <label class="col-sm-2 control-label" style="color:white;">Description: </label>
                                                <div class="col-sm-10 col-md-8">
                                                    <input pattern=".{10,}" title="Description must be 10 charcters or more" type="text" class="form-control live-desc" name="description" required placeholder="Description of the Item">
                                                </div>
                                            </div>

                                            <div class="form-group form-group-lg">
                                                <label class="col-sm-2 control-label" style="color:white;">Price: </label>
                                                <div class="col-sm-10 col-md-8">
                                                    <input type="text" class="form-control live-price" name="price" required placeholder="Price of the Item">
                                                </div>
                                            </div>

                                            <div class="form-group form-group-lg">
                                                <label class="col-sm-2 control-label" style="color:white;">Country: </label>
                                                <div class="col-sm-10 col-md-8">
                                                    <input type="text" class="form-control" name="made" autocomplete="off" required placeholder="Country of the Item">
                                                </div>
                                            </div>

                                            <div class="form-group form-group-lg">
                                                <label class="col-sm-2 control-label" style="color:white;">Rating: </label>
                                                <div class="col-sm-10 col-md-8">
                                                    <select name="status" required>
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
                                                <div class="col-sm-10 col-md-8">
                                                    <select name="member" required>
                                                        <option value="0">....</option>
                                                        <?php   
                                                            $users = getAll('users', 'user_id');

                                                            foreach($users as $user){

                                                                echo "<option value='" . $user['user_id'] . "'>" . $user['username'] . "</option>";

                                                            }
                                                        ?>

                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group form-group-lg">
                                                <label class="col-sm-2 control-label" style="color:white;">Categories: </label>
                                                <div class="col-sm-10 col-md-8">
                                                    <select name="category">
                                                        <option value="0">....</option>
                                                        <?php  

                                                            $cats = getAll('categories', 'ID');

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

                            <div class="col-md-4">
                                <div class="card item-box live-preview">
                                <span class="price-tag">
                                    $0
                                </span>
                                <img class="img-fluid" src="https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_640.png" alt="" />
                                <div class="caption">
                                    <h3 class="live-title">Title</h3>
                                    <p class="live-desc">Description</p>
                                </div>
                                </div>
                            </div>
                        </div>
                        <!-- Start Errors Area -->
                         <?php 
                            if(!empty($formErrors)) {
                                foreach($formErrors as $error) {
                                    echo "<div class='alert alert-danger'>" . $error . "</div>";
                                }
                            } 
                            if(isset($done)){
                                echo "<div class='alert alert-success'>" . $done . "</div>";
                            }                                  
                        ?>
                        <!-- End Errors Area -->
                    </div>
                </div>
            </div>
        </div> 

        <?php } else {
            header('Location: login.php');
            exit();
}



include $tmps. "footer.php";?>