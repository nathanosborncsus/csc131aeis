<?php

session_start();



if (isset($_POST['submit'])) {
  include 'db.inc.php';

  $email = mysqli_real_escape_string($conn, $_POST['email']);
  $pwd = mysqli_real_escape_string($conn, $_POST['pwd']);

  //Error Handlers
  //Check if inoputs are empty
  if (empty($email) || empty($pwd)) {
    header("Location: ../index.php?login=empty");
    exit();
  } else {
    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $sql);
    $resultCheck = mysqli_num_rows($result);
    if ($resultCheck < 1) {
      header("Location: ../index.php?login=error");
      exit();
    } else {
      if ($row = mysqli_fetch_assoc($result)) {
        //De-Hashing the passwordHash
        $salt = 'k*jJlrsH:cY]O^Z^/J2)Pz{)qz:+yCa]^+V0S98Zf$sV[c@hKKG07Q{utg%OlODS';

        // hash salt and password together
        $hashedPwd = md5($salt . $pwd);
        $savedPwd = $row['passwordHash'];
        $hashedPwdCheck = ($row['passwordHash'] == $hashedPwd);
        if ($hashedPwdCheck == false) {
          header("Location: ../index.php?login=errorP". $hashedPwd. "?" . $savedPwd);
          exit();
        } elseif ($hashedPwdCheck == true) {
          //Log in user here
          $_SESSION['u_first'] = $row['firstName'];
          $_SESSION['u_last'] = $row['lastName'];
          $_SESSION['u_email'] = $row['email'];
          $_SESSION['u_id'] = $row['user_id'];
          header("Location: ../dashboard.php");
          exit();
        }
      }
    }
  }
}
