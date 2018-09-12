<?php

  include("header.php");

  if (isset($_SESSION["user"]) && isset($_GET["template"])) {

    $taskID = $_GET["template"];
    $userID = $_SESSION["userID"];

    $sql = "SELECT * FROM tasks WHERE template = '$taskID' AND user = '$userID'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
      while($row = mysqli_fetch_assoc($result)) {
        $taskDate = date("Y-m-d", $row["date"]);

        if ($row["endDate"] == False) {
          $endDateOrig = date("Y-m-d", $row["date"]);
          $endDate = date('Y-m-d', strtotime($endDateOrig . ' + 7 days'));
        }
        else {
          $endDate = date("Y-m-d", $row["endDate"]);
        }

        if ($row["freq"] != False) {
          $freq = $row["freq"];
        }
        else {
          $freq = 7;
        }

        $taskTitle = $row["title"];
        $taskMore = $row["more"];
      }
    }

    else {
      header("location: index.php");
    }

?>

<script>
  function alertDelete() {
    var answer = confirm("Are you sure you are finished with this task?")
    if (answer)
      window.location = "process.php?delete&id=<?php echo $taskID ?>&token=<?php echo $_SESSION['userToken'] ?>"
  }
</script>

<h2 class = "headline">Today is <?php echo date("l, F dS (m/d/Y)") ?></h2>

  <div class = "logsignWrap">
    <form class = "item" action = "process.php" method = "POST">
      <input type = "hidden" name = "token" value = "<?php echo $_SESSION["userToken"]; ?>">
      <h3>Edit Task</h3>

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

      <div id = "onceD" class = "sub">
        <label class = "formLabel">Deadline</label>
        <input value = "<?php echo htmlentities($taskDate); ?>" type = "date" class = "inputText" name = "dateInput" minlength = "5" maxlength = "10" placeholder = "Deadline (MM/DD/YYYY)" required>
      </div>
      <div id = "repeatD" class = "sub">
        <label class = "formLabel">Frequency (days)</label>
        <input value = "<?php echo htmlentities($freq); ?>" type = "number" class = "inputText" name = "freqInput" min = "1" max = "20" placeholder = "Repeat every x days" required>
        <label class = "formLabel">Start date</label>
        <input value = "<?php echo htmlentities($taskDate); ?>" type = "date" class = "inputText" name = "startInput" placeholder = "Start date (MM/DD/YYYY)" required>
        <label class = "formLabel">End date</label>
        <input value = "<?php echo htmlentities($endDate); ?>" type = "date" class = "inputText" name = "endInput" placeholder = "End date (MM/DD/YYYY)" required>
      </div>

      <input type = "hidden" name = "taskID" value = "<?php echo htmlentities($taskID); ?>">
      <label class = "formLabel">More Info</label>
      <textarea class = "inputTextBig" name = "moreInput"  placeholder = "More Info"><?php echo htmlentities($taskMore); ?></textarea>
      <input type = "submit" style = "margin-bottom: 15px" class = "inputBtn" name = "submitUpdate" value = "Update" required>
      <a href = "javascript:alertDelete()" class = "inputBtn" style = "display: block; background-color: #1BAF5B">Finished</a>
    </form>
  </div>

<?php

  include("footer.php");

  }

  else {
    header("location: index.php");
  }

?>
