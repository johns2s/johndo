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
  		header("location: index.php");
    }
	  else {
		  header("location: login.php?message=Incorrect+email+or+password.+Do+you+want+to+<a href = 'signup.php'>sign up</a>?");
	  }
  }
  else {
    header("location: login.php?message=Incorrect+email+or+password.+Do+you+want+to+<a href = 'signup.php'>sign up</a>?");
  }


}

else if (isset($_POST["submitSignup"])) {
  $email = mysqli_real_escape_string($conn, $_POST["emailInput"]);
	$passwordText = $_POST["passwordInput"];
  $password = password_hash($passwordText, PASSWORD_BCRYPT);
  $signupSQLCheck = "SELECT * FROM users WHERE email = '$email'";
	$result = mysqli_query($conn, $signupSQLCheck);

	if (mysqli_num_rows($result) == 0) {
		$signupSQL = "INSERT INTO users set email = '$email', password = '$password'";
		mysqli_query($conn, $signupSQL);
		header("location: login.php?message=Your+account+is+active.+You+may+now+login.");
	}

	else {
		header("location: signup.php?message=This+email+is+already+registered.+Do+you+want+to+<a href = 'login.php'>login</a>?");
	}

}

else if (isset($_GET["logout"]) && isset($_SESSION["user"])) {
	session_destroy();
	$_SESSION = array();
	header("location: login.php?message=You+have+been+logged+out.");
}

else if (isset($_GET["killAccount"]) && isset($_SESSION["user"])) {
  $userID = $_SESSION["userID"];
  $killSQL = "DELETE FROM users WHERE id = '$userID' LIMIT 1";
  if (mysqli_query($conn, $killSQL)) {
    session_destroy();
  	$_SESSION = array();
    header("location: signup.php?message=Your+account+has+been+deleted.");
  }
  else {
    header("location: settings.php?message=Your+account+couldn't+be+deleted.");
  }


}

else if (isset($_SESSION["user"])) {
	header("location: index.php");
}

else {
	header("location: index.php");
}

mysqli_close($conn);

?>
