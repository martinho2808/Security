<?php
class DecryptClass {
  
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

    //Main function to use multiple method decrypt the text
    public function decryption($plainText){
        $transposition_decryption_result = $this->transpositionDecryption($plainText,'ABCDEF');
        $des_decryption_result = $this->desEncryption($transposition_decryption_result);
        $substitution_decryption_result = $this->substitutionDecryption($des_decryption_result);
        $skytale_decryption_result = $this->skytaleDecryption($substitution_decryption_result);
        return $skytale_decryption_result;
        
        //$transposition_decryption_result = $this->transpositionDecryption($ancient_decryption_result,'ABCDEF');
        //return $transposition_decryption_result;
    }

    //Ancient Decryption -> Substitution cipher
    private function substitutionDecryption($text) {  
        $length = strlen($text);
        $decrypted_result = "";
    // Use a forloop to add the content text that needs to be decrypted one by one and perform substitution separately.
        for ($i = 0; $i < $length; $i++) {
            $single_word = $text[$i];
            $toascii = ord($single_word);
            if ($toascii >= 65 && $toascii <= 90) {
                $decrypted_result .= chr(($toascii - 65 - $this->shifting + 26) % 26 + 65);
            } elseif ($toascii >= 97 && $toascii <= 122) {
                $decrypted_result .= chr(($toascii - 97 - $this->shifting + 26) % 26 + 97);
            } else {
                $decrypted_result .= $single_word;
            }
        }
        
        return $decrypted_result;
    }
    

    //Ancient Decryption -> Transposition  cipher
    private function transpositionDecryption($ciphertext, $key){
        //Convert the key to an array, and then arrange the keys in alphabetical order
        $keyArr = str_split($key);
        sort($keyArr);
        // Calculate the length of content and key
        $keyLength = count($keyArr);
        $textLength = strlen($ciphertext);
    
        // Calculate the number of rows required
        $numRows = ceil($textLength / $keyLength);
    
        //Create an empty grid
        $grid = array();
        for ($i = 0; $i < $numRows; $i++) {
            $grid[$i] = array_fill(0, $keyLength, '');
        }
    
        // Fill the grid with ciphertext in columns
        $index = 0;
        for ($col = 0; $col < $keyLength; $col++) {
            $colIndex = array_search($keyArr[$col], $keyArr);
            for ($row = 0; $row < $numRows; $row++) {
                if ($index < $textLength) {
                    $grid[$row][$colIndex] = $ciphertext[$index];
                    $index++;
                } else {
                    break;
                }
            }
        }
    
        // Read the characters in the grid in the order of the key to form plain text
        $plaintext = '';
        for ($row = 0; $row < $numRows; $row++) {
            for ($col = 0; $col < $keyLength; $col++) {
                $plaintext .= $grid[$row][$col];
            }
        }
    
        // Remove fill characters
        $plaintext = rtrim($plaintext, '_');
    
        return $plaintext;
    }

    //Symmetric Decrytion -> DES decryption
    private function desEncryption($text)
    {
        $Des_paddingChar = $this->desSize - (strlen($this->desKey) % $this->desSize);
        $Des_bin = str_repeat('0', $Des_paddingChar) . $this->desKey;

        $length = strlen($text);
        $result_binary = "";
        $decrypted_data = "";
        $full_bin = "";
        $decrypt_result = "";
        $result_full_binary = "";

        for ($i = 0; $i < $length; $i++) {
            $single_word = $text[$i];
            $toascii = ord($single_word);
            $tobin = decbin($toascii);

            $paddingChar = $this->desSize - (strlen($tobin) % $this->desSize);
            $full_bin = str_repeat('0', $paddingChar) . $tobin;

            $result_full_binary = "";

            for ($j = 0; $j < $this->desSize; $j++) {
                $single_request_value = $full_bin[$j];
                $single_key_value = $Des_bin[$j];
                $single_result_binary = ($single_key_value xor $single_request_value) ? '1' : '0';
                $result_full_binary .= $single_result_binary;
            }

            $decrypted_data = $result_full_binary;
            $decrypt_result .= chr(bindec($decrypted_data));
        }

        return $decrypt_result;
    }

       //Symmetric Decrytion -> Skytale decryption
       private function skytaleDecryption($ciphertext){
        $plaintext = "";
        $length = strlen($ciphertext);

        // Calculate the number of rows needed on the imaginary rod
        $numRows = ceil($length / $this->rod_diameter);

        // Read the message from the rod column by column to decrypt
        for ($i = 0; $i < $numRows; $i++) {
            for ($j = 0; $j < $this->rod_diameter; $j++) {
                $position = $j * $numRows + $i;
                if ($position < $length) {
                    $plaintext .= substr($ciphertext,$position,1);
                // $plaintext .= $ciphertext[$position];
                }
            }
        }

        return rtrim($plaintext, "_");
   }
}
