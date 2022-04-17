<?php
  session_start();

  /*
   * test to see if they are already logged in. If they are redirect to home page. If not continue.
   */
  if(isset($_SESSION['user'])){
    header('Location: ../../');
    exit();
  }

/*
  Comment this out in production
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

  if($_SERVER['REQUEST_METHOD'] == 'POST'){
    // Uncomment this section if you want an email allow list
    // if($emailAllowListEnabled){
      // $emailAllowList = array("greigsteve@gmail.com", "bob@bob.com");
      // if(!in_array($_POST['email'], $emailAllowList)){
        // header("Location: ../../?msg=contactSteve");
        // exit();
      // }
    // }
    require '../auth-functions.php';
    // should we also be checking if email is in registers table?
    // if they register twice before verifying delete first registration?
    if(itemExists($db, 'passwords', 'email', $_POST['email'])){
      header("Location: ../login/?err-msg=An account already exists for {$_POST['email']}. Please login.");
      exit();
    }
//  We are not having users at the moment
//    if(itemExists($db, 'passwords', 'user', $_POST['user'])){
//      header("Location: ./?err-msg=The user name {$_POST['user']}, already exists. Please choose a different user name.");
//      exit();
//    }
    if(!emailValidates($db, $_POST['email'])){
      header("Location: ./?err-msg=The email, {$_POST['email']}, does not validate. Please enter a different email.");
      exit();
    }
//  We are not having users at the moment
//    if(!userValidates($_POST['user'])){
//      header("Location: ./?err-msg=The user name {$_POST['user']} does not validate. Please choose a different user name.");
//      exit();
//    }
    // We ar not confirming passwords at the moment
    // Check password === confirm-password.
    // If user accepts password suggestion Google autocompletes confirm-password.
    // Not sure about other browsers.
    // if(isset($_POST["confirm-password"])){
      // if($_POST["password"] !== $_POST["confirm-password"]){
        // header("Location: ./?err-msg=Passwords do not match.");
        // exit();
      // }
    // }

    $token = insertIntoRegisters($db, $_POST["email"], $_POST["password"]);

    // make this dependent on insertIntoRegisters() being successful
    if(isset($token)){
      sendRegisterEmail($_POST['email'], $token, $baseUrl);
    }
    header('Location: ../../');
    exit();
  }
  require "../header.php"
?>
  <title>Register</title>
</head>
<body>
  <h1>Register</h1>
  <p>
    Please register an email address for the account. After you register you will be returned to the home page. An email will be sent to you with a link from which you can verify your email address. You will then be able to login using the password you chose when you registered. The verification link is only valid for 24 hours. If it expires please register again if you still want to open an account.
  </p>
  <p>
    After registering you will be taken to the home page.
  </p>
  <p style="color:red">
    <?php
      if(isset($_GET['err-msg'])){
        $errMsg = $_GET['err-msg'];
        echo $errMsg;
      }
    ?>
  </p>
  <form method="post">
<!-- We are not having user names at the moment
  <label for="user">User Name</label>
  <input type="text" id="user" name="user" required>
-->
  <label for="email">Email</label>
  <!-- make sure email is type="text" WHY?? -->
  <input type="text" id="email" name="email" required>
  <label for="password">Password</label>
  <input type="password" id="password" name="password" autocomplete="new-password">
  <!-- Forget about this until system is up and running
  <label for="confirm-password">Confirm Password</label>
  <input type="password" id="confirm-password" name="confirm-password" autocomplete="new-password">
  -->
  <input type="submit" value="Register" name="submit">
</body>
</html>

