<?php
    include("includes/config.php");
    include("includes/classes/Account.php");
    include("includes/classes/Constants.php");

    $account = new Account($con);

    include("includes/handlers/register-handler.php");
    include("includes/handlers/login-handler.php");

    function getInputValue($inputName) {
        if (isset($_POST[$inputName])) {
            echo $_POST[$inputName];
        }
    }
?>

<html>
    <head>
        <title>Welcome to Slotify!</title>
        <link rel="stylesheet" type="text/css" href="assets/css/register.css" />
    </head>
    <body>
        <div id="background">
            <div id="loginContainer">
                <div id="inputContainer">
                    <form action="register.php" id="loginForm" method="POST">
                        <h2>Log in to your Slotify account</h2>
                        <p>
                            <?php echo $account->getError(Constants::$loginFailed); ?>
                            <label for="loginUsername">Username</label>
                            <input type="text" id="loginUsername" name="loginUsername" placeholder="e.g., bartSimpson" required />
                        </p>
                        <p>
                            <label for="loginPassword">Password</label>
                            <input type="password" id="loginPassword" name="loginPassword" placeholder="Your password" required />
                        </p>
                        <button type="submit" name="loginButton">LOG IN</button>
                        <div class="hasAccountText">
                            <span id="hideLogin">Don't have an account yet? Signup here.</span>
                        </div>
                    </form>
                    <form action="register.php" id="registerForm" method="POST">
                        <h2>Create your free Slotify account</h2>
                        <p>
                            <?php echo $account->getError(Constants::$usernameLengthInvalid); ?>
                            <?php echo $account->getError(Constants::$usernameTaken); ?>
                            <label for="username">Username</label>
                            <input type="text" id="username" name="username" placeholder="e.g., bartSimpson" value="<?php getInputValue('username'); ?>" required />
                        </p>
                        <p>
                            <?php echo $account->getError(Constants::$firstNameLengthInvalid); ?>
                            <label for="firstName">First name</label>
                            <input type="text" id="firstName" name="firstName" placeholder="e.g., Bart" value="<?php getInputValue('firstName'); ?>" required />
                        </p>
                        <p>
                            <?php echo $account->getError(Constants::$lastNameLengthInvalid); ?>
                            <label for="lastName">Last name</label>
                            <input type="text" id="lastName" name="lastName" placeholder="e.g., Simpson" value="<?php getInputValue('lastName'); ?>" required />
                        </p>
                        <p>
                            <?php echo $account->getError(Constants::$emailInvalid); ?>
                            <?php echo $account->getError(Constants::$emailTaken); ?>
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" placeholder="e.g., bart@gmail.com" value="<?php getInputValue('email'); ?>" required />
                        </p>
                        <p>
                            <?php echo $account->getError(Constants::$emailsDoNotMatch); ?>
                            <label for="email2">Confirm email</label>
                            <input type="email" id="email2" name="email2" placeholder="e.g., bart@gmail.com" value="<?php getInputValue('email2'); ?>" required />
                        </p>
                        <p>
                            <?php echo $account->getError(Constants::$passwordNotAlphanumeric); ?>
                            <?php echo $account->getError(Constants::$passwordLengthInvalid); ?>
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password" placeholder="Your password" required />
                        </p>
                        <p>
                            <?php echo $account->getError(Constants::$passwordsDoNotMatch); ?>
                            <label for="password2">Confirm password</label>
                            <input type="password" id="password2" name="password2" placeholder="Your password" required />
                        </p>
                        <button type="submit" name="registerButton">SIGN UP</button>
                        <div class="hasAccountText">
                            <span id="hideRegister">Already have an account? Log in here.</span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="assets/js/register.js"></script>
    </body>
</html>
