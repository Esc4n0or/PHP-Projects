<?php

    function lang($phrase){

        static $language = array(

            // Navbar Links

            'ADMIN_PANEL'  => 'Admin-Panel',
            'CATEGORIES'   => 'Categories',
            'ITEMS'        => 'Items',
            'MEMBERS'      => 'Users-Panel',
            'COMMS'        => 'Comments',
            'STATS'        => 'Statistecis',
            'LOGS'         => 'Logs',
            'EDIT_PROFILE' => 'Edit_Profile',
            'SETTINGS'     => 'Settings',
            'LOGOUT'       => 'Logout',
            'INTERFACE'    => 'Shop',
            
            ''   => '',
            '' => '',
            ''   => '',
        );

        return $language[$phrase];
    }
?>