<?php
include("config.php");
if (isset($_SESSION["user"])) {
  if (isset($_POST["submitNew"]) && isset($_POST["token"]) && $_POST["token"] == $_SESSION["userToken"]) {
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

  else if (isset($_POST["submitUpdate"]) && isset($_POST["token"]) && $_POST["token"] == $_SESSION["userToken"]) {
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

  else if (isset($_GET["delete"]) && isset($_GET["id"]) && isset($_GET["token"]) && $_GET["token"] == $_SESSION["userToken"]) {
    $id = mysqli_real_escape_string($conn, $_GET["id"]);
    $userID = $_SESSION["userID"];
    $sql = "DELETE FROM tasks WHERE id = '$id' AND user = '$userID'";
  	if (mysqli_query($conn, $sql)) {
  		header("location: index.php");
    }
  	else {
      header("location: index.php?message=Error+deleting+task.");
    }
  }

  else if (isset($_POST["submitUpdateSettingsPW"]) && isset($_POST["token"]) && $_POST["token"] == $_SESSION["userToken"]) {
    $passwordText = $_POST["passwordChangeInput"];
    $userID = $_SESSION["userID"];
    $passwordNew = password_hash($passwordText, PASSWORD_BCRYPT);
    $sql = "UPDATE users SET password = '$passwordNew' WHERE id = '$userID';";

    if (mysqli_query($conn, $sql)) {
      header("location: settings.php");
    }
    else {
      header("location: settings.php?message=Something+went+wrong.+You+may+not+have+the+permissions+to+do+this.");
    }
  }

  else if (isset($_POST["submitUpdateSettings"]) && isset($_POST["token"]) && $_POST["token"] == $_SESSION["userToken"]) {
    $fuzziness = mysqli_real_escape_string($conn, $_POST["fuzziness"]);
    $userID = $_SESSION["userID"];
    $chUpdateSQL = "SELECT * FROM settings WHERE user = '$userID' LIMIT 1";
  	$result = mysqli_query($conn, $chUpdateSQL);

  	if (mysqli_num_rows($result) == 1) {
      $updateSQL = "UPDATE settings set fuzzyDates = '$fuzziness' WHERE user = '$userID'";
      if (mysqli_query($conn, $updateSQL)) {
        header("location: settings.php");
      }
      else {
        header("location: settings.php?message=Something+went+wrong.+You+may+not+have+the+permissions+to+do+this.");
      }
    }
    else {
      $insertSQL = "INSERT INTO settings set fuzzyDates = '$fuzziness', user = '$userID'";
      if (mysqli_query($conn, $insertSQL)) {
        header("location: settings.php");
      }
      else {
        header("location: settings.php?message=Something+went+wrong.+You+may+not+have+the+permissions+to+do+this.");
      }
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
