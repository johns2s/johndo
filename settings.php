<?php

  include("header.php");

  if (isset($_SESSION["user"])) {

    $userID = $_SESSION["userID"];

?>

<h2 class = "headline">Today is <?php echo date("l, F dS (m/d/Y)") ?></h2>

  <div class = "logsignWrap">
    <form class = "item" action = "process.php" method = "POST">
      <h3>Settings</h3>

      <?php

      if (isset($_GET["message"])) {
        echo "<p class = 'message'><b>" . $_GET['message'] . "</b></p>";
      }

      ?>
      <input type = "text" class = "inputText" name = "passwordChangeInput" maxlength = "75" placeholder = "New Password">
      <input type = "hidden" name = "taskID" value = "<?php echo htmlentities($taskID); ?>">
      <input type = "submit" class = "inputBtn" name = "submitUpdate" value = "Update" required>
    </form>
  </div>

<?php

  include("footer.php");

  }

  else {
    header("location: index.php");
  }

?>
