<?php


    $action = isset($_GET['action']) ? $_GET['action'] : 'Manage';

    if ($action == 'Manage') {
        echo "Manage Page";
    }elseif ($action == 'add') {
        echo "Add Page";
    }elseif ($action == 'delete') {
        echo "Delete Page";
    }else {
        echo "ERORR 404";
    }