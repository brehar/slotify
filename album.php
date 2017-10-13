<?php include("includes/header.php"); ?>

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

<?php include("includes/footer.php"); ?>
