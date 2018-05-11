<?php
  include_once 'header.php';
?>

<?php

/*

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
*/

<script type="text/javascript">
window.onload = function () {
  var chart = new CanvasJS.Chart("chartContainer",
  {
    title:{
      text: "AEIS System Inventory"
    },
  legend: {
  horizontalAlign: "left", // left, center ,right
  verticalAlign: "center",  // top, center, bottom
  },
  axisY: {
      title: "Scoretable",
      maximum: 33
    },
    data: [
    {
      type: "bar",
  showInLegend: true,
  legendText: "Public Services",
      dataPoints: [
      { y: 33, label: "Health Institute"},
      { y: 25, label: "Fortnight Construction"},
      { y: 33, label: "Sayden's Steeline"},
      { y: 25, label: "Typhoon Industries"},
      { y: 19, label: "SHODAN"},
      { y: 10, label: "50 Blessings Account"},
  { y: 25, label: "Enclave University"}
      ]
    },
    {
      type: "bar",
  showInLegend: true,
      legendText: "Internal Services",
  dataPoints: [
      { y: 11, label: "Health Institute"},
      { y: 11, label: "Fortnight Construction"},
      { y: 11, label: "Sayden's Steeline"},
      { y: 11, label: "Typhoon Industries"},
      { y: 6, label: "SHODAN"},
      { y: 1, label: "50 Blessings Account"},
  { y: 11, label: "Enclave University"}
      ]
    },
    {
      type: "bar",
  showInLegend: true,
  legendText: "Accessibility",
      dataPoints: [
      { y: 31, label: "Health Institute"},
      { y: 22, label: "Fortnight Construction"},
      { y: 22, label: "Sayden's Steeline"},
      { y: 22, label: "Typhoon Industries"},
      { y: 2, label: "SHODAN"},
      { y: 9, label: "50 Blessings Account"},
  { y: 31, label: "Enclave University"}
      ]
    },
  {
      type: "bar",
  showInLegend: true,
  legendText: "Life Span",
      dataPoints: [
      { y: 25, label: "Health Institute"},
      { y: 12, label: "Fortnight Construction"},
      { y: 25, label: "Sayden's Steeline"},
      { y: 25, label: "Typhoon Industries"},
      { y: 0, label: "SHODAN"},
      { y: 12, label: "50 Blessings Account"},
  { y: 25, label: "Enclave University"}
      ]
    }
    ]
  });

chart.render();
}
</script>
<script type="text/javascript" src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
<div id="chartContainer" style="height: 600px; width: 75%;">
</div>


<?php
include_once 'footer.php';
?>
