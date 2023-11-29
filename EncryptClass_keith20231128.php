<?php
class EncryptClass {
  
    private $shifting; //the key use Substitution cipher
    private $desKey; // the key use for DES encryption
    private $desSize; // the size use for DES encryption
    private $rod_diameter; //the key of skytale
    private $transpositionKey; //the key of Transposition
    public function __construct() {
        //assign the value to keys
        $this->shifting = 3;
        $this->desKey = decbin(ord("20"));
        $this->desSize = 8;
        $this->rod_diameter = 4;
        $this->transpositionKey = "ABCDEF";
    }

    //Main function to use multiple method encrypt the text
    public function encryption($plainText){
        $substitution_encryption_result = $this->substitutionEncryption($plainText);
        
        //$skytale_encryption_result = $this->skytaleEncryption($plainText);
        $des_encryption_result = $this->desEncryption($substitution_encryption_result);
       
        //$transposition_encryption_result = $this->transpositionEncryption($plainText);
        return $des_encryption_result;
        //$transpostion_encryption_result = $this->$transpositionEncryption($des_encryption_result,'ABCDEF');
        //return $transpostion_encryption_result;
    }

    //Ancient Encryption -> Substitution cipher
    private function substitutionEncryption($text) {  
        $limit_ascii_capital_letter = 122 - $this->shifting;
        $limit_ascii_smell_letter = 90 - $this->shifting;
        $length = strlen($text);
        $encrypted_result = "";

        for ($i = 0; $i < $length; $i++) {
            $single_word = $text[$i];
            $toascii = ord($single_word);
            if($toascii >= 65 && $toascii <= 90 || $toascii >=97 && $toascii <= 122)
            {
            if ($toascii >= $limit_ascii_smell_letter && $toascii <= 90 || $toascii >= $limit_ascii_capital_letter && $toascii <= 122) {
                $encrypted_result .= chr($toascii - (26 -  $this->shifting));
            } else {
                $encrypted_result .= chr($toascii +  $this->shifting);
            }
            }
            else if($toascii >= 48 && $toascii <= 57)
            {
                if ($toascii >= 48 && $toascii <= 51) {
                    $encrypted_result .= chr($toascii + (9 - $this->shifting));
                } else {
                    $encrypted_result .= chr($toascii - $this->shifting);
                }
            }
        
        }
        return $encrypted_result;
    }
    //Ancient Encryption -> Transposition  cipher
    private function transpositionEncryption($text){
        //coding....
        $text = str_replace(' ', '', $text);
        $keyLength = strlen($this->transpositionKey);
        $messageLength = strlen($text);
        
        // Calculate the number of rows required in the grid
        $numRows = ceil($messageLength / $keyLength);

        // Create an empty grid
        $grid = array();
        for ($i = 0; $i < $numRows; $i++) {
            $grid[$i] = array();
        }
        $index = 0;
        for ($row = 0; $row < $numRows; $row++) {
            for ($col = 0; $col < $keyLength; $col++) {
                if ($index < $messageLength) {
                    $grid[$row][$col] = $text[$index];
                    $index++;
                } else {
                    $grid[$row][$col] = '';
                }
            }
        }
        // Rearrange the columns based on the key
        $sortedKey = str_split($this->transpositionKey);
        asort($sortedKey);

        $EncryptedMessage = '';
        foreach ($sortedKey as $col) {
            $colIndex = array_search($col, str_split($this->transpositionKey));
            for ($row = 0; $row < $numRows; $row++) {
                $EncryptedMessage .= $grid[$row][$colIndex];
            }
        }

        return $EncryptedMessage;
    }

    //Symmetric Encrytion -> DES encryption
    private function desEncryption($text)
    {
        $Des_paddingChar = $this->desSize  - (strlen($this->desKey) % $this->desSize );
        $Des_bin = str_repeat('0', $Des_paddingChar) . $this->desKey;

        $length = strlen($text);
        $result_binary = "";
        $encrypted_data = "";
        $full_bin = "";
        $encrypt_result = "";
        $result_full_binary = "";

        for ($i = 0; $i < $length; $i++) {
            $single_word = $text[$i];
            $toascii = ord($single_word);
            $tobin = decbin($toascii);

            $paddingChar = $this->desSize  - (strlen($tobin) % $this->desSize );
            $full_bin = str_repeat('0', $paddingChar) . $tobin;
            $result_full_binary = "";
            for ($j = 0; $j < $this->desSize ; $j++) {
                $single_request_value = $full_bin[$j];
                $single_key_value = $Des_bin[$j];
                $single_result_binary = ($single_key_value xor $single_request_value) ? '1' : '0';
                $result_full_binary .= $single_result_binary;
            }
            $encrypted_data = $result_full_binary;
            $encrypted_dec = bindec($encrypted_data);
            if ($encrypted_dec > 10){
                $encrypt_result .= chr($encrypted_dec);
            }
            else {
                $encrypt_result .= $encrypted_dec;
            }
        }

        return $encrypt_result;
    }

       //Symmetric Encrytion -> skytale encryption
       private function skytaleEncryption($text){
            $ciphertext = "";
            $length = strlen($text);
            $numRows = ceil($length / $this->rod_diameter);
        
            $text = str_pad($text, $numRows * $this->rod_diameter, "*");
        
            for ($i = 0; $i < $this->rod_diameter; $i++) {
                for ($j = 0; $j < $numRows; $j++) {
                    $ciphertext .= $text[$j * $this->rod_diameter + $i];
                }
            }
        
            return $ciphertext;
       }
}
