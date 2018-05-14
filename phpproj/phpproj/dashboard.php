<?php
  include_once 'header.php';
  include_once 'includes/db.inc.php';
?>



<form class="questionnaire-form">




    <?php
      if (isset($_SESSION['u_id'])) {
        echo "<center><h2><br>Welcome"; $uid = $_SESSION["u_id"];

        $sql = "SELECT * FROM assessments WHERE report_owner='$uid'";
        $result = mysqli_query($conn, $sql);
        $resultCheck = mysqli_num_rows($result);

        $count = 0;

        if($resultCheck > 0) { echo" back, "; }else echo ", " ;
        echo $_SESSION['u_first'] . ' ' . $_SESSION['u_last'];
        echo "!</center></h2><br><br><br>";
      }

      else{
        echo "Error: You must be logged in to access this page.";
      }
     ?>


<table>

<tr>
<td colspan="4" style="background: linear-gradient(#aaa, #fff);"<h1>Previously Submitted Assessments</h1></td>
</tr>

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
          echo "<td>" . $row['report_name'] . "</td>";

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
          $report_ID = $row['report_ID'];

          if($question1 == 1){
            $score += 80;
          }
          else {
            $score += 5;
          }
          if($question2 == 1){
            $score += 80;
          }
          else {
            $score += 5;
          }
          if($question3 == 1){
            $score += 60;
          }
          else {
            $score += 5;
          }
          if($question4 == 1){
            $score += 80;
          }
          else {
            $score += 5;
          }
          if($question5 == 1){
            $score += 60;
          }
          else {
            $score += 5;
          }
          if($question6 == 1){
            $score += 40;
          }
          else {
            $score += 5;
          }
          if($question7 == -1){
            $score += 180;      //200 - 20 because question 8 (7.2) defaults to -1
          }
          if($question8 == 1){
            $score += 80;
          }
          else {
            $score += 20;
          }
          if($question9 == 1){
            $score += 5;
          }
          else {
            $score += 250;
          }
          if($question10 == 1){
            $score += 50;
          }
          else {
            $score += 5;
          }
          $score = ($score * 100)/900;
          $score = round($score);




          echo "<td>$score</td>";
          echo "<td>" . $row['dt_created'] . "</td>";
          echo "<td><button onclick='changeToInactive($report_ID)'>Delete</button></td>";

          echo "</tr>";
          $count = $count + 1;
        }

      }
      echo "</table>";
    }
    else{
      echo "<tr>";
      echo "<td><td></td></td><td></td><td></td>";


      echo "</tr>";
      echo "</table> <br> <br> <center>You currently have no previously submitted assessments. Please select 'New Assessment' on the navigation menu.</center>";
    }

   ?>





<script>
function changeToInactive(report_ID) {

  var params = "reportID=" + encodeURIComponent(report_ID);
  //alert(params);

  // Create XHR Object
  var xhr = new XMLHttpRequest();
  //console.log(xhr);  //use this to see data
  // OPEN - type, url/filename, async
  xhr.open('POST', 'includes/deleteReport.inc.php', true);
  // This next line is because of POST type
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

  xhr.onload = function() {
     if(this.status == 200) {
        alert("Assessment Deleted Successfully");
     }
   }

  xhr.onerror = function() {
    console.log('Request Error...');

  }
  //var u_id = "<php? echo $_SESSION['u_id'] ?>";

  //sends request
  xhr.send(params);

  //alert("Done");

}

</script>

 </form>

<?php
include_once 'footer.php';
?>
