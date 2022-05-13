<?php

    session_start();

    if (isset($_SESSION['user'])){
        header('Location: index.php');
    }

    include "init.php";
    
    if ($_SERVER['REQUEST_METHOD'] == "POST") {

        if (isset($_POST['login'])) {
        $user = $_POST['username'];
        $pass = $_POST['password'];
        $hash_pass = sha1($pass);
        
        $stmt = $con->prepare("SELECT
                                 user_id,username, password
                               FROM
                                 users
                                WHERE
                                 username = ?
                                AND
                                 password = ?
                                 ");
        $stmt->execute(array($user, $hash_pass));
        $row = $stmt->fetch();
        $record = $stmt->rowCount();

        if ($record > 0){
            $_SESSION['user'] = $user;
            $_SESSION['uid'] = $row['user_id'];
            header('Location: index.php');
            }
        } else {

            $formErrors = array();
            $user = $_POST['username'];
            $pass = $_POST['password'];
            $pass_two = $_POST['password2'];
            $email= $_POST['email'];
            // Check Username field
            if(isset($user) && !empty($user)) {
                $username = filter_var($user, FILTER_SANITIZE_STRING);
                if(strlen($username) < 4) {
                    $formErrors[] = "Username Must be large than 4 charcters.";    
                }
            } else { $formErrors[] =  "Username field can't be empty"; }
            // Check Password field
            if(isset($pass) && isset($pass_two)) {
                if (!empty($pass) && !empty($pass_two)) {
                    $pass1 = sha1($pass);
                    $pass2 = sha1($pass_two);

                    if ($pass1 !== $pass2) {
                        $formErrors[] = "Password dosen't Match.";
                    }

                } else {  $formErrors[] =  "Password field can't be empty"; }
                
            }

            // Check Email field
            if(isset($email) && !empty($email)) {
                $filterEmail = filter_var($email, FILTER_SANITIZE_EMAIL);
                if (filter_var($email, FILTER_VALIDATE_EMAIL) != true) {
                    $formErrors[] =  "Email isn't Valid";
                }
            } else {  $formErrors[] =  "Email field can't be empty"; }

            if (empty($formErrors)) {

                // Check if the user exist or not.

                $exist = checkExist("username", "users", $user);

                if ($exist == 1) {

                    echo "<div class='container'>";

                        $formErrors[] =  "<div class='alert alert-danger'>Faild to add User.</div>";
                    
 
                    echo "</div>";
                } else {

                    $stmt = $con->prepare("INSERT INTO

                                        users(username, password, email, reg_status,  Date)

                                        VALUES(:user, :pass, :email, 0 ,now())" ); // You can choose any names in VALUES field.

                $stmt->execute(array('user' => $user,'pass' => sha1($pass),'email' => $email));

                echo "<div class='container'>";

                    $addedUser = "<div class='alert alert-success'>You successfuly Registered</div>";

                echo "</div>";
                    }
             }
        }  

    }

?>
    <div class="container login-page">
        <h1 class="text-center">
            <span class="selected" data-class="login">Login</span> | <span data-class="signup">SignUp</span>
        </h1>
        <!-- Login Form -->
        <form class="login" action="<?php echo $_SERVER['PHP_SELF']?>" method="POST" >
            <input type="text" name="username" autocomplete="off" class="form-control" placeholder="Type your Username" required="required"> 
            <input type="password" name="password" autocomplete="new-password" class="form-control" placeholder="Type your Password" required="required"> 
            <input type="submit" name="login" class="btn btn-primary btn-block" value="Login"> 
        </form>

        <!-- SignUp Form --> 
        <form class="signup" action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">
            <input pattern = ".{4,}" title = "Username must be more than 4 char" type="text" name="username"
             autocomplete="off" class="form-control" placeholder="Type your Username" required="required"> 

            <input minlength="4" type="password" name="password" autocomplete="new-password" class="form-control"
             placeholder="Type your Password" required="required"> 

            <input minlength="4" type="password" name="password2" autocomplete="new-password" class="form-control" 
            placeholder="Confirm Password" required="required"> 

            <input type="email" name="email" autocomplete="off" class="form-control" placeholder="Type your Email" required="required">
            
            <input type="submit" name="signup" class="btn btn-success btn-block" value="Login"> 
        </form>

        
        <div class="errors text-center">
            <?php
                if(!empty($formErrors)) {
                    foreach($formErrors as $error) {
                        echo '<div class="msg error">' . $error . '</div>'; 
                    }
                }
                
                if (isset($addedUser)) {

                    echo $addedUser;
                }
            ?> 
        </div>
    </div>

        
<?php include $tmps. "footer.php";?>