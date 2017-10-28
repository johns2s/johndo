<?php

include("config.php");

if (isset($_SESSION["user"])) {
  if (isset($_POST["submitNew"])) {
    $title = mysqli_real_escape_string($conn, $_POST["titleInput"]);
    $more = mysqli_real_escape_string($conn, $_POST["moreInput"]);
    if ($more == "") {
      $more = ". . . . .";
    }
    $deadlineOrig = mysqli_real_escape_string($conn, $_POST["dateInput"]);
    $deadline = strtotime($deadlineOrig);
    if ($deadline === false) {
      $deadline = "unknown";
    }
    $user = $_SESSION["userID"];
		$sql = "INSERT INTO tasks set title = '$title', more = '$more', date = '$deadline', user = '$user'";
		if (mysqli_query($conn, $sql)) {
		    header("location: index.php");
		}
    else {
  			header("location: newtask.php?message=A+database+error+occured+while+adding+your+task.");
  	}
  }

  else if (isset($_POST["submitUpdate"])) {
    $title = mysqli_real_escape_string($conn, $_POST["titleInput"]);
    $taskID = mysqli_real_escape_string($conn, $_POST["taskID"]);
    $more = mysqli_real_escape_string($conn, $_POST["moreInput"]);
    if ($more == "") {
      $more = ". . . . .";
    }
    $deadlineOrig = mysqli_real_escape_string($conn, $_POST["dateInput"]);
    $deadline = strtotime($deadlineOrig);
    if ($deadline === false) {
      $deadline = "unknown";
    }
    $user = $_SESSION["userID"];
    $sql = "UPDATE tasks set title = '$title', more = '$more', date = '$deadline' WHERE id = '$taskID' AND user = '$user'";
    if (mysqli_query($conn, $sql)) {
        header("location: index.php");
    }
    else {
        header("location: newtask.php?message=A+database+error+occured+while+updating+your+task.You+may+not+be+allowed+to+edit+this+task.");
    }
  }

  else if (isset($_GET["delete"]) && isset($_GET["id"])) {
    $id = $_GET["id"];
    $userID = $_SESSION["userID"];
    $sql = "DELETE FROM tasks WHERE id = '$id' AND user = '$userID'";
  	if (mysqli_query($conn, $sql)) {
  		header("location: index.php");
    }
  	else {
      header("location: index.php?message=Error+deleting+task.");
    }
  }

  else {
    header("location: index.php");
  }

}

else {
  header("location: index.php");
}

mysqli_close($conn);
