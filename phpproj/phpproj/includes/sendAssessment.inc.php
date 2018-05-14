<?php

if (isset($_POST['name'])) {
  include_once 'db.inc.php';

  $assess_name = mysqli_real_escape_string($conn, $_POST['name']);
  $q1 = mysqli_real_escape_string($conn, $_POST['q1']);
  $q2 = mysqli_real_escape_string($conn, $_POST['q2']);
  $q3 = mysqli_real_escape_string($conn, $_POST['q3']);
  $q4 = mysqli_real_escape_string($conn, $_POST['q4']);
  $q5 = mysqli_real_escape_string($conn, $_POST['q5']);
  $q6 = mysqli_real_escape_string($conn, $_POST['q6']);
  $q7 = mysqli_real_escape_string($conn, $_POST['q7']);
  $q8 = mysqli_real_escape_string($conn, $_POST['q8']);
  $q9 = mysqli_real_escape_string($conn, $_POST['q9']);
  $q10 = mysqli_real_escape_string($conn, $_POST['q10']);
  $uid = mysqli_real_escape_string($conn, $_POST['uid']);



//Error handling for empty form value
  if (empty($assess_name) ||
      empty($q1) ||
      empty($q2) ||
      empty($q3) ||
      empty($q4) ||
      empty($q5) ||
      empty($q6) ||
      empty($q7) ||
      empty($q8) ||
      empty($q9) ||
      empty($q10)) {
        //header("Location: ../assessment.php?data=missing");
        //returns user to signup page
        exit(1);
  } else {
          $sql = "INSERT INTO assessments (report_name, question_1, question_2, question_3, question_4, question_5, question_6, question_7, question_8, question_9, question_10, report_owner) VALUES ('$assess_name', '$q1', '$q2', '$q3', '$q4', '$q5', '$q6', '$q7', '$q8', '$q9', '$q10', '$uid' );";

          mysqli_query($conn, $sql);
          //header("Location: ../assessment.php?signup=success");
          //echo 'Assessment has been successfully sent';
          exit();

        }


} else {
  header("Location: ../assessment.php");
  exit();
}
