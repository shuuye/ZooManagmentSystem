<?php

    class databaseConfig {
        private $host = 'localhost';
        private $db_name = 'zooManagementdb';
        private $username = 'alibaba';
        private $password = 'alibaba123';
        private $conn;

        protected function getConnection() {
            $this->conn = null;

            try {
                // set connection to database using PDO
                
                $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
            } catch(PDOException $exception) {
                //throw new Exception("Connection error: " . $exception->getMessage());
                //$_SESSION['error_message'] = "Error: " . $exception->getMessage();
                $_SESSION['error_message'] = "Error: connection Failed";
                header("Location: index.php");
                exit();
            }

            return $this->conn;
        }
    }
?>
