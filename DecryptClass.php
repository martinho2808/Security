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
        $des_decryption_result = $this->desdecryption($plainText);
        //return $des_decryption_result;
        $ancient_decryption_result = $this->substitutionDecryption($des_decryption_result);
        //$ancient_decryption_result = $this->substitutionDecryption($plainText);
        //return $ancient_decryption_result;
        
        $transposition_decryption_result = $this->transpositionDecryption($ancient_decryption_result,'ABCDEF');
        return $transposition_decryption_result;
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
        $length = strlen($text);
        $decrypted_result = "";
    
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

    //Ancient Decryption -> Transposition cipher
    private function transpositionDecryption($text,$key){
        //coding....
        $text = str_replace(' ', '', $text);
        $keyLength = strlen($key);
        $encryptedLength = strlen($text);

        // Calculate the number of rows required in the grid
        $numRows = ceil($encryptedLength / $keyLength);

        // Calculate the number of empty cells
        $numEmptyCells = ($numRows * $keyLength) - $encryptedLength;

        // Rearrange the columns based on the key
        $sortedKey = str_split($key);
        asort($sortedKey);

        // Calculate the number of cells in each column
        $colCounts = array();
        foreach ($sortedKey as $col) {
            $colCounts[] = $numRows - (substr_count($key, $col) - 1);
        }
        // Create an empty grid
        $grid = array();
        for ($i = 0; $i < $numRows; $i++) {
            $grid[$i] = array();
        }
        $index = 0;
        foreach ($sortedKey as $col) {
            $colIndex = array_search($col, str_split($key));
            $colCount = $colCounts[$colIndex];
            for ($row = 0; $row < $colCount; $row++) {
                if ($index < $encryptedLength) {
                    $grid[$row][$colIndex] = $text[$index];
                    $index++;
                } else {
                    break;
                }
            }
        }
        // Read the decrypted message from the grid
        $decryptedMessage = '';
        for ($row = 0; $row < $numRows; $row++) {
            for ($col = 0; $col < $keyLength; $col++) {
                if (isset($grid[$row][$col])) {
                    $decryptedMessage .= $grid[$row][$col];
                }
            }
        }
        return $decryptedMessage;
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
