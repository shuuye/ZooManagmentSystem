<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of subject
 *
 * @author chaiy
 */

require_once 'Observer.php';

interface Subject {
    public function attach(Observer $observer);
    public function detach(Observer $observer);
    public function notify();
}
