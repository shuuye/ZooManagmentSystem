<?php
    /*Author Name: Chew Wei Seng*/
    require_once __DIR__ . '/../../Config/databaseConfig.php';

    class MembershipModel extends databaseConfig{
        
        public function __construct() {
            //establish database connection
            $this->db = $this->getConnection();
        }

        public function getMembershipByMembershipID($membershipID) {
            $stmt = $this->db->prepare("SELECT * FROM membership WHERE membershipID = :membershipID");
            $stmt->bindParam(':membershipID', $membershipID);
            $stmt->execute();

            $membership = $stmt->fetch(PDO::FETCH_ASSOC);

            // Check if a membership was found and return the entire row
            if ($membership) {
                return $membership; // Returns an associative array with all membership data
            } else {
                return null; // Return null if no membership is found
            }
        }
        
        public function getAllMembership() {
            $stmt = $this->db->prepare("SELECT * FROM membership");
            $stmt->execute();

            $membership = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Check if a membership was found and return the entire row
            if ($membership) {
                return $membership; // Returns an associative array with all membership data
            } else {
                return null; // Return null if no membership is found
            }
        }

    }
    
?>

