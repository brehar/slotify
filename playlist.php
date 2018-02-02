<?php include("includes/includedFiles.php"); ?>

<?php
	if (isset($_GET['id'])) {
		$playlistId = $_GET['id'];
	} else {
		header("Location: index.php");
		exit;
	}

	$playlist = new Playlist($con, $playlistId);
	$owner = new User($con, $playlist->getOwner());
?>

<div class="entityInfo">
	<div class="leftSection">
        <div class="playlistImage">
		    <img src="assets/images/icons/playlist.png" />
        </div>
	</div>
	<div class="rightSection">
		<h2><?php echo $playlist->getName(); ?></h2>
		<p>By <?php echo $playlist->getOwner(); ?></p>
		<p><?php echo $playlist->getNumberOfSongs(); ?> <?php echo ngettext("Song", "Songs", $playlist->getNumberOfSongs()); ?></p>
		<button class="button" onclick="deletePlaylist('<?php echo $playlistId; ?>');">DELETE PLAYLIST</button>
	</div>
</div>
<div class="tracklistContainer">
	<ul class="tracklist">
		<?php
			$songIdArray = $playlist->getSongIds();
			$i = 1;

			foreach($songIdArray as $songId) {
				$playlistSong = new Song($con, $songId);
				$songArtist = $playlistSong->getArtist();

				echo "<li class='tracklistRow'>";
				echo "<div class='trackCount'>";
				echo "<img class='play' src='assets/images/icons/play-white.png' alt='Play' onclick='setTrack(\"" . $playlistSong->getId() . "\", tempPlaylist, true)' />";
				echo "<span class='trackNumber'>";
				echo $i;
				echo "</span>";
				echo "</div>";
				echo "<div class='trackInfo'>";
				echo "<span class='trackName'>";
				echo $playlistSong->getTitle();
				echo "</span>";
				echo "<span class='numPlays'>";
				echo $songArtist->getName() . " (" . $playlistSong->getPlays() . " " . ngettext("Play", "Plays", $playlistSong->getPlays()) . ")";
				echo "</span>";
				echo "</div>";
				echo "<div class='trackOptions'>";
				echo "<input type='hidden' class='songId' value='" . $playlistSong->getId() . "' />";
				echo "<img class='optionsButton' src='assets/images/icons/more.png' onclick='showOptionsMenu(this);' />";
				echo "</div>";
				echo "<div class='trackDuration'>";
				echo "<span class='duration'>";
				echo $playlistSong->getDuration();
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
<nav class="optionsMenu">
    <input type="hidden" class="songId" />
	<?php echo Playlist::getPlaylistDropdown($con, $userLoggedIn->getUsername()); ?>
</nav>
