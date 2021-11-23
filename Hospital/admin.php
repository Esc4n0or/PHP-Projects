<?php
  include('header.php');
?>
  <table class="hhh">
    <th>رقم الحجز</th>
    <th>إسم المريض</th>
    <th>البريد الإلكتروني</th>
    <th>تاريخ الحجز</th>

<?php
  // Connect to the database;
  $host      = 'localhost';
  $user      = 'root';
  $password  = 'password';
  $db_name   = 'hospital';
  $connect   = mysqli_connect($host, $user, $password,$db_name);

  $query   = "SELECT * FROM patients";
  $Showing = mysqli_query($connect, $query);

  if ($Showing){
        while($rows = mysqli_fetch_assoc($Showing)){
            echo "<tr><td>" . $rows['id'] . "</td><td>" . $rows['name'] . "</td><td>" . $rows['email'] . "</td><td>" . $rows['date'] . "</td></tr>";
        }
        echo "</table>";
    }
    else{
        echo "يوجد خطا ماء";
    }


?>
