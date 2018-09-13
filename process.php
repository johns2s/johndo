<?php
include("config.php");

function new_template() {
  $confirm_template = False;
  $template = "";
  while ($confirm_template == False) {
    $template = random_int(10000000, 100000000);
    $sqlCheckTemp = "SELECT * FROM tasks WHERE template = '$template'";
    $resultCheckTemp = mysqli_query($conn, $sqlCheckTemp);
    if (mysqli_num_rows($resultCheckTemp) == 0) {
      $confirm_template = True;
    }
  }
  return $template;
}

if (isset($_SESSION["user"])) {
  if (isset($_POST["submitNew"]) && isset($_POST["token"]) && $_POST["token"] == $_SESSION["userToken"]) {
    $title = mysqli_real_escape_string($conn, $_POST["titleInput"]);
    $more = mysqli_real_escape_string($conn, $_POST["moreInput"]);
    if ($more == "") {
      $more = ". . . . .";
    }
    $recurring = mysqli_real_escape_string($conn, $_POST["recurring"]);
    if ($recurring == "repeat") {
      $endDate = strtotime(mysqli_real_escape_string($conn, $_POST["endInput"]));

      $deadline = strtotime(mysqli_real_escape_string($conn, $_POST["startInput"]));
      $startDate = strtotime(mysqli_real_escape_string($conn, $_POST["startInput"]));
      $freq = mysqli_real_escape_string($conn, $_POST["freqInput"]);
      if ($startDate > $endDate) {
        header("location: newtask.php?message=The+start+date+may+not+be+later+than+the+end+date.");
        exit;
      }
    }
    else {
      $deadline = strtotime(mysqli_real_escape_string($conn, $_POST["dateInput"]));
      $endDate = False;
      $startDate = False;
      $freq = False;
    }

    $user = $_SESSION["userID"];
    $template = new_template();

		$sql = "INSERT INTO tasks set template = '$template', title = '$title', more = '$more', startDate = '$startDate', date = '$deadline', endDate = '$endDate', freq = '$freq', user = '$user'";
		if (mysqli_query($conn, $sql)) {
      if ($recurring == "repeat") {
        $deadlineNew = strtotime('+' . $freq . ' days', $deadline);
        $duntil = floor(abs($endDate - $startDate) / 86400);
        if ($duntil > 500) {
          header("location: newtask.php?message=Recurring+tasks+must+end+within+500+days+of+starting.");
          exit;
        }
        $times = (int) ($duntil / $freq);
        for ($i = 0; $i < $times; $i++) {
          $sqlX = "INSERT INTO tasks set template = '$template', title = '$title', more = '$more', startDate = '$startDate', date = '$deadlineNew', endDate = '$endDate', freq = '$freq', user = '$user'";
          if (!(mysqli_query($conn, $sqlX))) {
            header("location: newtask.php?message=A+database+error+occured+while+adding+your+task.");
            exit;
          }
          $deadlineNew = strtotime('+' . $freq . ' days', $deadlineNew);
        }
      }
      header("location: index.php");
      exit;
		}
    else {
  		header("location: newtask.php?message=A+database+error+occured+while+adding+your+task.");
      exit;
  	}
  }

  else if (isset($_POST["submitUpdate"]) && isset($_POST["token"]) && $_POST["token"] == $_SESSION["userToken"]) {
    $title = mysqli_real_escape_string($conn, $_POST["titleInput"]);
    $template = mysqli_real_escape_string($conn, $_POST["taskID"]);
    $more = mysqli_real_escape_string($conn, $_POST["moreInput"]);
    if ($more == "") {
      $more = ". . . . .";
    }

    $user = $_SESSION["userID"];

    $recurring = mysqli_real_escape_string($conn, $_POST["recurring"]);
    if ($recurring == "repeat") {
      $endDate = strtotime(mysqli_real_escape_string($conn, $_POST["endInput"]));
      $startDate = strtotime(mysqli_real_escape_string($conn, $_POST["startInput"]));

      $deadline = strtotime(mysqli_real_escape_string($conn, $_POST["startInput"]));
      $freq = mysqli_real_escape_string($conn, $_POST["freqInput"]);

      if ($startDate > $endDate) {
        header("location: newtask.php?message=The+start+date+may+not+be+later+than+the+end+date.");
        exit;
      }

      $sqlExpunge = "DELETE FROM tasks WHERE template = '$template' AND user = '$user'";
      $sql = "INSERT INTO tasks set template = '$template', title = '$title', more = '$more', startDate = '$startDate', date = '$deadline', endDate = '$endDate', freq = '$freq', user = '$user'";

      if (mysqli_query($conn, $sqlExpunge) && mysqli_query($conn, $sql)) {
        if ($recurring == "repeat") {
          $deadlineNew = strtotime('+' . $freq . ' days', $deadline);
          $duntil = floor(abs($endDate - $startDate) / 86400);
          if ($duntil > 500) {
            header("location: edittask.php?template=" . $taskID ."&message=Recurring+tasks+must+end+within+500+days+of+starting.");
            exit;
          }

          $times = (int) ($duntil / $freq);
          for ($i = 0; $i < $times; $i++) {
            $sqlX = "INSERT INTO tasks set template = '$template', title = '$title', more = '$more', startDate = '$startDate', date = '$deadlineNew', endDate = '$endDate', freq = '$freq', user = '$user'";
            if (!(mysqli_query($conn, $sqlX))) {
              header("location: edittask.php?template=" . $taskID ."&message=A+database+error+occured+while+adding+your+task.");
              exit;
            }
            $deadlineNew = strtotime('+' . $freq . ' days', $deadlineNew);
          }

        }
        header("location: index.php");
        exit;
      }
      else {
        header("location: edittask.php?template=" . $taskID ."&message=A+database+error+occured+while+adding+your+task.");
        exit;
      }

    }
    else {
      $deadline = strtotime(mysqli_real_escape_string($conn, $_POST["dateInput"]));
      $endDate = False;
      $freq = False;
      $startDate = False;
      $sql = "UPDATE tasks set title = '$title', more = '$more', startDate = '$startDate', date = '$deadline', endDate = '$endDate', freq = '$freq' WHERE template = '$template' AND user = '$user'";
      if (mysqli_query($conn, $sql)) {
        header("location: index.php");
        exit;
      }
      else {
        header("location: newtask.php?message=A+database+error+occured+while+updating+your+task.You+may+not+be+allowed+to+edit+this+task.");
        exit;
      }
    }
  }

  else if (isset($_GET["delete"]) && isset($_GET["id"]) && isset($_GET["token"]) && $_GET["token"] == $_SESSION["userToken"]) {
    $id = mysqli_real_escape_string($conn, $_GET["id"]);
    $userID = $_SESSION["userID"];
    $sql = "DELETE FROM tasks WHERE id = '$id' AND user = '$userID'";
  	if (mysqli_query($conn, $sql)) {
  		header("location: index.php");
      exit;
    }
  	else {
      header("location: index.php?message=Error+deleting+task.");
      exit;
    }
  }

  else if (isset($_GET["delete"]) && isset($_GET["template"]) && isset($_GET["token"]) && $_GET["token"] == $_SESSION["userToken"]) {
    $template = mysqli_real_escape_string($conn, $_GET["template"]);
    $userID = $_SESSION["userID"];
    $sql = "DELETE FROM tasks WHERE template = '$template' AND user = '$userID'";
    if (mysqli_query($conn, $sql)) {
      header("location: index.php");
      exit;
    }
    else {
      header("location: index.php?message=Error+deleting+task.");
      exit;
    }
  }

  else if (isset($_POST["submitUpdateSettingsPW"]) && isset($_POST["token"]) && $_POST["token"] == $_SESSION["userToken"]) {
    $passwordText = $_POST["passwordChangeInput"];
    $userID = $_SESSION["userID"];
    $passwordNew = password_hash($passwordText, PASSWORD_BCRYPT);
    $sql = "UPDATE users SET password = '$passwordNew' WHERE id = '$userID';";

    if (mysqli_query($conn, $sql)) {
      header("location: settings.php");
      exit;
    }
    else {
      header("location: settings.php?message=Something+went+wrong.+You+may+not+have+the+permissions+to+do+this.");
      exit;
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
        exit;
      }
      else {
        header("location: settings.php?message=Something+went+wrong.+You+may+not+have+the+permissions+to+do+this.");
        exit;
      }
    }
    else {
      $insertSQL = "INSERT INTO settings set fuzzyDates = '$fuzziness', user = '$userID'";
      if (mysqli_query($conn, $insertSQL)) {
        header("location: settings.php");
        exit;
      }
      else {
        header("location: settings.php?message=Something+went+wrong.+You+may+not+have+the+permissions+to+do+this.");
        exit;
      }
    }
  }


  else {
    header("location: index.php");
    exit;
  }

}

else {
  header("location: index.php");
  exit;
}

mysqli_close($conn);
