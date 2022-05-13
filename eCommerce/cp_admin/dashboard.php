<?php 
    session_start();
    if (isset($_SESSION['ADMIN'])){

        $title = 'Admin Dashboard';

        include 'init.php';
        ?>

        <div class="container home-stats text-center">
            <h1>Dashboard</h1>
            <div class="row">
                <div class="col-md-3">
                    <div class="stat st-members">
                        <i class="fa fa-users"></i>
                        <div class="info">
                            Total Members
                            <span>
                                <a href="members.php"><?php echo countItems('user_id', 'users') ?></a>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat st-pending">
                        <i class="fa fa-user-plus"></i>
                        <div class="info">
                            Pending Memebers
                            <span>
                                <a href="members.php?action=manage&page=pending"><?php echo checkExist('reg_status', 'users', 0) ?></a>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat st-items">
                        <i class="fa fa-tag"></i>
                        <div class="info">
                            Total items
                            <span>
                                <a href="items.php"><?php echo countItems('item_id', 'items') ?></a>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat st-comments">
                        <i class="fa fa-comments"></i>
                        <div class="info">
                            Total comments
                            <span><a href="comments.php"><?php echo countItems('comm_id', 'comments') ?></a></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container latest">
            <div class="row">
                <div class="col-sm-6">
                    <div class="card card-default">
                        <div class="card-header">
                            <i class="fa-solid fa-book-journal-whills"></i> Latest Registered Users
                            <span class="toggle-info pull-right">
                                <i class="fa fa-plus fa-lg"></i>
                            </span>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled latest-users">
                                <?php 
                                    $l_users = getLatest('*', 'users', 'Date', $limit = 5);

                                    if (!empty($l_users)) {

                                        foreach( $l_users as $user) {
                                            echo "<li>";
                                                echo $user['username'];
                                                echo'<a href="members.php?action=edit-profile&id=' . $user['user_id']  . '" >';
                                                    echo "<span class='btn btn-info pull-right'>";
                                                        echo "<i class='fa fa-edit'></i> Edit";
                                                        if ($user['reg_status'] == 0) {
    
                                                            echo "<a href='members.php?action=activate&id=" . $user['user_id'] . "' class='btn btn-success activate'><i class='fa-solid fa-check'></i> Activate </a>";
                                                        }
                                                    echo"</span>";
                                                echo "</a>";    
                                            echo "</li>";
                                        }

                                    } else {
                                        echo "There\'s no Member To show";
                                    }
                                    

                                     ?>
                             </ul>   
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="card card-default">
                        <div class="card-header">
                            <i class="fa-solid fa-font-awesome"></i> Latest Items
                            <span class="toggle-info pull-right">
								<i class="fa fa-plus fa-lg"></i>
							</span>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled latest-users">
                                    <?php 
                                
                                        $l_items = getLatest('*', 'items', 'date', $limit = 5);
                                        if(!empty($l_items)) {
                                            foreach( $l_items as $item) {
                                                echo "<li>";
                                                    echo $item['name'];
                                                    echo'<a href="items.php?action=edit&item_id=' . $item['item_id']  . '" >';
                                                        echo "<span class='btn btn-info pull-right'>";
                                                            echo "<i class='fa fa-edit'></i> Edit";
                                                            if ($item['approve'] == 0) {
    
                                                                echo "<a href='items.php?action=approve&item_id=" . $item['item_id'] . "'
                                                                 class='btn btn-success activate'><i class='fa fa-check'></i> Approve </a>";
                                                            }
                                                        echo"</span>";
                                                    echo "</a>";    
                                                echo "</li>";
                                            }
                                        } else {
                                            echo "<div class='alert alert-info'>There's no item To show</div>";
                                        }
                                         ?>
                                </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        include $tmps. "footer.php";
    }else{
        header('Location: index.php');
        exit();
    }