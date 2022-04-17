<?php
  session_start();
  if(isset($_SESSION['user'])){
    header("Location: ../../");
    exit();
  }
  // auth-functions.php required for POST and GET requests
  require '../auth-functions.php';

  if($_SERVER['REQUEST_METHOD'] == 'POST'){
    removeExpiredRows($db, "forgot_passwords");
    if(passwordValidates($_POST['password'])){
      // updatePasswords returns the email address from forgot_passwords
      $user = updatePasswords($db, $_POST['password'], $_POST['token']);
      //echo "you are: $user";
      //exit();
      $_SESSION['user'] = $user;
      header('Location: ../../');
      exit();
      //echo "we need to store that password and delete a row from forgot_passwords.";
      //exit();
    } else {
      header("Location: ./?token={$_POST['token']}&err-msg=The password you choose is not valid. Please choose another one"); 
      exit();
    }
  }

  /*
   * if it was not a post request and therefore dealt with above and it does not have a 'token' query string it should not be coming to this page hence:
   */
  if(!isset($_GET['token'])){
    header("Location: ../../");
    exit();
  }
  $forgotPasswordsRow = getForgotPasswordsRow($db, $_GET['token']);
  if(empty($forgotPasswordsRow)){
    header('Location: ../../');
    exit();
  }
  require "../header.php"
?>
    <title>Reset</title>
  </head>
  <body>
    <h1>Reset Password</h1>
    <p>
      Hello <?php echo $forgotPasswordsRow['email']; ?>, please choose a new password for your account.
    </p>
    <p>
      After you submit a valid password you will be automatically logged into your account and taken to the home page.
    </p>
  <?php
    if(isset($_GET['err-msg'])){
      $errMsg = $_GET['err-msg'];
      echo "<p style='color:red'>".$errMsg."</p>";
    }
  ?>
    <form method="post">
    <label for="password">New Password</label>
    <input type="password" id="password" name="password" autocomplete="new-password">
    <br>
    <input type="hidden" name="token" value="<?php echo $_GET['token']; ?>">
    <input type="submit" value="Submit" name="submit">
  </body>
</html>


