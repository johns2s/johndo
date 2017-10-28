<?php

  include("header.php");

  if (isset($_SESSION["user"])) {

    $userID = $_SESSION["userID"];

?>

  <div class = "logsignWrap">
    <form class = "item" action = "process.php" method = "POST">
      <h3>Settings</h3>

      <?php

      if (isset($_GET["message"])) {
        echo "<p class = 'message'><b>" . $_GET['message'] . "</b></p>";
      }

      ?>
      <input type = "password" class = "inputText" name = "passwordChangeInput" value = "" placeholder = "New Password">
      <input type = "submit" class = "inputBtn" name = "submitUpdateSettingsPW" value = "Change Password" required>
    </form>
  </div>

<?php

  include("footer.php");

  }

  else {
    header("location: index.php");
  }

?>
