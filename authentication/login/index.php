<?php
  session_start();
  if(isset($_SESSION['user'])){
    header('Location: ../../');
    exit();
  }
  if($_SERVER['REQUEST_METHOD'] == 'POST'){
    require '../auth-functions.php';
    $user = login($db, $_POST['email'], $_POST['password']);
    if($user){
      $_SESSION['user'] = $user["email"];
      $_SESSION['uid'] = $user["id"];
     header('Location: ../../'); 
      exit();
    } else {
     header('Location: ./?err-msg=failed-login'); 
     exit();
    }  
  }
  require "../header.php"
?>
  <title>Login</title>
</head>
<body>
  <h1>Login</h1>
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
  <input type="password" id="password-input" name="password" required>
  <input type="submit" value="Login" name="submit">
  </form>
  <input type='checkbox' onclick='togglePassword()'>Show Password
  <p><a href="../forgot/">forgot</a> password?</p>
<script>
function togglePassword() {
  var x = document.getElementById("password-input");
  if (x.type === "password") {
    x.type = "text";
  } else {
    x.type = "password";
  }
}
</script>
</body>
</html>


