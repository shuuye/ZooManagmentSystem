<?php

    class InputValidationCtrl{
        // Sanitize input data
        public static function prepareInput($data) {
            // Remove extra spaces from both ends of a string
            $data = trim($data);
            // Remove backslashes
            $data = stripslashes($data);
            // Convert special characters to HTML entities
            $data = htmlspecialchars($data);
            
            return $data;
        }
        
        public static function inputIsEmptyValidation($input){ //check empty
            $preparedInput = self::prepareInput($input);
            if (is_null($preparedInput) || $preparedInput === '') {
            //if(empty($input)){
                return true;
            }
            return false;
        }
        
        public static function inputFormatValidation($input, $format){
            $preparedInput = self::prepareInput($input);
            
            if (preg_match($format, $preparedInput)) { // match with the pattern set
                return true;
            }
            return false;
        }
        
        public static function inputMinLengthValidation($input, $minOfLength){
            $preparedInput = self::prepareInput($input);
            
            if (strlen($preparedInput) < $minOfLength) { ///check the input charater length with minimum
                return false;
            }
            return true;
        }
        
        public static function inputMaxLengthValidation($input, $maxOfLength){
            $preparedInput = self::prepareInput($input);
            
            if (strlen($preparedInput) < $maxOfLength) { ///check the input charater length with maximum
                return true;
            }
            return false;
        }
        
        public static function inputMatchValidation($input, $comparingVar){
            $preparedInput = self::prepareInput($input);
            
            if ($preparedInput == $comparingVar) { // compare between two variable
                return true;
            }
            return false;
        }
        
        public static function inputFilterValidation($input, $filterVar){
            $preparedInput = self::prepareInput($input);
            
            if (filter_var($preparedInput, $filterVar)) { // validate the input filter
                return true;
            }
            return false;
        }
    }

