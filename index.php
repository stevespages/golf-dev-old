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
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- From realfavicongenerator.com
        <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
        <link rel="manifest" href="/site.webmanifest">
        <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
        <meta name="msapplication-TileColor" content="#da532c">
        <meta name="theme-color" content="#ffffff">
        End of realfavicongenerator.com stuff -->
        <!-- my code but using the realfavicongenerator's image -->
        <link href="golf-ball.svg" rel="icon">
        <!-- END of my code but using the realfavicongenerator's image -->
        <link rel="stylesheet" href="./css/main.css">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
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
