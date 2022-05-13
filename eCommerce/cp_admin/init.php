<?php

    include "connect.php";
    // Pathes to Directories

    $tmps = 'includes/templates/'; // Templates Dir
    $css  = 'design/css/'; // CSS Dir
    $js   = 'design/js/'; // JS Dir
    $lang = 'includes/languages/'; // Languages Dir
    $func = 'includes/functions/'; // Functions Dir


    // Important Files

    include $lang . "en.php";
    
    include $tmps . "header.php";

    include $func . "function.php";

    if (!isset($No_Navbar)){ include $tmps . "navbar.php"; }
    