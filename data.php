<?php

  include("header.php");

  if (isset($_SESSION["user"])) {

    $userID = $_SESSION["userID"];

    $sqlDat = "SELECT * FROM users WHERE id = '$userID' LIMIT 1";
    $resultDat = mysqli_query($conn, $sqlDat);
    $rowDat = mysqli_fetch_assoc($resultDat);

?>

  <div class = "logsignWrap">
    <script>
      function alertKill() {
        var answer = confirm("Are you sure you wish to permanently delete your account, all of your tasks, and any other associated data?")
        if (answer)
          window.location = "logsign.php?killAccount=True&token=<?php echo $_SESSION['userToken'] ?>"
      }
    </script>
    <div class = "item">
      <h3>Your Data</h3>
      <p class = "message"><i>You can copy and past the data on this page, take a screenshot, or print it out</i></p>
      <label class = "formLabel">Your Email</label>
      <div class = "sub">
        <p class = "data"><?php echo $rowDat["email"]; ?></p>
      </div>
      <label class = "formLabel">Your Tasks</label>

      <?php
        $sqlTDat = "SELECT * FROM tasks WHERE user = '$userID' ORDER BY date asc, id desc";
        $resultTDat = mysqli_query($conn, $sqlTDat);

        if (mysqli_num_rows($resultTDat) > 0) {
          while($rowTDat = mysqli_fetch_assoc($resultTDat)) {
      ?>
      <div class = "sub">
        <p class = "data" style = "font-weight: 700; font-size: 1em; font-style: normal;"><?php echo $rowTDat["title"]; ?> (due: <?php echo $rowTDat["date"] ?>)</p>
        <p class = "data"><?php echo $rowTDat["more"]; ?></p>
      </div>
      <?php
          }
        }
        else {
          echo "No task data";
        }
      ?>

      <a href = "javascript:alertKill()" class = "inputBtn" style = "display: block; background-color: #f62626">Delete Account & Data</a>

    </div>
  </div>

<?php

  include("footer.php");

  }

  else {
    header("location: index.php");
  }

?>
