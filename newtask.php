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
      <label class = "formLabel">Task Name</label>
      <input type = "text" class = "inputText" name = "titleInput" maxlength = "75" placeholder = "Task Title" value = "<?php echo htmlentities($taskTitle); ?>" required>

      <input id="once" style="display: none" name="recurring" value="once" type="radio" checked>
      <input id="repeat" style="display: none" name="recurring" value="repeat" type="radio">

      <label class = "formLabel">Recurring?</label>
      <div class = "radioWrap">
        <label for="once" id = "onceLabel" class="radioLabel">One Time</label>
        <label for="repeat" id = "repeatLabel" class="radioLabel">Repeat</label>
      </div>

      <div id = "onceD">
        <label class = "formLabel">Deadline</label>
        <input type = "date" class = "inputText" name = "dateInput" minlength = "5" maxlength = "10" placeholder = "Deadline (MM/DD/YYYY)" required>
      </div>
      <div id = "repeatD">
        <label class = "formLabel">Frequency (days)</label>
        <input type = "number" class = "inputText" name = "freqInput" min = "1" max = "20" placeholder = "Repeat every x days" required>
        <label class = "formLabel">Start date</label>
        <input type = "date" class = "inputText" name = "startInput" placeholder = "Start date (MM/DD/YYYY)" required>
        <label class = "formLabel">End date</label>
        <input type = "date" class = "inputText" name = "endInput" placeholder = "End date (MM/DD/YYYY)" required>
      </div>

      <label class = "formLabel">More Info</label>
      <textarea class = "inputTextBig" name = "moreInput"  placeholder = "More Info"></textarea>
      <input type = "submit" class = "inputBtn" name = "submitNew" value = "Add" required>
    </form>
  </div>

<?php

  include("footer.php");

  }

  else {
    header("location: index.php");
  }

?>
