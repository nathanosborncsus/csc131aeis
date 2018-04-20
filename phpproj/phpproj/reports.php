<?php
  include_once 'header.php';
?>

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
