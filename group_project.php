<?php

$encrypt_request = isset($_POST['encrypt_input']) ? $_POST['encrypt_input'] : "";
$decrypt_request = isset($_POST['decrypt_input']) ? $_POST['decrypt_input'] : "";
/* Encryption functions */
function ancientEncryption($encrypt_request)
{
    $shifting = 3;
    $limit_ascii_capital_letter = 122 - $shifting;
    $limit_ascii_smell_letter = 90 - $shifting;
    $length = strlen($encrypt_request);
    $encrypted_result = "";

    for ($i = 0; $i < $length; $i++) {
        $single_word = $encrypt_request[$i];
        $toascii = ord($single_word);

        if ($toascii >= $limit_ascii_smell_letter && $toascii <= 90 || $toascii >= $limit_ascii_capital_letter && $toascii <= 122) {
            $encrypted_result .= chr($toascii - (26 - $shifting));
        } else {
            $encrypted_result .= chr($toascii + $shifting);
        }
    }

    return $encrypted_result;
}

function DESencryption($encrypt_request)
{
    $Des_key = decbin(ord("20"));
    $Des_Size = 8;
    $Des_paddingChar = $Des_Size - (strlen($Des_key) % $Des_Size);
    $Des_bin = str_repeat('0', $Des_paddingChar) . $Des_key;

    $length = strlen($encrypt_request);
    $result_binary = "";
    $encrypted_data = "";
    $full_bin = "";
    $encrypt_result = "";
    $result_full_binary = "";

    for ($i = 0; $i < $length; $i++) {
        $single_word = $encrypt_request[$i];
        $toascii = ord($single_word);
        $tobin = decbin($toascii);

        $paddingChar = $Des_Size - (strlen($tobin) % $Des_Size);
        $full_bin = str_repeat('0', $paddingChar) . $tobin;
        $result_full_binary = "";

        for ($j = 0; $j < $Des_Size; $j++) {
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

if (isset($encrypt_request)) {
    $ancient_encryption_result = ancientEncryption($encrypt_request);
    $des_encryption_result = DESencryption($ancient_encryption_result);
}

/* Decryption functions */
function ancientDecryption($encrypted_request)
{
    $shifting = 3;
    $limit_ascii_capital_letter = 65 + $shifting;
    $limit_ascii_smell_letter = 97 + $shifting;
    $length = strlen($encrypted_request);
    $decrypted_result = "";

    for ($i = 0; $i < $length; $i++) {
        $single_word = $encrypted_request[$i];
        $toascii = ord($single_word);

        if ($toascii >= $limit_ascii_smell_letter && $toascii <= 65 || $toascii >= $limit_ascii_capital_letter && $toascii <= 97) {
            $decrypted_result .= chr($toascii + (26 - $shifting));
        } else {
            $decrypted_result .= chr($toascii - $shifting);
        }
    }

    return $decrypted_result;
}

function DESdecryption($decrypt_request){
    $Des_key = decbin(ord("20"));
    $Des_Size = 8;
    $Des_paddingChar = $Des_Size - (strlen($Des_key) % $Des_Size);
    $Des_bin = str_repeat('0', $Des_paddingChar) . $Des_key;

    $length = strlen($decrypt_request);
    $result_binary = "";
    $decrypted_data = "";
    $full_bin = "";
    $decrypt_result = "";
    $result_full_binary = "";

    for ($i = 0; $i < $length; $i++) {
        $single_word = $decrypt_request[$i];
        $toascii = ord($single_word);
        $tobin = decbin($toascii);

        $paddingChar = $Des_Size - (strlen($tobin) % $Des_Size);
        $full_bin = str_repeat('0', $paddingChar) . $tobin;

        $result_full_binary = "";

        for ($j = 0; $j < $Des_Size; $j++) {
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

if (isset($decrypt_request)) {
    $des_decryption_result = DESdecryption($decrypt_request);
    $ancient_decryption_result = ancientDecryption($des_decryption_result);
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
        Encryption value: <?php echo $des_encryption_result; ?>
    </form>
    <form method="post">
    Input:<input type="text" name="decrypt_input">
    <input type="submit" value="submit">
    <input type="reset" value="Reset" onclick="location.href='#'" /><br>
    Decryption value: <?php echo $ancient_decryption_result; ?>
</form>

    </main>
</body>
</html>