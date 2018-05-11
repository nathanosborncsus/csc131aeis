<?php
  include_once 'header.php';
  include_once 'includes/db.inc.php';
?>



<form class="questionnaire-form">




    <?php
      if (isset($_SESSION['u_id'])) {
        echo "<h2>Welcome back, ";
        echo $_SESSION['u_first'] . ' ' . $_SESSION['u_last'];
        echo "!</h2><br><br>";
      }

      else{
        echo "Error: You must be logged in to access this page.";
      }
     ?>


<table>

  <tr>
    <th>Assessment Name</th>
    <th>Total Score</th>
    <th>Date Submitted</th>
    <th>Action</th>
  </tr>

  <?php

    $uid = $_SESSION["u_id"];

    $sql = "SELECT * FROM assessments WHERE report_owner='$uid'";
    $result = mysqli_query($conn, $sql);
    $resultCheck = mysqli_num_rows($result);

    $count = 0;

    if($resultCheck > 0) {
      while (($row = mysqli_fetch_assoc($result)) && ($count < 10)) {
        if ($row['active'] > 0) {
          echo "<tr>";
          echo "<th>" . $row['report_name'] . "</th>";

          $score = 0;
          $question1 = $row['question_1'];
          $question2 = $row['question_2'];
          $question3 = $row['question_3'];
          $question4 = $row['question_4'];
          $question5 = $row['question_5'];
          $question6 = $row['question_6'];
          $question7 = $row['question_7'];
          $question8 = $row['question_8'];
          $question9 = $row['question_9'];
          $question10 = $row['question_10'];



          echo "<th> 100 </th>";
          echo "<th>" . $row['dt_created'] . "</th>";
          echo "<th> Delete </th>"; //this need to be a link that will send a request to move 'active' to 0

          echo "</tr>";
          $count = $count + 1;
        }
      }
    }

   ?>



</table>


 </form>

<?php
include_once 'footer.php';
?>
