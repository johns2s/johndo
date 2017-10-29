<?php

include("header.php");

if (isset($_SESSION["user"])) {

?>

  <h2 class = "headline">Today is <?php echo date("l, F dS") ?></h2>

  <?php

  if (isset($_GET["message"])) {
    echo "<p class = 'message'><b>" . $_GET['message'] . "</b></p>";
  }

  ?>

  <div class = "wrapper">
    <div class = "item">
      <h3 style = "margin-bottom: 25px">Add New Task</h3>
      <hr>
      <form action = "newtask.php" method = "POST">
        <input type = "text" class = "inputText" name = "titleInput" maxlength = "75" placeholder = "Task Title">
        <input type = "submit" class = "inputBtn" name = "submitNewTitle" value = "Continue..." required>
      </form>
    </div>

    <?php

      $userID = $_SESSION["userID"];

      $sql = "SELECT * FROM tasks WHERE user = '$userID' ORDER BY date asc, id desc";
      $result = mysqli_query($conn, $sql);

      if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
          if ($row["date"] == "unknown") {
            $date = $row["date"];
          }
          else {
            $date = date("D, M dS", $row["date"]);
          }

          echo "<div class = 'item'><h3>" . $row["title"] . "</h3>";

          $more = nl2br($row["more"]);

                if (strtotime("tomorrow") >= $row["date"] && strtotime("today") <= $row["date"] && $row["date"] !== "unknown") {
                  echo "<img class = 'icon' src = 'redclock.png' title = 'This task is due soon'></img>
                  <p style = 'color: #f62626'><b>" . $date . "</b></p>
                  <hr><p style = 'max-height: 300px; display: block; overflow-y: auto'>" . $more . "</p>";
                }
                else if (strtotime("today") >= $row["date"] && $row["date"] !== "unknown") {
                  echo "<img class = 'icon' src = 'warning.png' title = 'This task is late'></img>
                  <p style = 'color: #f62626'><b>" . $date . "</b></p>
                  <hr><p style = 'max-height: 300px; display: block; overflow-y: auto'>" . $more . "</p>";

                }
                else {
                  echo "<img class = 'icon' src = 'clock.png'></img>
                  <p><b>" . $date . "</b></p>
                  <hr><p style = 'max-height: 300px; display: block; overflow-y: auto'>" . $more . "</p>";
                }

              echo "<div class = 'optionsWrap'>
              <a href = 'process.php?delete&id=" . $row["id"] . "' style = 'color: #1BAF5B; display: block;'>Finished</a>
              <a href = 'edittask.php?id=" . $row["id"] . "' style = 'color: #5294e2; display: block;'>Edit</a>
              </div>
              </div>";
                }
        }

    ?>

    <!-- delete btn:<a href = 'Delete' style = 'color: #f62626; display: block;'>Delete</a>
-->

  </div>

<?php

  }

  else {

    ?>

    <div class = "homeWrap">
      <div id = "home">
        <h1 class = "headline" style = "padding-bottom: 15px;">To do? Too done.</h1>
          <h3 class = "headline" style = "font-size: 1em; font-weight: 400;">Get Your Tasks Done With johnDo</h3>
        <a href = "signup.php" class = "inputBtn" style = "margin: 25px auto 0; display: block; max-width: 250px; color: #fff; text-align: center;">Signup For Free</a>
      </div>
    </div>

    <div class = "wrapper">
      <div class = "item">
        <img class = "buyImg" src = "phone.png">
        <h3 style = "margin-bottom: 25px">Works on all Devices</h3>
        <hr>
        <p>johnDo's beautiful and responsive design is hand-crafted to be completely responsive and work on phones, tablets, and computers.</p>
      </div>

      <div class = "item">
        <img class = "buyImg" src = "fast.png">
        <h3 style = "margin-bottom: 25px">Really Fast</h3>
        <hr>
        <p>johnDo is powered by the latest, cleanest HTML5 and CSS3. In addition, we use the fastest server technology and no bloat on our pages.</p>
      </div>

      <div class = "item">
        <img class = "buyImg" src = "secure.png">
        <h3 style = "margin-bottom: 25px">Secure</h3>
        <hr>
        <p>We only use the most secure password encryption techniques. In addition, all pages are served over SSL, which makes it nearly impossible for hackers to intercept your data enroute to our servers.</p>
      </div>
    </div>

      <?php
  }

  include("footer.php");

?>