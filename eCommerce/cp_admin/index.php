<?php 
    session_start();
    $No_Navbar = '';
    // $title = '';
    if (isset($_SESSION['ADMIN'])){
        header('Location: dashboard.php');
    }
    include "init.php";
    

    if ($_SERVER['REQUEST_METHOD'] == "POST") {

        $username = $_POST['username'];
        $password = $_POST['password'];
        $hash_pass = sha1($password);
        
        $stmt = $con->prepare("SELECT
                                 user_id,username, password
                               FROM
                                 users
                                WHERE
                                 username = ?
                                AND
                                 password = ?
                                AND
                                 group_id = 1
                                 LIMIT 1");
        $stmt->execute(array($username, $hash_pass));
        $row = $stmt->fetch();
        $record = $stmt->rowCount();

        if ($record > 0){
            $_SESSION['ADMIN'] = $username;
            $_SESSION['ID'] = $row['user_id'];
            header('Location: dashboard.php');
        }


    }
  ?>  
    <form class="login" action="<?php echo $_SERVER['PHP_SELF']?>" method="POST" >
        <h3 class="text-center">Admin Panel</h3>
        <input type="text" class="form-control" name="username" placeholder="Username" autocomplete="off" />
        <input type="password" class="form-control" name="password" placeholder="Password" autocomplete="new-password" />
        <input type="submit" class="btn btn-primary btn-block" name="login" value="Login" />
    </form>
    
<?php 

include $tmps. "footer.php";
?>