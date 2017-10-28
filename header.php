<?php include("config.php") ?>

<!doctype html>

<html>

<head>
  <title>johnDo - Planner</title>
  <meta charset = "UTF-8">
  <link rel="icon" href="logo.png" type="image/png">
  <meta name="viewport" content="width = device-width, initial-scale = 1.0">
  <link rel="stylesheet" type="text/css" href="style.css">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700" rel="stylesheet" type="text/css">
</head>

<body>

<header>
  <a href = "index.php" class = "headerTitle">
    <img class = "logo" src = "logo.png"></img>
    johnDo
   </a>

   <nav>

   <?php
    if (isset($_SESSION["user"])) {
      echo "<a href = 'logsign.php?logout=true' class = 'navLink'>Logout</a>";
    }
    else {
      echo "<a href = 'login.php' class = 'navLink'>Login</a><a href = 'signup.php' class = 'navLink'>Signup</a>";
    }
    ?>

   </nav>

</header>
