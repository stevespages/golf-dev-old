<?php
  /*
    Comment this out in production
   */
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);

 /**
  * Configuration
  *
  * Give the base url for your website
  *
  * Do put a forward slash at the end
  *
  * For example:
  * $baseUrl = https://example.com/
  * or
  * $baseUrl = https://example.com/a-directory/
  */
$baseUrl = "https://stevespages.org.uk/golf/";

  $dsn = 'sqlite:'.__DIR__.'/sqlite/database.db';
  try {                    
    $db = new PDO($dsn);
  } catch (Exception $e) {    
    $error = $e->getMessage();
  }

  function itemExists($db, $table, $column, $item){
    $sql = "SELECT COUNT($column) FROM $table WHERE $column = :item";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':item', $item);
    $stmt->execute();
    $result = $stmt->fetch();
    if($result[0]==0){
      return false;
    } else {
      return true;
    }
  }

  function emailValidates($db, $email){
    if(strpos($email, '@') !== false) {
      return true;
    } else {
      return false;
    }
  }

// user name is not required
// It complicates life for users and developers
// user names could be obtained independently of registration
// the user field has been left in the database tables should they be needed. 
//  function userValidates($user){
//    if(strlen($user)>25){
//      return false;
//    } else {
//      return true;
//    }
//  }

  function passwordValidates($password){
    if(strlen($password)<8){
      return false;
    } else {
      return true;
    }
  }

  function insertIntoRegisters($db, $email, $password){
    $token = bin2hex(random_bytes(50));
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO registers (email, hashed_password, token, expiry_datetime) VALUES (:email, :hashed_password, :token, datetime('now', '+1 day'))";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':hashed_password', $hashedPassword);
    $stmt->bindParam(':token', $token);
    $stmt->execute();
    return $token;
  }

  function sendRegisterEmail($email, $token, $baseUrl){
    $subject = "Verify Account";
    $msg = "Please click " . $baseUrl . "authentication/verify/?token=$token to verify your email address and choose a password for your account";
    $headers = "From: golf@golf.com";
    mail($email, $subject, $msg, $headers);
  }

  function removeExpiredRows($db, $table){
    $sql = "DELETE FROM $table WHERE expiry_datetime < datetime('now')";
    $stmt = $db->prepare($sql);
    $stmt->execute();
  }

  function getRegisterRow($db, $token){
    $sql = "SELECT email, hashed_password, token FROM registers WHERE token = :token";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(":token", $token);
    $stmt->execute();
    $result = $stmt->fetch();
    return $result;
  }

  function insertRegisterRowIntoPasswords($db, $registerRow){
    $sql = "INSERT INTO passwords (email, hashed_password, datetime) VALUES ('".$registerRow["email"]."', '".$registerRow["hashed_password"]."', datetime('now'))";
    $stmt  = $db->prepare($sql);
    $stmt->execute();
  }

  function deleteRegisterRowFromRegisters($db, $token){
    $sql = "DELETE FROM registers WHERE token = :token";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':token', $token);
    $stmt->execute();
  }

  function login($db, $email, $password){
    $sql = "SELECT id, email, hashed_password FROM passwords WHERE email='$email'";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch();
    $hashedPassword = $result['hashed_password'];
    $authenticated = password_verify($password, $hashedPassword);
    if($authenticated){
      return $result;
    }
  }

  function authenticates($db, $email, $password){
    $sql = "SELECT email, hashed_password FROM passwords WHERE email='$email'";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch();
    $hashedPassword = $result['hashed_password'];
    return password_verify($password, $hashedPassword);
  }

  function deleteUser($db, $email){
    $sql = "DELETE FROM passwords WHERE email = :email";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
  }

  function sendResetPasswordEmail($db, $email, $baseUrl){
    $token = bin2hex(random_bytes(50));
    $sql = "INSERT INTO forgot_passwords (email, token, expiry_datetime) VALUES (:email, '$token', datetime('now', '+1 day'))";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $subject = "Forgot Password";
    $msg = "Please click " . $baseUrl . "authentication/reset/?token=$token to create a new password for your account";
    $headers = "From: golf@golf.com";
    mail($email, $subject, $msg, $headers);
  }

  // updatePasswords is for when user forgot their password.
  // this need to be split into two or more functions. Also id might be
  // better to use than email. Certain of these functions could be used
  // in more than one part of the code base.
  //
  // Early in script get rid of expired rows from database. Then you do
  // not need to check the date of the token later, just whether it exists or not.
  function updatePasswords($db, $password, $token){
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $sql = "SELECT email FROM forgot_passwords WHERE token='$token'";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch();
    $email = $result['email'];

    // email should be a bound parameter like password. Change it!
    $sql = "UPDATE passwords SET hashed_password = :password WHERE email='$email'";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':password', $hashedPassword);
    $stmt->execute();

    // The row in forgot_passwords is no longer needed so delete it:
    $sql = "DELETE FROM forgot_passwords WHERE token = :token";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':token', $token);
    $stmt->execute();

    return $email;
  }

  function getForgotPasswordsRow($db, $token){
    $sql = "SELECT email FROM forgot_passwords WHERE token='$token'";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch();
    return $result;
  }

  function changePassword($db, $email, $hashedPassword){
    $sql = "UPDATE passwords SET hashed_password = '$hashedPassword' WHERE email = '$email'";
    $stmt = $db->prepare($sql);
    $stmt->execute();
  }

