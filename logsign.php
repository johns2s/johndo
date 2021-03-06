<?php

include("config.php");

if (isset($_POST["submitLogin"])) {
  $email = mysqli_real_escape_string($conn, $_POST["emailInput"]);
  $passwordText = $_POST["passwordInput"];
  $loginSQL = "SELECT * FROM users WHERE email = '$email' LIMIT 1";
	$result = mysqli_query($conn, $loginSQL);

	if (mysqli_num_rows($result) == 1) {
    $row = mysqli_fetch_assoc($result);
    $hash = $row["password"];

    if (password_verify($passwordText, $hash)) {
      $_SESSION["user"] = 1;
  		$_SESSION["userID"] = $row["id"];
      $_SESSION["userToken"] = hash("sha256", random_bytes(12) . time());
  		header("location: index.php");
    }
	  else {
		  header("location: login.php?message=Incorrect+email+or+password.");
	  }
  }
  else {
    header("location: login.php?message=Incorrect+email+or+password.");
  }


}

else if (isset($_POST["submitSignup"])) {
  $email = mysqli_real_escape_string($conn, $_POST["emailInput"]);
	$passwordText = $_POST["passwordInput"];
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("location: login.php?message=Invalid+Email+Address.");
    exit;
  }
  $password = password_hash($passwordText, PASSWORD_BCRYPT);
  $signupSQLCheck = "SELECT * FROM users WHERE email = '$email'";
	$result = mysqli_query($conn, $signupSQLCheck);

	if (mysqli_num_rows($result) == 0) {
		$signupSQL = "INSERT INTO users set email = '$email', password = '$password'";
		mysqli_query($conn, $signupSQL);
		header("location: login.php?message=Your+account+is+active.+You+may+now+login.");
    exit;
	}

	else {
		header("location: signup.php?message=This+email+is+already+registered.");
    exit;
	}

}

else if (isset($_GET["logout"]) && isset($_SESSION["user"]) && isset($_GET["token"]) && $_GET["token"] == $_SESSION["userToken"]) {
	session_destroy();
	$_SESSION = array();
	header("location: login.php?message=You+have+been+logged+out.");
  exit;
}

else if (isset($_GET["killAccount"]) && isset($_SESSION["user"]) && isset($_GET["token"]) && $_GET["token"] == $_SESSION["userToken"]) {
  $userID = $_SESSION["userID"];
  $killSQL = "DELETE FROM settings WHERE user = '$userID';";
  $killSQL1 = "DELETE FROM tasks WHERE user = '$userID';";
  $killSQL2 = "DELETE FROM users WHERE id = '$userID' LIMIT 1;";
  if (mysqli_query($conn, $killSQL) && mysqli_query($conn, $killSQL1) && mysqli_query($conn, $killSQL2)) {
    session_destroy();
    $_SESSION = array();
    header("location: signup.php?message=Your+account+has+been+deleted.");
    exit;
  }
  else {
    header("location: settings.php?message=Your+account+couldn't+be+deleted.");
    exit;
  }


}

else if (isset($_SESSION["user"])) {
	header("location: index.php");
  exit;
}

else {
	header("location: index.php");
  exit;
}

mysqli_close($conn);

?>
