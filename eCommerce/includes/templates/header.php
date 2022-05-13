<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Hello</title>
        <script src="https://kit.fontawesome.com/ad2260c6b4.js" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="<?php echo $css;?>bootstrap.min.css">
        <link rel="stylesheet" href="<?php echo $css;?>jquery-ui.css">
        <link rel="stylesheet" href="<?php echo $css;?>jquery.selectBoxIt.css">
        <link type="text/css" rel="stylesheet" href="<?php echo $css;?>forntend.css">
    </head>
    <body>
        <div class="Upper-bar">
            <div class="container">
                <?php
                    if(isset($_SESSION['user'])) {
                        if (checkUserStatus($sessionUser) === 1) {
                            echo '<b>' . $sessionUser . '</b>' . ' You need to activate your Email. - ';
                            echo "<a href='logout.php'>Logout</a>";
                        } else {
                            
                            echo "Welcome " . $sessionUser . '  '; ?> 
                            <img class="my-image img-thumbnail img-circle" src="img.png" alt="" />
                             <?php
                            echo "<a href='profile.php'>My Profile</a> - ";
                            echo "<a href='newad.php'>New item</a> - ";
                            echo "<a href='logout.php'>Logout</a>";
                        }
                    } else { ?> 
                        <a href="login.php">
                            <span class="pull-right">Login/SignUp</span>
                        </a>
                  <?php  }  ?>
               
            </div>
        </div>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark nav">
        <div class="container">
            <a class="navbar-brand" href="index.php">MainPage</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#app-nav" aria-controls="app-nav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="app-nav">
                <ul class="nav navbar-nav navbar-right">
                    <?php 
                        $cats = getAll('categories', 'ID', 'Where parent = 0');

                        foreach($cats as $cat){
                            echo '<li><a href="categories.php?pageid=' . $cat['ID'] . '&pagename=' . str_replace(' ', '-', $cat['Name']).'">'
                            . $cat['Name'] . '</a></li>';
                        }
                    ?> 
                </ul>
            </div>
        </div>
    </nav>
