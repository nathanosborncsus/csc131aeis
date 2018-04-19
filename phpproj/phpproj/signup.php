<?php
  include_once 'header.php';
?>

<section class="main-container">
  <div class="main-wrapper">
    <h2>Sign up for an AEIS portal</h2>
    <form class="signup-form" action="includes/signup.inc.php" method="POST">
      <input type="text" name="first_name" placeholder="First Name">
      <input type="text" name="last_name" placeholder="Last Name">
      <input type="text" name="email" placeholder="Email Address">
      <input type="password" id="password" name="pwd" onkeyup='check();' placeholder="Password">
      <input type="password" id="confirm_password" name="cpwd" onkeyup='check();' placeholder="Confirm Password">
      <span id='message'></span>
      <button type="submit" name="submit">Sign up</button>
    </form>
  </div>
</section>

<script>
var check = function() {
  if (document.getElementById('password').value ==
    document.getElementById('confirm_password').value) {
    document.getElementById('message').style.color = 'green';
    document.getElementById('message').innerHTML = 'matching';
  } else {
    document.getElementById('message').style.color = 'red';
    document.getElementById('message').innerHTML = 'not matching';
  }
}
</script>

<?php
include_once 'footer.php';
?>
