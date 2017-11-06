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
          $taskDate = date("m/d/Y", $row["date"]); /* when date supported change to yyyy-mm-dd */
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
      <input type = "text" class = "inputText" name = "titleInput" maxlength = "75" placeholder = "Task Title" value = "<?php echo htmlentities($taskTitle); ?>" required>
      <input type = "date" class = "inputText" name = "dateInput" minlength = "5" maxlength = "10" placeholder = "Deadline (MM/DD/YYYY)" value = "<?php echo htmlentities($taskDate); ?>" required>
      <input type = "hidden" name = "taskID" value = "<?php echo htmlentities($taskID); ?>">
      <textarea class = "inputTextBig" name = "moreInput"  placeholder = "More Info"><?php echo htmlentities($taskMore); ?></textarea>
      <input type = "submit" class = "inputBtn" name = "submitUpdate" value = "Update" required>
      <p style = "margin-top: 15px;">*You can also type in a day name (ex. Monday) only. If you use dashes, johnDo assumes that you are using the DD-MM-YYYY format (MM/DD/YYYY with slashes).</p>
    </form>
  </div>

<?php

  include("footer.php");

  }

  else {
    header("location: index.php");
  }

?>
