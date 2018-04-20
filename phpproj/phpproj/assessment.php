<?php
  include_once 'header.php';
?>
<script src = "main.js"></script>


<body onload="hideAll()">

        <!-- Handles webpage color outside of the questionnaire form.
        Questionnaire form remains white for distinction. -->
        <body style="background-color:#dddddd;">

<section class="main-container">
            <div class="main-wrapper">
              <!-- <h2>New Assessment</h2> -->
            </div>



        <!-- I had to remove 'form class=options' that was around every
        question and replace it with form class="questionnaire-form".
        This was so I was able to have each question handled
        as a single form when styling. If this affects anything functionally,
        give me a heads up. -->
            <form class="questionnaire-form">

<div.a>
  <div class="custheader"
        <h1> <center>Accessibility Exposure & Impact </center> </h1><br><br>
  </div>
        <h2></h2>

        <!--Had to put img id instead of img-src, because on main.js I had
        to make a changeImage function (called on button press) to change
        src each time -->
  <center>          <img id="prog-bar" src="https://puu.sh/zRygn/b75091f1f1.png"> </center><br>



        <br><br>
        <!--  Commenting out Buttons for visibility but
        keeping these lines for debugging purposes
        <button onclick="hide()">Hide</button>
        <button onclick="show()">Show</button>
        -->


</div.a>

<div class="questions" id="question1">
    <p>1. Does the system provide a service that is essential to the general public?</p><br><br>

        <input class="option" type="radio" name="question1" value=1>Yes<br>
        <input class="option" type="radio" name="question1" value=0>No<br>
		<br><br><button type='button' id="nextBtn" onclick="nextQuestion(1), changeImage(1)">Next</button>


</div>

<div class="questions" id="question2">
    <p>2. Are citizens required to use the system to gain access to a service?</p><br>

        <input class="option" type="radio" name="question2" value=1>Yes<br>
        <input class="option" type="radio" name="question2" value=0>No<br>
        <br><br><button type='button' id="prevBtn" onclick="prevQuestion(1), changeImage(1)">Previous</button>
        <button type='button' id="nextBtn" onclick="nextQuestion(2), changeImage(2)">Next</button>


</div>


<div class="questions" id="question3">
    <p>3. Is the system the only or most efficient option for accessing the public service or information in a timely manner?</p>

        <input class="option" type="radio" name="question3" value=1>Yes<br>
        <input class="option" type="radio" name="question3" value=0>No<br>
		<br><br><button type='button' id="prevBtn" onclick="prevQuestion(2), changeImage(2)">Previous</button>
    <button type='button' id="nextBtn" onclick="nextQuestion(3), changeImage(3)">Next</button>


</div>


<div class="questions" id="question4">
    <p>4. If a citizen is unable to use the service that the system provides, does it prevent or reduce access to another service (i.e. travel, healthcare, etc.)?</p>

        <input class="option" type="radio" name="question4" value=1>Yes<br>
        <input class="option" type="radio" name="question4" value=0>No<br>
		<br><br><button type='button' id="prevBtn" onclick="prevQuestion(3), changeImage(3)">Previous</button>
    <button type='button' id="nextBtn" onclick="nextQuestion(4), changeImage(4)">Next</button>

</div>


<div class="questions" id="question5">
    <p>5. In addition to internal staff, are employees from other State departments required to use or interface with the system?</p>

        <input class="option" type="radio" name="question5" value=1>Yes<br>
        <input class="option" type="radio" name="question5" value=0>No<br>
		<br><br><button type='button' id="prevBtn" onclick="prevQuestion(4), changeImage(4)">Previous</button>
    <button type='button' id="nextBtn" onclick="nextQuestion(5), changeImage(5)">Next</button>


</div>


<div class="questions" id="question6">
    <p>6. Would accessibility issues prevent an employee from performing essential job functions?</p>

        <input class="option" type="radio" name="question6" value=1>Yes<br>
        <input class="option" type="radio" name="question6" value=0>No<br>
		<br><br><button type='button' id="prevBtn" onclick="prevQuestion(5), changeImage(5)">Previous</button>
    <button type='button' id="nextBtn" onclick="nextQuestion(6), changeImage(6)">Next</button>


</div>


<div class="questions" id="question7.1">
    <p>7.1. Has the system been tested for accessibility since the last major release?</p>

        <input class="option" type="radio" name="question7.1" value=1>Yes<br>
        <input class="option" type="radio" name="question7.1" value=0>No<br>
		<br><br><button type='button' id="prevBtn" onclick="prevQuestion(6), changeImage(6)">Previous</button>
    <button type='button' id="nextBtn" onclick="nextQuestion(7), changeImage(7)">Next</button>


</div>


<div class="questions" id="question7.2">
    <p>7.2. If so, were any findings of accessibility issues not addressed?</p>

        <input class="option" type="radio" name="question7.2" value=1>Yes<br>
        <input class="option" type="radio" name="question7.2" value=0>No<br>
		<br><br><button type='button' id="prevBtn" onclick="prevQuestion(7), changeImage(7)">Previous</button>
    <button type='button' id="nextBtn" onclick="nextQuestion(8), changeImage(8)">Next</button>


</div>


<div class="questions" id="question8">
    <p>8. Are there any plans for the system to be retired or replaced?</p>

        <input class="option" type="radio" name="question8" value=1>Yes<br>
        <input class="option" type="radio" name="question8" value=0>No<br>
		<br><br><button type='button' id="prevBtn" onclick="prevQuestion(8), changeImage(8)">Previous</button>
    <button type='button' id="nextBtn" onclick="nextQuestion(9), changeImage(9)">Next</button>


</div>


<div class="questions" id="question9">
    <p>9. Is there a State or Federal mandate that requires this system or an equivalent system to be readily available?</p>

        <input class="option" type="radio" name="question9" value=1>Yes<br>
        <input class="option" type="radio" name="question9" value=0>No<br>
		<br><br><button type='button' id="prevBtn" onclick="prevQuestion(9), changeImage(9)">Previous</button>
    <button id="subBtn" onclick="submitAnswers(), changeImage(10)">Submit</button>



</div>
</form>
    <br>

  </section>
<?php
include_once 'footer.php';
?>
