<?php
class DecryptClass {
  
    private $shifting; //the key use Substitution cipher
    private $desKey; // the key use for DES encryption
    private $desSize; // the size use for DES encryption
    private $numericTable; // the table used for numeric encryption
    
    public function __construct() {
        //assign the value to keys
        $this->shifting = 3;
        $this->desKey = decbin(ord("20"));
        $this->desSize = 8;
        //If directly use the encryption method for numbers, there will be problems in processing (for example, lost data after hexadecimal conversion)
        $this->numericTable = [
            'က' => '0',
            'ခ' => '1',
            'ဂ' => '2',
            'ဃ' => '3',
            'င' => '4',
            'စ' => '5',
            'ဆ' => '6',
            'ဇ' => '7',
            'ဈ' => '8',
            'ဉ' => '9',
        ];
    }

    //Main function to use multiple method decrypt the text
    public function decryption($plainText) {
        $plainText = mb_convert_encoding($plainText, 'UTF-8');
    
        $decrypted_result = '';
        $length = mb_strlen($plainText);
        for ($i = 0; $i < $length; $i++) {
            $char = mb_substr($plainText, $i, 1);
            if ($this->isCustomNumeric($char)) {
                // If it is a number, use the decryptNumericData method to decrypt it
                $decrypted_result .= $this->decryptNumericData($char);
            } else {
                // If it is a character, first use the desDecryption method to decrypt it
                $des_decryption_result = $this->desDecryption($char);
    
                // Then use substitutionDecryption method to decrypt
                $decrypted_result .= $this->substitutionDecryption($des_decryption_result);
            }
        }
        return $decrypted_result;
    }

    private function isCustomNumeric($char) {
        return isset($this->numericTable[$char]);
    }
    private function decryptNumericData($numericData) {
        if (isset($this->numericTable[$numericData])) {
            return $this->numericTable[$numericData];
        } else {
            // Handle cases where the numeric value doesn't have a corresponding encryption value
            return $numericData;
        }
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
                if ($toascii <= $limit_ascii_smell_letter && $toascii >= 65 || $toascii <= $limit_ascii_capital_letter && $toascii >= 97) {
                    $decrypted_result .= chr($toascii + (26 - $this->shifting));
                } else {
                    $decrypted_result .= chr($toascii - $this->shifting);
                }
        }
        
        return $decrypted_result;
    }

    //Ancient Decryption -> Transposition cipher
    private function transpositionDecryption($text){
        //coding....
    }

    //Symmetric Decryption -> DES decryption
    private function desDecryption($text)
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

    //Symmetric Decryption -> AES decryption
    private function aesDecryption($text){
        //code...
    }
}
