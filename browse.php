<?php include("includes/includedFiles.php"); ?>

<h1 class="pageHeadingBig">You might also like...</h1>
<div class="gridViewContainer">
	<?php
		$albumQuery = mysqli_query($con, "SELECT * FROM albums ORDER BY RAND() LIMIT 8");

		while ($row = mysqli_fetch_array($albumQuery)) {
			echo "<div class='gridViewItem'>";
			echo "<span role='link' tabindex='0' onclick='openPage(\"album.php?id=" . $row['id'] . "\")'>";
			echo "<img src='" . $row['artworkPath'] . "' />";
			echo "<div class='gridViewInfo'>";
			echo $row['title'];
			echo "</div>";
			echo "</span>";
			echo "</div>";
		}
	?>
</div>
