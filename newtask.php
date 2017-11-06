<?php

  include("header.php");

  if (isset($_SESSION["user"])) {

    if (isset($_POST["submitNewTitle"])) {
      $taskTitle = $_POST["titleInput"];
    }

    else {
      $taskTitle = "";
    }

?>

<h2 class = "headline">Today is <?php echo date("l, F dS (m/d/Y)") ?></h2>

  <div class = "logsignWrap">
    <form class = "item" action = "process.php" method = "POST">
      <input type = "hidden" name = "token" value = "<?php echo $_SESSION["userToken"]; ?>">
      <h3>Add New Task</h3>

      <?php

      if (isset($_GET["message"])) {
        echo "<p class = 'message'><b>" . htmlentities($_GET['message']) . "</b></p>";
      }

      ?>
      <input type = "text" class = "inputText" name = "titleInput" maxlength = "75" placeholder = "Task Title" value = "<?php echo htmlentities($taskTitle); ?>" required>
      <input type = "date" class = "inputText" name = "dateInput" minlength = "5" maxlength = "10" placeholder = "Deadline (MM/DD/YYYY)" required>
      <textarea class = "inputTextBig" name = "moreInput"  placeholder = "More Info"></textarea>
      <input type = "submit" class = "inputBtn" name = "submitNew" value = "Add" required>
      <p style = "margin-top: 15px;">*You can also type in a day name (ex. Monday) only. If you use dashes, johnDo assumes that you are using the DD-MM-YYYY format (MM/DD/YYYY with slashes).</p>
    </form>
    drop - month drop - day text (4) year combi @ process
  </div>

<?php

  include("footer.php");

  }

  else {
    header("location: index.php");
  }

?>
