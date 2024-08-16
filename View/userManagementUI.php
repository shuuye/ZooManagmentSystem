<?php
    class userManagementUI {
        public static function welcomeLoginBanner() {
            echo "
            <div class='welcome-login-banner'>
                <h1>Login Here</h1>
            </div>
            ";
        }

        public static function displayLoginForm() {
            echo '
            <form action="Control/userManagementControl.php" method="POST">
                <input type="hidden" name="form_type" value="login">                
                <div>
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div>
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div>
                    <button type="submit">Login</button>
                </div>
            </form>
            ';
        }
    }
?>
