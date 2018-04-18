<?php
session_start();
 ?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <title></title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
</head>
<body>
<header>
  <nav>
    <div class="main-wrapper">
      <ul>
        <li><a href="index.php">Dashboard</a></li>
      </ul>
      <ul>
        <li><a href="assessment.php">New Assessment</a></li>
      </ul>
      <div class="nav-login">
        <?php
            if (isset($_SESSION['u_id'])) {
              echo '<form action="includes/logout.inc.php" method="POST">
                  <button type="submit" name="submit">Logout</button>
              </form>';
            } else {
              echo '<form action="includes/login.inc.php" method="POST">
                <input type="text" name="email" placeholder="email">
                <input type="password" name="pwd" placeholder="password">
                <button type="submit" name="submit">Login</button>
              </form>
              <a href="signup.php">Sign up</a>';
            }
          ?>

      </div>
    </div>
  </nav>
</header>
