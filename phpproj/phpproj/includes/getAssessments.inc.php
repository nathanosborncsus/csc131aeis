<?php

session_start();



if (isset($_POST['submit'])) {
  include 'db.inc.php';

  $sql = "SELECT * FROM assessments WHERE report_owner='$_SESSION["u_id"]'";
  $result = mysqli_query($conn, $sql);
  $resultCheck = mysqli_num_rows($result);

  if ($resultCheck < 1) {
    header("Location: ../dashboard.php?assessments=empty");
    exit();
  } else {
    if ($row = mysqli_fetch_assoc($result)) {

      }
    }
  }
}
