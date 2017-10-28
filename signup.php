<?php

  include("header.php");

  if (!isset($_SESSION["user"])) {

?>

  <div class = "logsignWrap">
    <form class = "item" action = "logsign.php" method = "POST">
      <h3>Signup</h3>

      <?php

      if (isset($_GET["message"])) {
        echo "<p class = 'message'><b>" . $_GET['message'] . "</b></p>";
      }

      else {
        echo "<p class = 'message'><i>Registered already? Login <a href = 'login.php'>here</a>.</i></p>";
      }

      ?>

      <input type = "email" class = "inputText" name = "emailInput" placeholder = "Email Address" required>
      <input type = "password" class = "inputText" name = "passwordInput" placeholder = "Password" required>
      <input type = "submit" class = "inputBtn" name = "submitSignup" value = "Signup" required>
    </form>
  </div>

<?php

  include("footer.php");

  }

  else {
    header("location: index.php");
  }

?>
