<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="dashboard.php"><?php echo lang('ADMIN_PANEL')?></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#app-nav" aria-controls="app-nav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="app-nav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="categories.php"><?php echo lang('CATEGORIES')?></a>
                </li>

                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="items.php"><?php echo lang('ITEMS')?></a>
                </li>

                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="members.php?action=manage"><?php echo lang('MEMBERS')?></a>
                </li>

                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="comments.php"><?php echo lang('COMMS')?></a>
                </li>

            
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="#"><?php echo lang('STATS')?></a>
                </li>

                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="#"><?php echo lang('LOGS')?></a>
                </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle active" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Taha
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="../index.php"><?php echo lang('INTERFACE')?></a></li>
                        <li><a class="dropdown-item" href="members.php?action=edit-profile&id=<?php echo $_SESSION['ID'] ?>"> <?php echo lang('EDIT_PROFILE')?></a></li>
                        <li><a class="dropdown-item" href="#"><?php echo lang('SETTINGS')?></a></li>
                        <li><a class="dropdown-item" href="logout.php"><?php echo lang('LOGOUT')?></a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>


                    
                    
                    
                    