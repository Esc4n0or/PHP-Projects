<?php


    ini_set('display_errors', 'On');
    error_reporting(E_ALL);

    include "cp_admin/connect.php";
    // Pathes to Directories

    // Create User Session
    $sessionUser ='';
    if (isset($_SESSION['user'])) {
        $sessionUser = $_SESSION['user'];
    }
    

    $tmps = 'includes/templates/'; // Templates Dir
    $css  = 'design/css/'; // CSS Dir
    $js   = 'design/js/'; // JS Dir
    $lang = 'includes/languages/'; // Languages Dir
    $func = 'includes/functions/'; // Functions Dir


    // Important Files

    include $lang . "en.php";
    

    include $func . "function.php";

    include $tmps . "header.php";
    
    
    