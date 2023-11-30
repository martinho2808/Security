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
        $skytale_encryption_result = $this->skytaleEncryption($plainText);
        $substitution_encryption_result = $this->substitutionEncryption($skytale_encryption_result);
        $des_encryption_result = $this->desEncryption($substitution_encryption_result);
       
        $transposition_encryption_result = $this->transpositionEncryption($des_encryption_result,'ABCDEF');
        return $transposition_encryption_result;
        //$transpostion_encryption_result = $this->$transpositionEncryption($des_encryption_result,'ABCDEF');
        //return $transpostion_encryption_result;
    }

    //Ancient Encryption -> Substitution cipher
    private function substitutionEncryption($text) {  
        //Calculate the length of $text and use it as a loop
        $length = strlen($text);
        $encrypted_result = "";
        // Use a forloop to add the content text that needs to be encrypted one by one and perform substitution separately.
        for ($i = 0; $i < $length; $i++) {
            $single_word = $text[$i];
            //To perform Substitution, you need to convert characters into ascii format.
            $toascii = ord($single_word);
            //Determine whether it will jump to an ASCII value other than the English word based on the displacement amount. If so, return to the beginning of the English word.
            if ($toascii >= 65 && $toascii <= 90) {
                $encrypted_result .= chr(($toascii - 65 + $this->shifting) % 26 + 65);
            } elseif ($toascii >= 97 && $toascii <= 122) {
                $encrypted_result .= chr(($toascii - 97 + $this->shifting) % 26 + 97);
            } else {
                $encrypted_result .= $single_word;
            }
        }
        
        return $encrypted_result;
    }
    //Ancient Encryption -> Transposition  cipher
    private function transpositionEncryption($text,$key){
        if (empty($text)) {
            return '';
        }
        //Convert the key to an array, and then arrange the keys in alphabetical order
        $keyArr = str_split($key);
        sort($keyArr);
        // Calculate the length of content and key
        $keyLength = strlen($key);
        $textLength = strlen($text);
    
        // use mod to Calculate the number of padding characters that need to be added
        $paddingLength = $keyLength - ($textLength % $keyLength);
    
        // Add filler characters 
        $text .= str_repeat('_', $paddingLength);
    
        // Store $text in an array
        $textArr = str_split($text);
    
        //Create an empty array
        $empty_array = array();

        for ($i = 0; $i < $keyLength; $i++) {
            $empty_array[$keyArr[$i]] = array();
        }
    
        // Fill the characters of $text into the grid in order by sort($keyArr);
        for ($i = 0; $i < $textLength + $paddingLength; $i++) {
            $keyIndex = $i % $keyLength;
            $grid[$keyArr[$keyIndex]][] = $textArr[$i];
        }
    
        // Generate characters in the grid in order to form ciphertext
        $ciphertext = '';
        foreach ($keyArr as $keyChar) {
            $ciphertext .= implode('', $grid[$keyChar]);
        }
    
        return $ciphertext;
    }

    //Symmetric Encrytion -> DES encryption
    private function desEncryption($text)
    {
        //Use an encryption method similar to the Transposition encryption method, because the redundant binary 0s will be omitted. Because des key assumes that you can enter any value instead of directly entering 8-digit binary
        $Des_paddingChar = $this->desSize  - (strlen($this->desKey) % $this->desSize );
        $Des_bin = str_repeat('0', $Des_paddingChar) . $this->desKey;
        //Calculating the length of the content to be encrypted requires processing word by word just like substitution.
        $length = strlen($text);
        $result_binary = "";
        $encrypted_data = "";
        $full_bin = "";
        $encrypt_result = "";
        $result_full_binary = "";
        //
        for ($i = 0; $i < $length; $i++) {
            $single_word = $text[$i];
            $toascii = ord($single_word);
            $tobin = decbin($toascii);
            //Use an encryption method similar to the Transposition encryption method, because the redundant binary 0s will be omitted, which will affect the result of dsekey encryption, so it needs to be filled to 8bit
            $paddingChar = $this->desSize  - (strlen($tobin) % $this->desSize );
            $full_bin = str_repeat('0', $paddingChar) . $tobin;
            $result_full_binary = "";
            //After the symbol is converted to the 8-bit binary full_bin, use a for loop to perform xor on the 8 bits separately. If true, it becomes 1, and if false, it becomes 0. And use $result_full_binary .= to store until the 8-bit data is obtained after the for loop is completed.
            for ($j = 0; $j < $this->desSize ; $j++) {
                $single_request_value = $full_bin[$j];
                $single_key_value = $Des_bin[$j];
                $single_result_binary = ($single_key_value xor $single_request_value) ? '1' : '0';
                $result_full_binary .= $single_result_binary;
            }
            $encrypted_data = $result_full_binary;
            //Convert the returned binary data back to decimal, and then convert the character format
            $encrypt_result .= chr(bindec($encrypted_data));
        }

        return $encrypt_result;
    }

       //Symmetric Encrytion -> skytale encryption
       private function skytaleEncryption($text){
            $ciphertext = "";
            $length = strlen($text);
            $numRows = ceil($length / $this->rod_diameter);
        
            $text = str_pad($text, $numRows * $this->rod_diameter, "_");
        
            for ($i = 0; $i < $this->rod_diameter; $i++) {
                for ($j = 0; $j < $numRows; $j++) {
                    $ciphertext .= $text[$j * $this->rod_diameter + $i];
                }
            }
        
            return $ciphertext;
       }
}
