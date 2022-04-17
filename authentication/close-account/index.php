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

  if($_SERVER['REQUEST_METHOD'] == 'POST'){
    require '../auth-functions.php';
    if(authenticates($db, $_POST["email"], $_POST["password"])){
      deleteUser($db, $_POST["email"]);
      session_destroy();
      header('Location: ../../');
      exit();
    } else {
      header("Location: ./?err-msg=Either the password or email entered were incorrect");
      exit();
    }
  }
  require "../header.php"
?>
  <title>Close Account</title>
</head>
<body>
  <h1>Close Account</h1>
  <p>
    Please enter your email address and password in order to close your account.
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
  <label for="email">Email</label>
  <input type="email" id="email" name="email" required>
  <label for="password">Password</label>
  <input type="password" id="password" name="password" required>
  <input type="submit" value="Close Account" name="submit">
</body>
</html>

