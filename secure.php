<?php
//A page to control the encrypt and decrypt
require 'EncryptClass.php';
require 'DecryptClass.php';

$encrypt_request = isset($_POST['encrypt_input']) ? $_POST['encrypt_input'] : "";
$decrypt_request = isset($_POST['decrypt_input']) ? $_POST['decrypt_input'] : "";

if (isset($encrypt_request)) {
    $obj = new EncryptClass();
    $encryption_result = $obj->encryption($encrypt_request);
   /*  $ancient_encryption_result = ancientEncryption($encrypt_request);
    $des_encryption_result = DESencryption($ancient_encryption_result); */
}

if (isset($decrypt_request)) {
    $obj = new DecryptClass();
    $decryption_result = $obj->decryption($decrypt_request);
    /* $des_decryption_result = DESdecryption($decrypt_request);
    $ancient_decryption_result = ancientDecryption($des_decryption_result); */
} 