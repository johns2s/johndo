<?php
?>
<script src="https://cdn.jsdelivr.net/npm/moment@2/moment.min.js"></script>
<!-- JSDelivr down? Try this: <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script> -->


<h2 class = "headline">Today is <?php echo date("l, F dS") ?></h2>

<script>

function doMomentFuzzy(dite) {
  document.write(
    moment(dite, 'MM/DD/YYYY').calendar(null, {
      nextDay: '[Tomorrow]',
      lastDay: '[Yesterday]',
      sameDay: '[Today]',
      nextWeek: '[a few days]',
      lastWeek: '[a few days ago]',
      sameElse: 'ddd, MMM Do',
    })
  );
}


function doMomentSemiFuzzy(dite) {
  document.write(
    moment(dite, 'MM/DD/YYYY').calendar(null, {
      nextDay: '[Tomorrow]',
      lastDay: '[Yesterday]',
      sameDay: '[Today]',
      nextWeek: '[This] dddd',
      lastWeek: 'ddd, MMM Do',
      sameElse: 'ddd, MMM Do',
    })
  );
}

</script>


<?php

if (isset($_GET["message"])) {
  echo "<p class = 'message'><b>" . htmlentities($_GET['message']) . "</b></p>";
}

?>

<div class = "wrapper tskW">
  <div class = "item tsk">
    <h3 style = "margin-bottom: 25px">Add New Task</h3>
    <hr>
    <form action = "newtask.php" method = "POST">
      <input type = "text" class = "inputText" name = "titleInput" maxlength = "75" placeholder = "Task Title">
      <input type = "submit" class = "inputBtn" name = "submitNewTitle" value = "Continue..." required>
    </form>
  </div>

  <?php

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


    $sql = "SELECT * FROM tasks WHERE user = '$userID' ORDER BY date asc, id desc";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
      while($row = mysqli_fetch_assoc($result)) {
        if ($row["date"] == "unknown") {
          $date = $row["date"];
          $dite = $row["date"];
        }
        else {
          $date = date("D, M dS", $row["date"]);
          $dite = date("m/d/Y", $row["date"]);
        }

        echo "<div class = 'item tsk'><h3>" . htmlentities($row["title"]) . "</h3>";

        $moreOrig = htmlentities($row["more"]);

        // regex, thanks Stackoverflow
        $moreUrl = preg_replace_callback(
            "@
                (?:http|ftp|https)://
                (?:
                    (?P<domain>\S+?) (?:/\S+)|
                    (?P<domain_only>\S+)
                )
            @sx",
            function($a){
                $link = "<a href='" . $a[0] . "'>";
                $link .= $a["domain"] !== "" ? $a["domain"] : $a["domain_only"];
                $link .= "</a>";
                return $link;
            },
            $moreOrig
        );
        $more = nl2br($moreUrl);
        if ($more == "") {
          $more = ". . . . .";
        }

          if ($fuzziness == "fuzzy") {

              if (strtotime("tomorrow") >= $row["date"] && strtotime("today") <= $row["date"] && $row["date"] !== "unknown") {
                echo "<img class = 'icon' src = 'redclock.png' alt = 'This task is due soon' title = 'This task is due soon'></img>
                <p style = 'color: #f62626'><b><script>doMomentFuzzy('" . $dite . "');</script></b></p>
                <hr><p style = 'max-height: 300px; display: block; overflow-y: auto'>" . $more . "</p>";
              }
              else if (strtotime("today") >= $row["date"] && $row["date"] !== "unknown") {
                echo "<img class = 'icon' src = 'warning.png' alt = 'This task is late' title = 'This task is late'></img>
                <p style = 'color: #f62626'><b><script>doMomentFuzzy('" . $dite . "');</script></b></p>
                <hr><p style = 'max-height: 300px; display: block; overflow-y: auto'>" . $more . "</p>";

              }
              else {
                echo "<img class = 'icon' src = 'clock.png'></img>
                <p><b><script>doMomentFuzzy('" . $dite . "');</script></b></p>
                <hr><p style = 'max-height: 300px; display: block; overflow-y: auto'>" . $more . "</p>";
              }
            }

            else if ($fuzziness == "semiFuzzy") {

                if (strtotime("tomorrow") >= $row["date"] && strtotime("today") <= $row["date"] && $row["date"] !== "unknown") {
                  echo "<img class = 'icon'alt = 'This task is due soon'  src = 'redclock.png' title = 'This task is due soon'></img>
                  <p style = 'color: #f62626'><b><script>doMomentSemiFuzzy('" . $dite . "');</script></b></p>
                  <hr><p style = 'max-height: 300px; display: block; overflow-y: auto'>" . $more . "</p>";
                }
                else if (strtotime("today") >= $row["date"] && $row["date"] !== "unknown") {
                  echo "<img class = 'icon' alt = 'This task is late' src = 'warning.png' title = 'This task is late'></img>
                  <p style = 'color: #f62626'><b><script>doMomentSemiFuzzy('" . $dite . "');</script></b></p>
                  <hr><p style = 'max-height: 300px; display: block; overflow-y: auto'>" . $more . "</p>";

                }
                else {
                  echo "<img class = 'icon' src = 'clock.png'></img>
                  <p><b><script>doMomentSemiFuzzy('" . $dite . "');</script></b></p>
                  <hr><p style = 'max-height: 300px; display: block; overflow-y: auto'>" . $more . "</p>";
                }
              }


            else {

                if (strtotime("tomorrow") >= $row["date"] && strtotime("today") <= $row["date"] && $row["date"] !== "unknown") {
                  echo "<img class = 'icon' alt = 'This task is due soon' src = 'redclock.png' title = 'This task is due soon'></img>
                  <p style = 'color: #f62626'><b>" . $date . "</b></p>
                  <hr><p style = 'max-height: 300px; display: block; overflow-y: auto'>" . $more . "</p>";
                }
                else if (strtotime("today") >= $row["date"] && $row["date"] !== "unknown") {
                  echo "<img class = 'icon' alt = 'This task is late' src = 'warning.png' title = 'This task is late'></img>
                  <p style = 'color: #f62626'><b>" . $date . "</b></p>
                  <hr><p style = 'max-height: 300px; display: block; overflow-y: auto'>" . $more . "</p>";

                }
                else {
                  echo "<img class = 'icon' src = 'clock.png'></img>
                  <p><b>" . $date . "</b></p>
                  <hr><p style = 'max-height: 300px; display: block; overflow-y: auto'>" . $more . "</p>";
                }
              }

            echo "<div class = 'optionsWrap'>
            <a href = 'process.php?delete&id=" . $row["id"] . "&token=" . $_SESSION["userToken"] . "' style = 'color: #1BAF5B; display: block;'>Finished</a>
            <a href = 'edittask.php?template=" . $row["template"] . "' style = 'color: #5294e2; display: block;'>Edit</a>
            </div>
            </div>";
              }
      }

  ?>

  <!-- delete btn:<a href = 'Delete' style = 'color: #f62626; display: block;'>Delete</a>
-->

</div>
<?php
?>
