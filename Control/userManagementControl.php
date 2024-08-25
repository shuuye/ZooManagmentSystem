<?php
    session_start();
    require_once '../Config/databaseConfig.php';

    $database = new databaseConfig();
    $db = $database->getConnection();

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['form_type']) && $_POST['form_type'] == 'login') {
        $username = $_POST['username'];
        $password = $_POST['password'];

        try {
            $query = "SELECT * FROM users WHERE username = :username AND password = :password";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $password);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $_SESSION['username']= "$username";
                    header("Location: ../home.php");
                    exit();
                    //echo "User: " . $row['username'] . "<br>";
                }
            } else {
                $_SESSION['error_message'] = "Invalid username or password.";
                header("Location: /ZooManagementSystem/userLoginPage.php");
                exit();
            }
        } catch (PDOException $exception) {
            $_SESSION['error_message'] = "Error: " . $exception->getMessage();
            header("Location: ../View/userLoginPage.php");
            exit();
        }
    }
?>

