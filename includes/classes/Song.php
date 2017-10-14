<?php
	class Song {

		private $con;
		private $id;
		private $mysqliData;
		private $title;
		private $artistId;
		private $albumId;
		private $genreId;
		private $duration;
		private $path;
		private $albumOrder;
		private $plays;

		public function __construct($con, $id) {
			$this->con = $con;
			$this->id = $id;

			$query = mysqli_query($this->con, "SELECT * FROM songs WHERE id='$this->id'");
			$this->mysqliData = mysqli_fetch_array($query);

			$this->title = $this->mysqliData['title'];
			$this->artistId = $this->mysqliData['artist'];
			$this->albumId = $this->mysqliData['album'];
			$this->genreId = $this->mysqliData['genre'];
			$this->duration = $this->mysqliData['duration'];
			$this->path = $this->mysqliData['path'];
			$this->albumOrder = $this->mysqliData['albumOrder'];
			$this->plays = $this->mysqliData['plays'];
		}

		public function getTitle() {
			return $this->title;
		}

		public function getArtist() {
			return new Artist($this->con, $this->artistId);
		}

		public function getAlbum() {
			return new Album($this->con, $this->albumId);
		}

		public function getGenre() {
			return new Genre($this->con, $this->genreId);
		}

		public function getDuration() {
			return $this->duration;
		}

		public function getPath() {
			return $this->path;
		}

		public function getAlbumOrder() {
			return $this->albumOrder;
		}

		public function getPlays() {
			return $this->plays;
		}

		public function getMysqliData() {
			return $this->mysqliData;
		}
	}
?>
