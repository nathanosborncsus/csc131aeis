<?php
  include_once 'header.php';
?>


  <div class="SettingsWindow">

<h2><center><u>Edit User Information</u></center></h2>
<br><br>

<div class="assess-styledcontainer2">
<div class="row">
  <div class="column" style="background-color:#ggg;" style="column-width: 50px;" >

        First Name:
        <?php echo "<br>";  ?>
        <?php echo "<br>";  ?>
        <?php echo "<br>";  ?>
        <?php echo "<br>";  ?>
        Last Name:
        </div>

  <div class="column" style="background-color:#ggg;">
 <input type="text" name="first_name" placeholder="<?php echo $_SESSION['u_first']; ?>">
        <?php echo "<br>";  ?>
        <?php echo "<br>";  ?>
        <?php echo "<br>";  ?>
        <?php echo "<br>";  ?>
<input type="text" name="last_name" placeholder="<?php echo $_SESSION['u_last'];?>">
  </div>

  <div class="column" style="background-color:#ggg;" style="column-width: 50px;">
E-mail:
        <?php echo "<br>";  ?>
        <?php echo "<br>";  ?>
        <?php echo "<br>";  ?>
        <?php echo "<br>";  ?>
Change Password:
  </div>

  <div class="column" style="background-color:#ggg;" style="column-width: 20px;" style="padding:padding-top">
            <input type="text" name="email" disabled="disabled" placeholder="<?php echo $_SESSION['u_email']; ?>"> Cannot be changed.
        <?php echo "<br>";  ?>
        <?php echo "<br>";  ?>
        <?php echo "<br>";  ?>
        <?php echo "<br>";  ?>
        <input type="password" id="password" name="pwd" onkeyup='check();' placeholder="Current password" style="padding:2px;"><br>
        <input type="password" id="confirm_password" name="cpwd" onkeyup='check();' placeholder="Enter new password" style="padding:2px;"><br>
        <input type="password" id="confirm_password" name="cpwd2" onkeyup='check();' placeholder="Confirm new password" style="padding:2px;">
  </div>

<br><br><br>


</div>

</div>

<br><br>
              <center><button type="submit" name="savechanges" style="border: 1px solid #e6ac00; border-radius:5px; background: linear-gradient(#ffbf00, #ffd24d); font-family: arial; font-size: 14px; color: #262626; cursor: pointer;">Save User Settings</button><center>


  </div>


<script>

/** Enter Needed Script Info **/

</script>



<?php
include_once 'footer.php';
?>
