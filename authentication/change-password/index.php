<?php
  session_start();

  /*
   * if they are not logged in redirect to home page.
   */
  //if(!isset($_SESSION['user'])){
  if(empty($_SESSION['user'])){
    header('Location: ../../');
    exit();
  }

  // auth-functions.php required for POST and GET requests
  require '../auth-functions.php';

  if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $user = login($db, $_POST['email'], $_POST['existing-password']);
    if($_SESSION['user'] !== $user["email"]){
      header("Location: ./?err-msg=Either your existing password or email are not correct");
      exit();
    }
    if(passwordValidates($_POST['new-password'])){
      $hashedPassword = password_hash($_POST['new-password'], PASSWORD_DEFAULT);
      // update the password field!!
      changePassword($db, $_POST["email"], $hashedPassword);
      header('Location: ../../?success-msg=You have changed your password');
      exit();
    } else {
      header("Location: ./?err-msg=The password you chose is not valid. Please choose another one"); 
      exit();
    }
  }

  require "../header.php"
?>
    <title>Change Password</title>
  </head>
  <body>
    <h1>Change Password</h1>
    <p>
      Hello <?php echo $_SESSION['user']; ?>. Please submit your email and existing password and a new valid password. You will then be redirected to the home page.
    </p>
  <?php
    if(isset($_GET['err-msg'])){
      $errMsg = $_GET['err-msg'];
      echo "<p style='color:red'>".$errMsg."</p>";
    }
  ?>
    <form method="post">
    <label for="email">Email</label>
    <input type="email" id="email" name="email">
    <label for="existing-password">Existing Password</label>
    <input type="password" id="existing-password" name="existing-password" autocomplete="password">
    <label for="new-password">New Password</label>
    <input type="password" id="new-password" name="new-password" autocomplete="new-password">
    <br>
    <input type="submit" value="Submit" name="submit">
  </body>
</html>

