<?php
    class userManagementUI {
        public static function welcomeLoginBanner() {
            echo "
            <div class='welcome-login-banner'>
                <h1>Login Here</h1>
            </div>
            ";
        }
        public static function welcomeRegisterBanner() {
            echo "
            <div class='welcome-login-banner'>
                <h1>Register Here</h1>
            </div>
            ";
        }

        public static function displayLoginForm() {
            echo '
            <form action="" method="POST">
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
        
        public static function inputInTableNoAutoComplete($title, $inputType, $name, $sessionErrName){
            $errorMsg = isset($_SESSION[$sessionErrName]) ? $_SESSION[$sessionErrName] : "";
            echo '<tr>
                    <td>' . $title . ':</td>
                    <td><input type="' . $inputType . '" name="' . $name . '" autocomplete="off" required></td>
                    <td class="error">' . $errorMsg . '</td>
                </tr>';
        }

        public static function displayRegistrationForm() {
            echo '<form action="" method="post" autocomplete="off">
                    <table>';
                        self::inputInTableNoAutoComplete("Username", "text", "newUsername", "newUsernameErr");
                        self::inputInTableNoAutoComplete("Password", "password", "newPassword", "newPasswordErr");
                        self::inputInTableNoAutoComplete("Confirm Password", "password", "confirmPassword", "confirmPasswordErr");
                        self::inputInTableNoAutoComplete("First Name", "text", "newFirstName", "newFirstNameErr");
                        self::inputInTableNoAutoComplete("Last Name", "text", "newLastName", "newLastNameErr");
                        self::inputInTableNoAutoComplete("Phone Number", "text", "newPhoneNumber", "newPhoneNumberErr");
                        self::inputInTableNoAutoComplete("Email Address", "text", "newEmail", "newEmailErr");

            $defaultRoleId = 2;
            $options = userManagementControl::generateRoleOptions($defaultRoleId);  
            $i = 1;//here control to display what 

            if ($i == 1) {
                echo '<tr>
                        <td>Role:</td>
                        <td>
                            <select name="role">' . $options . '</select>
                        </td>
                      </tr>';
            } else {
                echo '<tr>
                        <td colspan="3">i is not 1</td>
                      </tr>';
            }

            // Continue HTML output
            echo '  <tr>
                        <td colspan="3"><button type="submit">Register</button></td>
                    </tr>
                </table>
            </form>';
        }

            
        /*
        public static function displayRegistrationForm(){
            echo'<form action="" method="post" autocomplete="off">
                <table>';
                    self::inputInTableNoAutoComplete("Username",  "text", "newUsername", "usernameErr");
                    
                    <tr>
                        <td>Password:</td>
                        <td><input type="text" name="password" autocomplete="off" required></td>
                        <td class="error"><?php echo isset($_SESSION["passwordErr"]) ? $_SESSION["passwordErr"] : ''; ?></td>
                    </tr>
                    <tr>
                        <td>First Name:</td>
                        <td><input type="text" name="firstName"></td>
                        <td class="error"><?php echo isset($_SESSION["firstNameErr"]) ? $_SESSION["firstNameErr"] : ''; ?></td>
                    </tr>
                    <tr>
                        <td>Last Name:</td>
                        <td><input type="text" name="lastName"></td>
                        <td class="error"><?php echo isset($_SESSION["lastNameErr"]) ? $_SESSION["lastNameErr"] : ''; ?></td>
                    </tr>
                    <tr>
                        <td>Phone Number:</td>
                        <td><input type="text" name="phoneNumber"></td>
                        <td class="error"><?php echo isset($_SESSION["phoneNumberErr"]) ? $_SESSION["phoneNumberErr"] : ''; ?></td>
                    </tr>
                    <tr>
                        <td>Email:</td>
                        <td><input type="email" name="email"></td>
                        <td class="error"><?php echo isset($_SESSION["emailErr"]) ? $_SESSION["emailErr"] : ''; ?></td>
                    </tr>
                    <?php
                        $defaultRoleId = 2;
                        $options = userManagementControl::generateRoleOptions($defaultRoleId);  

                        $i = 0;
                    ?>

                    <?php if ($i == 1): ?>
                        <tr>
                            <td>Role:</td>
                            <td>
                                <select name="role">
                                    <?php echo $options; ?>
                                </select>
                            </td>
                        </tr>
                    <?php else: ?>
                        <tr>
                            <td colspan="3">i is not 1</td>
                        </tr>
                    <?php endif; ?>

            
            echo '
                    <tr>
                        <td colspan="3"><button type="submit">Register</button></td>
                    </tr>
                    
                </table>
            </form>';
        }*/
    }
?>
