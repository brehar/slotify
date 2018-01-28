<?php
	if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
		include("config.php");
		include("classes/Artist.php");
		include("classes/Genre.php");
		include("classes/Album.php");
		include("classes/Song.php");
	} else {
		include("header.php");
		include("footer.php");

		$url = $_SERVER['REQUEST_URI'];
		echo "<script>openPage('$url')</script>";
	}
?>
