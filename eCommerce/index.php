<?php
    session_start();
    include "init.php"; ?>

    
    <div class="container">
        <div class="row">

            <?php 
                $allItems = getAll('items', 'item_id', 'where approve = 1');
                foreach($allItems as $item) {
                    echo '<div class="col-sm-6 col-md-3">';
                                echo '<div class="card item-box">';
                                    echo '<span class="price-tag">' . $item['price'] . '</span>';
                                    echo '<img class="img-fluid" src="https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_640.png" alt="" />';
                                    echo '<div class="caption">';
                                        echo '<h3><a href="items.php?item_id='. $item['item_id'] .'">' . $item['name'] .'</a></h3>';
                                        echo '<p>' . $item['description'] . '</p>';
                                        echo '<div class="date">' . $item['date'] . '</div>';
                                    echo '</div>';
                                echo '</div>';
                    echo '</div>';
                    // echo $item['name'] . '<br>';
                }

            ?>

        </div>
    

    </div>
        
<?php include $tmps. "footer.php";?>