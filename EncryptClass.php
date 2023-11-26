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
    }

    //Main function to use multiple method encrypt the text
    public function encryption($plainText){
        $ancient_encryption_result = $this->substitutionEncryption($plainText);
        $des_encryption_result = $this->desEncryption($ancient_encryption_result);
        return $des_encryption_result;
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

            if ($toascii >= $limit_ascii_smell_letter && $toascii <= 90 || $toascii >= $limit_ascii_capital_letter && $toascii <= 122) {
                $encrypted_result .= chr($toascii - (26 -  $this->shifting));
            } else {
                $encrypted_result .= chr($toascii +  $this->shifting);
            }
        }

        return $encrypted_result;
    }

    //Ancient Encryption -> Transposition  cipher
    private function transpositionEncryption($text){
        //coding....
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
