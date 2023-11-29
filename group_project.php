<?php
require 'EncryptClass_keith20231128.php';
require 'DecryptClass_keith20231128.php';

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

?>
<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TestPage</title>
</head>
<body>
    <!-- header area -->
    <header>
    </header>
    <!-- main area -->
    <main>
    <form method="post">
        Input:<input type="text" name="encrypt_input">
        <input type="submit" value="submit">
        <input type="reset" value="Reset" onclick="location.href='#'" /><br>
        Encryption value: <?php echo $encryption_result; ?>
    </form>
    <form method="post">
    Input:<input type="text" name="decrypt_input">
    <input type="submit" value="submit">
    <input type="reset" value="Reset" onclick="location.href='#'" /><br>
    Decryption value: <?php echo $decryption_result; ?>
</form>

    </main>
</body>
</html>