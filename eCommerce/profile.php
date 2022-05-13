<?php
    session_start();
    include "init.php"; 

    if(isset($_SESSION['user'])) { 

        $userInfo = $con->prepare('SELECT * FROM users WHERE username = ?');
        $userInfo->execute(array($sessionUser));
        $info = $userInfo->fetch();
        

        ?>
        <!--  -->  
        <h1 class="text-center">My Profile</h1>
        <div class="information block">
            <div class="container">
                <div class="card border-primary mb-3">
                    <h4 class="card-header"><i class="fa fa-edit"></i> User Information</h4>
                    <div class="card-body">
                        <ul class="list-unstyled">
                           <li>
                               <i class="fa fa-unlock-alt fa-fw"></i>
                               <span>Name:</span>         <?php echo $info['username']; ?>
                            </li>

                           <li>
                               <i class="fa fa-envelope-o fa-fw"></i>
                               <span>Email:</span>        <?php echo $info['email']; ?>       
                            </li>

                           <li>
                               <i class="fa fa-user fa-fw"></i>
                               <span>Full-Name:</span>    <?php echo $info['full_name']; ?>   
                            </li>

                           <li>
                               <i class="fa fa-calendar fa-fw"></i>
                               <span>Register:</span>     <?php echo $info['Date']; ?>        
                            </li>

                           <li>
                               <i class="fa fa-tags fa-fw"></i>
                               <span>Fav-Category:</span> <?php echo $info['username']; ?>    
                            </li>
                        </ul>
                        <a href="#" class="btn btn-info">Edit Information</a>
                    </div>
                </div>
            </div>
        </div> 



        <div id ="my-ads" class="my-ads block">
            <div class="container">
                <div class="card border-warning mb-3">
                    <h4 class="card-header">My Items</h4>
                    <div class="card-body">
                        
                        <?php 
                            if(!empty(getItem('member_id', $info['user_id']))) {
                                echo '<div class="row">';
                                        foreach(getItem('member_id', $info['user_id']) as $item) {
                                            
                                            echo '<div class="col-sm-6 col-md-3">';
                                                        echo '<div class="card item-box">';
                                                            if($item['approve'] == 0) {
                                                                echo '<span class="approve-status">Waiting Approval</span>';}
                                                            echo '<span class="price-tag">$' . $item['price'] . '</span>';
                                                            echo '<img class="img-fluid" src="https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_640.png" alt="" />';
                                                            echo '<div class="caption">';
                                                                echo '<h3><a href="items.php?item_id='. $item['item_id'] .'">' . $item['name'] .'</a></h3>';
                                                                echo '<p>' . $item['description'] . '</p>';
                                                                echo '<div class="date">' . $item['date'] . '</div>';
                                                            echo '</div>';
                                                        echo '</div>';
                                            echo '</div>';
                                
                                }
                                echo '</div>';
                            } else {
                                echo "There\'re no items to show, create <a href='newad.php'>New Item</a>";
                            }
                            

                        ?>
                        </div>
                    
                    </div>
                </div>
            </div>
        </div> 

        <div class="information block">
            <div class="container">
                <div class="card text-white bg-dark mb-3">
                    <h4 class="card-header">Latest Comments</h4>
                    <div class="card-body">
                        <?php
                            $stmt = $con->prepare('SELECT * FROM comments WHERE user_id = ?');
                            $stmt->execute(array($info['user_id']));
                            $comments = $stmt->fetchAll();

                            if(!empty($comments)){
                                foreach($comments as $comment) {
                                    echo '<p>' . $comment['comment'] . '</p>';
                                }
                            }else {
                                echo "There're no comments to show.";
                            }
                        
                        ?>
                    </div>
                </div>
            </div>
        </div> 

        <?php } else {
            header('Location: login.php');
            exit();
}



include $tmps. "footer.php";?>