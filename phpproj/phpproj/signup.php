<?php
  include_once 'header.php';
?>

<section class="main-container">
  <div class="questionnaire-form">
    <h2>Sign up to access the AEIS Portal</h2>

  </div>
    <form class="signup-form" action="includes/signup.inc.php" method="POST">
      <input type="text" name="first_name" placeholder="First Name" style="border: 1px solid black; border-radius: 4px;   background-color: #ffffa8; color: #111;">
      <input type="text" name="last_name" placeholder="Last Name"style="border: 1px solid black; border-radius: 4px;   background-color: #ffffa8; color: #111;">
      <input type="text" name="email" placeholder="Email Address" style="border: 1px solid black; border-radius: 4px;   background-color: #ffffa8; color: #111;">
      <input type="password" id="password" name="pwd" onkeyup='check();' placeholder="Password" style="border: 1px solid black; border-radius: 4px;   background-color: #ffffa8; color: #111;">
      <input type="password" id="confirm_password" name="cpwd" onkeyup='check();' placeholder="Confirm Password" style="border: 1px solid black; border-radius: 4px;   background-color: #ffffa8; color: #111;">
      <span id='message'></span>
      <br></br>
      <button type="submit" name="submit" >Sign up</button>
    </form>

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
