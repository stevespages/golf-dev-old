<?php
/**
 * Home page
 */
session_start();
/*
 * Error reporting
 *
 * Comment this out in production
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once './header.php';
?>
        <title>Golf</title>
    </head>
    <body>
        <h1>Golf</h1>
        <p>
<?php
if (empty($_SESSION['user'])) {
    echo "<a href='./authentication/login/'>Login</a> | ";
    echo "<a href='./authentication/register/'>Register</a>";
    echo "</p>";
} else {
    echo "Hallo ".$_SESSION['user'];
    echo " (".$_SESSION["uid"].")";
    echo " | <a href='./authentication/logout/'>Logout</a>";
    echo " | <a href='./authentication/change-password/'>Change Password</a>";
    // Implement this if necessary
    // echo " | <a href='./authentication/change-email/'>Change Email</a>";
    echo " | <a href='./authentication/close-account/'>Close Account</a> | ";

    // Create all the tables if not already done
    require "./php/db-functions.php";
    require "./php/create-tables.php";
?>
      <a href="./about/">About</a> | 
      <a href="./courses/">Courses</a> | 
      <a href="./competitions/">Competitions</a> | 
      <a href="./players/">Players</a> | 
      <a href="./create-course/">Create Course</a> | 
      <a href="./create-competition/">Create Competition</a> | 
      <a href="./create-player/">Create Player</a>
    </p>
<?php } ?>
    </body>
</html>
