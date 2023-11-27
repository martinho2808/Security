<?php
class DecryptClass {
  
    private $shifting; //the key use Substitution cipher
    private $desKey; // the key use for DES encryption
    private $desSize; // the size use for DES encryption

    public function __construct() {
        //assign the value to keys
        $this->shifting = 3;
        $this->desKey = decbin(ord("20"));
        $this->desSize = 8;
    }

    //Main function to use multiple method decrypt the text
    public function decryption($plainText){
        $des_decryption_result = $this->desEncryption($plainText);
        //return $des_decryption_result;
        $ancient_decryption_result = $this->substitutionDecryption($des_decryption_result);
        //$ancient_decryption_result = $this->substitutionDecryption($plainText);
        return $ancient_decryption_result;
    }

    //Ancient Decryption -> Substitution cipher
    private function substitutionDecryption($text) {  
        $limit_ascii_capital_letter = 64 + $this->shifting; /*68 */
        $limit_ascii_smell_letter = 96 + $this->shifting; /*100 */
        $length = strlen($text);
        $decrypted_result = "";
    
        for ($i = 0; $i < $length; $i++) {
            $single_word = $text[$i];
            $toascii = ord($single_word);
        if($toascii >= 65 && $toascii <= 90 || $toascii >=97 && $toascii <= 122)
        {
            if ($toascii <= $limit_ascii_smell_letter && $toascii >= 65 || $toascii <= $limit_ascii_capital_letter && $toascii >= 97) {
                $decrypted_result .= chr($toascii + (26 - $this->shifting));
            } else {
                $decrypted_result .= chr($toascii - $this->shifting);
            }
        }
        else if($toascii >= 48 && $toascii <= 57)
        {
            if ($toascii >= 54 && $toascii <= 57) {
                $decrypted_result .= chr($toascii - (9 - $this->shifting));
            } else {
                $decrypted_result .= chr($toascii + $this->shifting);
            }
        }
        }
        return $decrypted_result;
    }
    

    //Ancient Decryption -> Transposition  cipher
    private function transpositionDecryption($text){
        //coding....
    }

    //Symmetric Decrytion -> DES decryption
    private function desEncryption($text)
    {
        $Des_paddingChar = $this->desKey - (strlen($this->desKey) % $this->desKey);
        $Des_bin = str_repeat('0', $Des_paddingChar) . $this->desKey;

        $length = strlen($text);
        $result_binary = "";
        $decrypted_data = "";
        $full_bin = "";
        $decrypt_result = "";
        $result_full_binary = "";

        for ($i = 0; $i < $length; $i++) {
            $single_word = $text[$i];
            if($single_word === "@")
            {
                
            }
            else{
            $toascii = ord($single_word);
            $tobin = decbin($toascii);

            $paddingChar = $this->desKey - (strlen($tobin) % $this->desKey);
            $full_bin = str_repeat('0', $paddingChar) . $tobin;

            $result_full_binary = "";

            for ($j = 0; $j < $this->desKey; $j++) {
                $single_request_value = $full_bin[$j];
                $single_key_value = $Des_bin[$j];
                $single_result_binary = ($single_key_value xor $single_request_value) ? '1' : '0';
                $result_full_binary .= $single_result_binary;
            }

            $decrypted_data = $result_full_binary;
            $decrypted_dec = bindec($decrypted_data);
            if ($decrypted_dec > 10){
                $decrypt_result .= chr($decrypted_dec);
            }
            else {
                $decrypt_result .= $decrypted_dec;
            }
        }
    }
        return $decrypt_result;
    }

       //Symmetric Decrytion -> AES decryption
       private function aesDecryption($text){
            //code...
       }
}
