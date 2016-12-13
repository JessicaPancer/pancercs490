<?php session_start(); ?>
<html>
<body>
<center>
  <?php
  $place = $_GET["place"];
    echo '<p id="title"> Study abroad photos in '. $place .': </p>';
  ?>
	<input action="action" type="button" value="Back" onclick="history.go(-1);" />

</center>

</body>
</html>
