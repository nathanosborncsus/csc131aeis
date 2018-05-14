<?php

if (isset($_POST['reportID'])) {
  include_once 'db.inc.php';

  $report_ID = mysqli_real_escape_string($conn, $_POST['reportID']);

//Error handling for empty form value
  if (empty($report_ID)) {
        exit(1);
  } else {
          $sql = "UPDATE assessments SET active=-1, dt_deleted= CURRENT_TIMESTAMP WHERE report_ID='$report_ID'";
          mysqli_query($conn, $sql);
          exit();

        }


} else {
  header("Location: ../dashboard.php");
  exit();
}
