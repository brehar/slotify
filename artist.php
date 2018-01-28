<?php
	include("includes/includedFiles.php");

	if (isset($_GET['id'])) {
		$artistId = $_GET['id'];
	} else {
		header("Location: index.php");
	}

	$artist = new Artist($con, $artistId);
?>

<div class="entityInfo">
	<div class="centerSection">
		<div class="artistInfo">
			<h1 class="artistName"><?php echo $artist->getName(); ?></h1>
            <div class="headerButtons">
                <button class="button green">PLAY</button>
            </div>
		</div>
	</div>
</div>
<div class="tracklistContainer">
    <ul class="tracklist">
		<?php
			$songIdArray = $artist->getSongIds();

			foreach($songIdArray as $songId) {
				$albumSong = new Song($con, $songId);

				echo "<li class='tracklistRow'>";
				echo "<div class='trackCount'>";
				echo "<img class='play' src='assets/images/icons/play-white.png' alt='Play' onclick='setTrack(\"" . $albumSong->getId() . "\", tempPlaylist, true)' />";
				echo "<span class='trackNumber'>";
				echo $albumSong->getAlbumOrder();
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
				echo "<img class='optionsButton' src='assets/images/icons/more.png' />";
				echo "</div>";
				echo "<div class='trackDuration'>";
				echo "<span class='duration'>";
				echo $albumSong->getDuration();
				echo "</span>";
				echo "</div>";
				echo "</li>";
			}
		?>
    </ul>
</div>
<script>
    let tempSongIds = '<?php echo json_encode($songIdArray); ?>';
    tempPlaylist = JSON.parse(tempSongIds);
</script>
