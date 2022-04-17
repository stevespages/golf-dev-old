<?php
  session_start();
  if(isset($_SESSION['user'])){
    header("Location: ../../");
    exit();
  }

  require '../auth-functions.php';
  // remove expired tokens first:
  removeExpiredRows($db, "registers");
  $registerRow = getRegisterRow($db, $_GET['token']);
  // If token was removed because it expired or
  // if token has been altered redirect to home page
  if(empty($registerRow)){
    // append err-msg: Your account has not been verified.
    header('Location: ../../'); 
    exit();
  } else {
    // if token was found insert register row into passwords table
    insertRegisterRowIntoPasswords($db, $registerRow);
    deleteRegisterRowFromRegisters($db, $registerRow["token"]);
    // now login or log them in ?????
    // append err-msg: Your account has been verified and you can logged in.
    header("Location: ../../");
    exit();
  }

