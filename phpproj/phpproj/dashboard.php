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
    <th>Date Submitted</th>
    <th>Assessment Name</th>
    <th>Total Score</th>
    <th>Action</th>
  </tr>

  <tr>
    <td>01/05/2018 09:31AM</td>
    <td>System 1</td>
    <td>54</td>
    <td> VIEW | DELETE</td>
  </tr>

  <tr>
    <td>03/11/2018 04:21PM</td>
    <td>System 2</td>
    <td>70</td>
    <td> VIEW | DELETE</td>
  </tr>

  <tr>
    <td>04/15/2018 11:11AM</td>
    <td>System 3</td>
    <td>44</td>
    <td> VIEW | DELETE</td>
  </tr>






 </form>

<?php
include_once 'footer.php';
?>
