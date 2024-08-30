
<?php
    require_once 'UserInterface.php';

    abstract class UserDecorator implements UserInterface {
        protected $user;

        public function __construct(UserInterface $user) {
            $this->user = $user;
        }

        public function getId() {
            return $this->user->getId();
        }

        public function getUsername() {
            return $this->user->getUsername();
        }

        public function getPassword() {
            return $this->user->getPassword();
        }

        public function getFirstName() {
            return $this->user->getFirstName();
        }

        public function getLastName() {
            return $this->user->getLastName();
        }

        public function getPhoneNumber() {
            return $this->user->getPhoneNumber();
        }

        public function getEmail() {
            return $this->user->getEmail();
        }

        public function getRole() {
            return $this->user->getRole();
        }

        public function navigateToBasedOnRoles() {
            $this->user->navigateToBasedOnRoles();
        }
    }

?>

