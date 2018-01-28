<?php
	include("includes/includedFiles.php");

	if (isset($_GET['term'])) {
		$term = urldecode($_GET['term']);
	} else {
		$term = '';
	}
?>

<div class="searchContainer">
	<h4>Search for an artist, album, or song</h4>
	<input type="text" class="searchInput" value="<?php echo $term; ?>" placeholder="Start typing..." onfocus="this.value = this.value" />
</div>

<script>
	$('.searchInput').focus();

	$(function() {
        $('.searchInput').keyup(function() {
	        clearTimeout(timer);

	        timer = setTimeout(function() {
                let val = $('.searchInput').val();
                openPage(`search.php?term=${val}`);
            }, 2000);
        });
	});
</script>

<?php
    if ($term == '') {
        exit();
    }
?>

<div class="tracklistContainer borderBottom">
    <h2>SONGS</h2>
    <ul class="tracklist">
		<?php
            $songsQuery = mysqli_query($con, "SELECT id FROM songs WHERE title LIKE '$term%' LIMIT 10");

            if (mysqli_num_rows($songsQuery) == 0) {
                echo "<span class='noResults'>No songs found matching $term</span>";
            }

			$songIdArray = array();
            $i = 1;

			while ($row = mysqli_fetch_array($songsQuery)) {
				if ($i > 15) {
					break;
				}

				array_push($songIdArray, $row['id']);

				$albumSong = new Song($con, $row['id']);

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
				echo "<img class='optionsButton' src='assets/images/icons/more.png' />";
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

<div class="artistsContainer borderBottom">
    <h2>ARTISTS</h2>
    <?php
        $artistsQuery = mysqli_query($con, "SELECT id FROM artists WHERE name LIKE '$term%' LIMIT 10");

        if (mysqli_num_rows($artistsQuery) == 0) {
            echo "<span class='noResults'>No artists found matching $term</span>";
        }

        while ($row = mysqli_fetch_array($artistsQuery)) {
            $artistFound = new Artist($con, $row['id']);

            echo "<div class='searchResultRow'>";
            echo "<div class='artistName'>";
            echo "<span role='link' tabindex='0' onclick='openPage(\"artist.php?id=" . $artistFound->getId() . "\")'>" . $artistFound->getName() . "</span>";
            echo "</div>";
            echo "</div>";
        }
    ?>
</div>

<div class="gridViewContainer">
    <h2>ALBUMS</h2>
	<?php
		$albumQuery = mysqli_query($con, "SELECT * FROM albums WHERE title LIKE '$term%' LIMIT 10");

		if (mysqli_num_rows($albumQuery) == 0) {
		    echo "<span class='noResults'>No albums found matching $term</span>";
        }

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
