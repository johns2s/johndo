<?php

include("header.php");

if (isset($_SESSION["user"])) {
  include("loggedin.php");
}
else {
  include("loggedout.php");
}

include("footer.php");

?>
