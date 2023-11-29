<?php
class EncryptClass {
  
    private $shifting; //the key use Substitution cipher
    private $desKey; // the key use for DES encryption
    private $desSize; // the size use for DES encryption

    public function __construct() {
        //assign the value to keys
        $this->shifting = 3;
        $this->desKey = decbin(ord("20"));
        $this->desSize = 8;
         //If directly use the encryption method for numbers, there will be problems in processing (for example, lost data after hexadecimal conversion)
        $this->numericTable = [
            '0' => 'က',
            '1' => 'ခ',
            '2' => 'ဂ',
            '3' => 'ဃ',
            '4' => 'င',
            '5' => 'စ',
            '6' => 'ဆ',
            '7' => 'ဇ',
            '8' => 'ဈ',
            '9' => 'ဉ',
        ];
    }

    //Main function to use multiple method encrypt the text
    public function encryption($plainText){
        $ancient_encryption_result = $this->substitutionEncryption($plainText);
        //return $ancient_encryption_result;
        $des_encryption_result = $this->desEncryption($ancient_encryption_result);
        //return $des_encryption_result;
        $transpostion_encryption_result = $this->transpositionEncryption($des_encryption_result,'ABCDEF');
        return $transpostion_encryption_result;
    }

    // Encrypt numeric data using custom numeric table
    private function encryptNumericData($numericData) {
        if (isset($this->numericTable[$numericData])) {
            return $this->numericTable[$numericData];
        } else {
            // Handle cases where the numeric value doesn't have a corresponding encryption value
            return $numericData;
        }
    }

    //Ancient Encryption -> Substitution cipher
    private function substitutionEncryption($text) {  
        $length = strlen($text);
        $encrypted_result = "";
    
        for ($i = 0; $i < $length; $i++) {
            $single_word = $text[$i];
            $toascii = ord($single_word);
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
        //coding....
        $text = str_replace(' ', '', $text);
        $keyLength = strlen($key);
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
        $sortedKey = str_split($key);
        asort($sortedKey);

        $EncryptedMessage = '';
        foreach ($sortedKey as $col) {
            $colIndex = array_search($col, str_split($key));
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
            $encrypt_result .= chr(bindec($encrypted_data));
        }

        return $encrypt_result;
    }

       //Symmetric Encrytion -> AES encryption
       private function aesEncryption($text){
            //code...
       }
}
