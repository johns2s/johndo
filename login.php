<?php

  include("header.php");

  if (!isset($_SESSION["user"])) {

?>

  <div class = "logsignWrap">
    <form class = "item" action = "logsign.php" method = "POST">
      <h3>Login</h3>

      <?php

      if (isset($_GET["message"])) {
        echo "<p class = 'message'><b>" . $_GET['message'] . "</b></p>";
      }

      else {
        echo "<p class = 'message'><i>Not registered yet? Signup <a href = 'signup.php'>here</a> for free.</i></p>";
      }

      ?>

      <input type = "email" class = "inputText" name = "emailInput" placeholder = "Email Address" required>
      <input type = "password" class = "inputText" name = "pwordInput" placeholder = "Password" required>
      <input type = "submit" class = "inputBtn" name = "submitLogin" value = "Login" required>
    </form>
  </div>

<?php

  include("footer.php");

  }

  else {
    header("location: index.php");
  }

?>
