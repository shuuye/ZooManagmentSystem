<?php

require_once 'Observer.php';

interface subject {
    public function attach(Observer $observer);
    public function detach(Observer $observer);
    public function notify();
}
