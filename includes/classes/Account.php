<?php
    class Account {

        private $con;
        private $errorArray;

        public function __construct($con) {
            $this->con = $con;
            $this->errorArray = array();
        }

        public function login($username, $password) {
            $password = md5($password);

            $query = mysqli_query($this->con, "SELECT * FROM users WHERE username = '$username' AND password = '$password'");

            if (mysqli_num_rows($query) == 1) {
                return true;
            } else {
                array_push($this->errorArray, Constants::$loginFailed);
                return false;
            }
        }

        public function register($username, $firstName, $lastName, $email, $email2, $password, $password2) {
            $this->validateUsername($username);
            $this->validateFirstName($firstName);
            $this->validateLastName($lastName);
            $this->validateEmails($email, $email2);
            $this->validatePasswords($password, $password2);

            if (empty($this->errorArray)) {
                return $this->insertUserDetails($username, $firstName, $lastName, $email, $password);
            } else {
                return false;
            }
        }

        public function getError($error) {
            if (!in_array($error, $this->errorArray)) {
                $error = "";
            }

            return "<span class='errorMessage'>$error</span>";
        }

        private function insertUserDetails($username, $firstName, $lastName, $email, $password) {
            $encryptedPassword = md5($password);
            $profilePic = "assets/images/profile-pics/head_emerald.png";
            $date = date("Y-m-d");

            $result = mysqli_query($this->con, "INSERT INTO `users` (`username`, `firstName`, `lastName`, `email`, `password`, `signUpDate`, `profilePic`) VALUES ('$username', '$firstName', '$lastName', '$email', '$encryptedPassword', '$date', '$profilePic')");

            return $result;
        }

        private function validateUsername($username) {
            if (strlen($username) > 25 || strlen($username) < 5) {
                array_push($this->errorArray, Constants::$usernameLengthInvalid);
                return;
            }

            $checkUsernameQuery = mysqli_query($this->con, "SELECT username FROM users WHERE username = '$username'");

            if (mysqli_num_rows($checkUsernameQuery) != 0) {
                array_push($this->errorArray, Constants::$usernameTaken);
                return;
            }
        }

        private function validateFirstName($firstName) {
            if (strlen($firstName) > 25 || strlen($firstName) < 2) {
                array_push($this->errorArray, Constants::$firstNameLengthInvalid);
                return;
            }
        }

        private function validateLastName($lastName) {
            if (strlen($lastName) > 25 || strlen($lastName) < 2) {
                array_push($this->errorArray, Constants::$lastNameLengthInvalid);
                return;
            }
        }

        private function validateEmails($email, $email2) {
            if ($email != $email2) {
                array_push($this->errorArray, Constants::$emailsDoNotMatch);
                return;
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                array_push($this->errorArray, Constants::$emailInvalid);
                return;
            }

            $checkEmailQuery = mysqli_query($this->con, "SELECT email FROM users WHERE email = '$email'");

            if (mysqli_num_rows($checkEmailQuery) != 0) {
                array_push($this->errorArray, Constants::$emailTaken);
                return;
            }
        }

        private function validatePasswords($password, $password2) {
            if ($password != $password2) {
                array_push($this->errorArray, Constants::$passwordsDoNotMatch);
                return;
            }

            if (preg_match('/[^A-Za-z0-9]/', $password)) {
                array_push($this->errorArray, Constants::$passwordNotAlphanumeric);
                return;
            }

            if (strlen($password) > 30 || strlen($password) < 5) {
                array_push($this->errorArray, Constants::$passwordLengthInvalid);
                return;
            }
        }
    }
?>
