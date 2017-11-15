<?php

  include("header.php");

  if (isset($_SESSION["user"]) && isset($_GET["id"])) {

    $taskID = $_GET["id"];
    $userID = $_SESSION["userID"];

    $sql = "SELECT * FROM tasks WHERE id = '$taskID' AND user = '$userID'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
      while($row = mysqli_fetch_assoc($result)) {
        if ($row["date"] == "unknown") {
          $taskDate = $row["date"];
        }
        else {
          $taskDate = date("Y-m-d", $row["date"]); /* when date supported change to yyyy-mm-dd */
        }
        $taskTitle = $row["title"];
        $taskMore = $row["more"];
      }
    }

    else {
      header("location: index.php");
    }

?>

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
      <label class = "formLabel">Deadline</label>
      <input type = "date" class = "inputText" name = "dateInput" minlength = "5" maxlength = "10" placeholder = "Deadline (MM/DD/YYYY)" value = "<?php echo htmlentities($taskDate); ?>" required>
      <input type = "hidden" name = "taskID" value = "<?php echo htmlentities($taskID); ?>">
      <label class = "formLabel">More Info</label>
      <textarea class = "inputTextBig" name = "moreInput"  placeholder = "More Info"><?php echo htmlentities($taskMore); ?></textarea>
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
