<?php
	include("includes/includedFiles.php");

	if (isset($_GET['id'])) {
		$artistId = $_GET['id'];
	} else {
		header("Location: index.php");
	}

	$artist = new Artist($con, $artistId);
?>

<div class="entityInfo borderBottom">
	<div class="centerSection">
		<div class="artistInfo">
			<h1 class="artistName"><?php echo $artist->getName(); ?></h1>
            <div class="headerButtons">
                <button class="button green" onclick="playFirstSong()">PLAY</button>
            </div>
		</div>
	</div>
</div>
<div class="tracklistContainer borderBottom">
    <h2>POPULAR SONGS</h2>
    <ul class="tracklist">
		<?php
			$songIdArray = $artist->getSongIds();
			$i = 1;

			foreach($songIdArray as $songId) {
			    if ($i > 5) {
			        break;
                }

				$albumSong = new Song($con, $songId);

				echo "<li class='tracklistRow'>";
				echo "<div class='trackCount'>";
				echo "<img class='play' src='assets/images/icons/play-white.png' alt='Play' onclick='setTrack(\"" . $albumSong->getId() . "\", tempPlaylist, true)' />";
				echo "<span class='trackNumber'>";
				echo $i;
				echo "</span>";
				echo "</div>";
				echo "<div class='trackInfo'>";
				echo "<span class='trackName'>";
				echo $albumSong->getTitle();
				echo "</span>";
				echo "<span class='numPlays'>";
				echo $albumSong->getPlays() . " " . ngettext("Play", "Plays", $albumSong->getPlays());
				echo "</span>";
				echo "</div>";
				echo "<div class='trackOptions'>";
				echo "<input type='hidden' class='songId' value='" . $albumSong->getId() . "' />";
				echo "<img class='optionsButton' src='assets/images/icons/more.png' onclick='showOptionsMenu(this);' />";
				echo "</div>";
				echo "<div class='trackDuration'>";
				echo "<span class='duration'>";
				echo $albumSong->getDuration();
				echo "</span>";
				echo "</div>";
				echo "</li>";

				$i++;
			}
		?>
    </ul>
</div>
<script>
    tempSongIds = '<?php echo json_encode($songIdArray); ?>';
    tempPlaylist = JSON.parse(tempSongIds);
</script>
<div class="gridViewContainer">
    <h2>ALBUMS</h2>
	<?php
		$albumQuery = mysqli_query($con, "SELECT * FROM albums WHERE artist='$artistId'");

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
<nav class="optionsMenu">
    <input type="hidden" class="songId" />
	<?php echo Playlist::getPlaylistDropdown($con, $userLoggedIn->getUsername()); ?>
</nav>
