<?php
	class Genre {

		private $con;
		private $id;

		public function __construct($con, $id) {
			$this->con = $con;
			$this->id = $id;
		}

		public function getName() {
			$genreQuery = mysqli_query($this->con, "SELECT `name` FROM genres WHERE id='$this->id'");
			$genre = mysqli_fetch_array($genreQuery);

			return $genre['name'];
		}
	}
?>
