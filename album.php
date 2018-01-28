<?php include("includes/includedFiles.php"); ?>

<?php
    if (isset($_GET['id'])) {
        $albumId = $_GET['id'];
    } else {
        header("Location: index.php");
        exit;
    }

    $album = new Album($con, $albumId);
    $artist = $album->getArtist();
?>

<div class="entityInfo">
    <div class="leftSection">
        <img src="<?php echo $album->getArtworkPath(); ?>" alt="<?php echo $album->getTitle(); ?>" />
    </div>
    <div class="rightSection">
        <h2><?php echo $album->getTitle(); ?></h2>
        <p>By <?php echo $artist->getName(); ?></p>
        <p class="italics"><?php echo $album->getGenre()->getName(); ?></p>
        <p><?php echo $album->getNumberOfSongs(); ?> <?php echo ngettext("Song", "Songs", $album->getNumberOfSongs()); ?></p>
    </div>
</div>
<div class="tracklistContainer">
    <ul class="tracklist">
        <?php
            $songIdArray = $album->getSongIds();

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
