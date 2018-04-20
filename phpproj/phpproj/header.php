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


    <!-- Beginning Division for the styling of the HEADER around
        its menu items.-->
    <div class="main-wrapper">


    <!-- A division (buttondiff) to insert a PHP statement that indicates if the
  user is logged in, it'll show a "New Assessment" and "Dashboard" button.
  If the user isn't logged in, it just shows a "home" button.-->
      <div class="buttondiff">
        <?php
            if (isset($_SESSION['u_id'])) {
              echo '<ul>
                      <li><a href="dashboard.php">Dashboard</a></li>
                    </ul>
                    <ul>
                      <li><a href="assessment.php">New Assessment</a></li>
                    </ul>
                    <ul>
                      <li><a href="reports.php">Reports</a></li>
                    </ul>
                    <ul>
                        <li><a href="settings.php">User Settings</a></li>
                    </ul>';
            } else {
              echo '      <ul>
                      <li><a href="index.php">Home</a></li>
                    </ul>';
            }
          ?>


      <!-- A division (nav-login) to hide login form and return a logout around
      settings button once user is logged in.-->
      <div class="nav-login">
        <?php
            if (isset($_SESSION['u_id'])) {
              echo '<form action="includes/logout.inc.php" method="POST">
                  <button type="submit" name="submit">Logout</button>
                  </form>';
            } else {
              echo '<form action="includes/login.inc.php" method="POST">
                <input type="text" name="email" placeholder="Email">
                <input type="password" name="pwd" placeholder="Password">
                <button type="submit" name="submit">Login</button>
            </form>
              <a href="signup.php">Sign up</a>';
            }
          ?>

      </div>
    </div>
  </nav>
</header>
