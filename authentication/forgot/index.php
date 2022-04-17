<?php
  session_start();
  if(isset($_SESSION['user'])){
    header('Location: ../../');
    exit();
  }
  if($_SERVER['REQUEST_METHOD'] == 'POST'){
    require '../auth-functions.php';
    if(!itemExists($db, 'passwords', 'email', $_POST['email'])){
      header("Location: ../../");
      exit();
    }
    sendResetPasswordEmail($db, $_POST['email'], $baseUrl);
    header("Location: ../../");
    exit();
  }
  require "../header.php"
?>
  <title>Forgot</title>
</head>
<body>
  <h1>Forgot Password</h1>
  <p>
    Please enter the email address for the account. If there is an account for that email address an email will be sent to it with a link to choose a new password.
  </p>
  <p>
    After submitting your email address you will be taken to the home page.
  </p>
  <form method="post">
  <label for="email">Email</label>
  <input type="email" id="email" name="email" required>
  <input type="submit" value="Submit" name="submit">
  </form>
</body>
</html>

