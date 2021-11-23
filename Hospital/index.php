<?php
  include "header.php";
 ?>
      <div class='booking'>
        <p>أهلا بكم في مستشفى قوص المركزي، من فضلك إملء البيانات التالية</p>
        <form action="index.php" method="post">
          <input type="text" placeholder="أدخل الأسم بالكامل" name="name" pattern="^[a-zA-Z]+$"><br>
          <input type="email" placeholder="أدخل البريد الألكتروني" name="email"><br>
          <input type="date" name="date"><br>
          <input type="submit" value="إحجز الآن" name="send">
        </form>

        <?php
          // Connect to the database;
          $host      = 'localhost';
          $user      = 'root';
          $password  = 'password';
          $db_name   = 'hospital';
          $connect = mysqli_connect($host, $user, $password,$db_name);

          // Storing values of the patients;
           if(isset($_POST) && array_key_exists('send',$_POST)){
             $p_name    = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
             $p_email   = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_SPECIAL_CHARS);
             $p_date    = $_POST['date'];
           }

           date_default_timezone_set("Africa/Cairo");
           $date = date('m/d/Y h:i:s a');
          // Sending values to the database;
          if(isset($_POST['send'])){
            if(!empty($p_name) and !empty($p_email) and !empty($p_date)){
              if(strtotime($p_date) >= strtotime($date)){
                $query = "INSERT INTO patients(name,email,date) VALUE('$p_name','$p_email','$p_date')";
                $Sender = mysqli_query($connect, $query);
                echo "<h3>"."شكراً لك".$p_name. ", لقد تم الحجز." . "</h3>";
              }else{
                echo "<p style='color:red'>". "يجب عليك إختيار ميعاد صحيح." . "</p>";
              }

            }else {
              echo "<h3 style='color:red'>"."عفواً، يجب عليك ملء البيانات أولاً"."</h3>";
            }
          }




          ?>
      </div>
    </div>
  </body>
</html>
