<?php
    session_start();
    include "init.php";    

    $item_id = isset($_GET['item_id']) && is_numeric($_GET['item_id']) ? intval($_GET['item_id']) : 0; 

    $stmt = $con->prepare("SELECT
                                items.*, 
                                categories.Name AS category_name, 
                                users.username 
                            FROM 
                                items
                            INNER JOIN 
                                categories 
                            ON 
                                categories.ID = items.cat_id 
                            INNER JOIN 
                                users 
                            ON 
                                users.user_id = items.member_id
                            WHERE 
                                item_id = ?
                            AND 
                                approve = 1");

    $stmt->execute(array($item_id));
    $count = $stmt->rowCount(); // Check if the info exist in the DB or not.

    if($count > 0) {
        $item = $stmt->fetch(); // Fetch the data

?>
        <h1 class="text-center"><?php echo $item['name'] ?></h1> 
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <img class="img-fluid img-thumbnail" src="https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_640.png" alt="" />
                </div>
                <div class="col-md-9">
                    <h2><?php echo $item['name'] ?></h2>
                    <p><?php echo $item['description'] ?></p>
                    <ul class="list-unstyled">
                        <li>
                            <i class="fa fa-calendar fa-fw"></i>
                            <span>Added Date</span> : <?php echo $item['date'] ?>
                        </li>
                        <li>
                            <i class="fa fa-money fa-fw"></i>
                            <span>Price</span> : <?php echo $item['price'] ?>
                        </li>
                        <li>
                            <i class="fa fa-building fa-fw"></i>
                            <span>Made In</span> : <?php echo $item['country_made'] ?>
                        </li>
                        <li>
                            <i class="fa fa-tags fa-fw"></i>
                            <span>Category</span> : <a href="categories.php?pageid=<?php echo $item['cat_id'] ?>&pagename=<?php echo $item['category_name'] ?>"><?php echo $item['category_name'] ?></a>
                        </li>
                        <li>
                            <i class="fa fa-user fa-fw"></i>
                            <span>Added By</span> : <a href="#"><?php echo $item['username'] ?></a>
                        </li>
                        <li class="tags-items">
                            <i class="fa fa-user fa-fw"></i>
                            <span>Tags</span> : 
                            <?php 
                             /*$allTags = explode(",", $item['tags']);
                            foreach ($allTags as $tag) {
                                $tag = str_replace(' ', '', $tag);
                                $lowertag = strtolower($tag);
                                if (! empty($tag)) {
                                    echo "<a href='tags.php?name={$lowertag}'>" . $tag . '</a>';
                                }
                            } */ 
                        ?>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <hr class="custom-hr">
       <?php if(isset($_SESSION['user'])) { ?>   
        <div class="row">
            <div class="col-md-offset-3">
                   <div class="add-comment">
                        <h3>Add Ur Comment</h3>
                        <form action="<?php echo $_SERVER['PHP_SELF'] . '?item_id=' . $item['item_id']?>" method="POST">
                            <textarea name="comment" class="form-control" required></textarea>
                            <input class="btn btn-primary" type="submit" name="add" value="Add Comment" >
                        </form>  
                        <?php
                            if($_SERVER['REQUEST_METHOD'] == 'POST') {
                                $comment    = filter_var($_POST['comment'], FILTER_SANITIZE_STRING);
                                $member_id  = $item['member_id'];
                                $item_id    = $item['item_id'];

                                if(!empty($comment)) {

                                    $stmt = $con->prepare('INSERT INTO
                                                                 comments(comment, status, comm_date, item_id, user_id)
                                                             VALUES(:comment, 0, now(), :item_id, :user_id)');
                                    $stmt->execute(array(
                                        'comment' => $comment,
                                        'item_id' => $item_id,
                                        'user_id' => $_SESSION['uid']));

                                    if($stmt) {
                                        echo "<div class='alert alert-success'>Your comment was added</div>";
                                    }

                                } else {
                                    echo "<div class='alert alert-warning'>Comment Can't be Empty</div>";
                                }
                            }
                        ?>
                    </div>
            </div>
                   
        </div>
        <?php } else {
            echo ' You have to <a href="login.php">Login</a> or <a href="login.php">Register</a> to add Comment';

        } ?> 

        <div class="row">
            <div class='col-md-3'><h3>Member</h3></div>
            <div class='col-md-3'><h3>Comment</h3></div>
        </div>
        
        <hr class="custom-hr">

        <?php 
                    $stmt = $con->prepare("SELECT 
                    comments.*, users.username AS Member
                FROM 
                    comments
                INNER JOIN 
                    users ON users.user_id = comments.user_id
                WHERE 
                    item_id = ?
                AND 
                    status = 1
                ORDER BY 
                    comm_id DESC");

                // Execute The Statement

                $stmt->execute(array($item['item_id']));
                $comms = $stmt->fetchAll();

                ?>
            <?php
                
                foreach($comms as $com) { ?> 

                    <div class="comment-box">
                        <div class='row'>
                        <div class='col-md-2 text-center'>
                            <img class="img-fluid img-thumbnail rounded-circle d-block m-auto" src="https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_640.png" alt="" />
                            <?php echo $com['Member']; ?>
                            </div>
                            <div class='col-md-10'>

                                <p class="lead"><?php echo $com['comment'] ?></p>
                            </div>
                        </div>
                    </div>
                    <hr class="custom-hr">

             <?php   }  ?>
        </div>
    <?php

    } else {

        echo "<div class='container'>";

              echo "<div class='alert alert-danger'>There is no Such ID with this Number OR wating Approval from Admin</div>";
               

        echo "</div>";
    }

   include $tmps. "footer.php";?>