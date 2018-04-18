<?php

if (isset($_POST['submit'])) {
  include_once 'db.inc.php';

  echo 'Connected successfully.';

  $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
  $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
  $email = mysqli_real_escape_string($conn, $_POST['email']);
  $pwd = mysqli_real_escape_string($conn, $_POST['pwd']);

  echo 'Connected successfully.';

//Error handling for empty form value
  if (empty($first_name) ||
      empty($last_name) ||
      empty($email) ||
      empty($pwd)) {
        header("Location: ../signup.php?signup=missing");
        //returns user to signup page
        exit(1);
  } else {
    //check if input characters are valid
    if (!preg_match("/^[a-zA-Z]*$/", $first_name) ||
        !preg_match("/^[a-zA-Z]*$/", $last_name)
        ) {
          header("Location: ../signup.php?signup=nameinvalid");
          //returns user to signup page
          exit(1);
    } else {
      //Check if email is valid
      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: ../signup.php?signup=emailnotvalid");
        //returns user to signup page
        exit(1);
      } else {
        //check if email is already registered
        $sql = "SELECT * FROM users WHERE email='email'";
        $result = mysqli_query($conn, $sql);
        $resultCheck = mysqli_num_rows($result);

        if ($resultCheck > 0) {
          //returns user to signup page, throws error
          header("Location: ../signup.php?signup=emailexists");
          exit();
        } else {
          //hash password for security
          // add random characters - the salt
          $salt = 'k*jJlrsH:cY]O^Z^/J2)Pz{)qz:+yCa]^+V0S98Zf$sV[c@hKKG07Q{utg%OlODS';

          // hash salt and password together
          $hashedPwd = md5($salt . $pwd);

          //Inserting into database
          $sql = "INSERT INTO users (firstName, lastName,
            email, passwordHash) VALUES ('$first_name', '$last_name',
              '$email', '$hashedPwd');";

          mysqli_query($conn, $sql);
          header("Location: ../index.php?signup=success");
          exit();
        }
      }
    }
  }

} else {
  header("Location: ../signup.php");
  exit();
}
