<?php

  include("header.php");

  if (isset($_SESSION["user"])) {

    $userID = $_SESSION["userID"];

    $sqlSet = "SELECT * FROM settings WHERE user = '$userID' LIMIT 1";
    $resultSet = mysqli_query($conn, $sqlSet);
    if (mysqli_num_rows($resultSet) == 1) {
      while($rowSet = mysqli_fetch_assoc($resultSet)) {
        $fuzziness = $rowSet["fuzzyDates"];
      }
    }
    else {
      $fuzziness = "notFuzzy";
    }


?>

  <div class = "logsignWrap">
    <script>
      function alertKill() {
        var answer = confirm("Are you sure you wish to permanently delete your account and all of your tasks?")
        if (answer)
          window.location = "logsign.php?killAccount=True&token=<?php echo $_SESSION['userToken'] ?>"
      }
  </script>
    <div class = "item" action = "process.php" method = "POST">
      <h3>Settings</h3>

      <?php
      if (isset($_GET["message"])) {
        echo "<p class = 'message'><b>" . htmlentities($_GET['message']) . "</b></p>";
      }

      ?>
      <form action = "process.php" method = "POST">
        <input type = "hidden" name = "token" value = "<?php echo $_SESSION["userToken"]; ?>">
        <label class = "formLabel">Task Display</label>
        <div class = "radioWrap">
          <input id="fuzzy" style="display: none" name="fuzziness" value="fuzzy" <?php if($fuzziness == "fuzzy") { echo "checked"; } ?> type="radio">
          <input id="notFuzzy" style="display: none" name="fuzziness" value="notFuzzy" <?php if($fuzziness == "notFuzzy") { echo "checked"; } ?> type="radio">
          <input id="semiFuzzy" style="display: none" name="fuzziness" value="semiFuzzy" <?php if($fuzziness == "semiFuzzy") { echo "checked"; } ?> type="radio">
          <label for="fuzzy" id = "fuzzyLabel" class="radioLabel">Fuzzy</label>
          <label for="semiFuzzy" id = "semifLabel" class="radioLabel">Semi-Fuzzy</label>
          <label for="notFuzzy" id = "nfLabel" class="radioLabel">Not Fuzzy</label>
        </div>
        <input type = "submit" class = "inputBtn" name = "submitUpdateSettings" value = "Update Settings" style = "margin-bottom: 10px;" required>
      </form>
      <hr class = "bigHR">
      <form action = "process.php" method = "POST">
        <input type = "hidden" name = "token" value = "<?php echo $_SESSION["userToken"]; ?>">
        <label class = "formLabel">Account Details</label>
        <input type="text" style="display:none">
        <input type="password" style="display:none">
        <input type = "password" class = "inputText" name = "passwordChangeInput" value = "" placeholder = "New Password" required>
        <input type = "submit" class = "inputBtn" name = "submitUpdateSettingsPW" value = "Change Password" style = "margin-bottom: 15px" required>
      </form>
      <a href = "javascript:alertKill()" class = "inputBtn" style = "display: block; background-color: #f62626">Delete Account</a>

    </div>
  </div>

<?php

  include("footer.php");

  }

  else {
    header("location: index.php");
  }

?>
