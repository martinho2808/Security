<?php

$encrypt_request = isset($_POST['encrypt_input']) ? $_POST['encrypt_input'] : "";
$decrypt_request = isset($_POST['decrypt_input']) ? $_POST['decrypt_input'] : "";
/* Encryption functions */



$plaintext = "HELPMEIAMUNDERATTACK";
$rod_diameter = 4; // Change this value to adjust the Scytale rod diameter

if (isset($encrypt_request)) {
    $rsa_encryption_result = encrypt_scytale($encrypt_request, $rod_diameter);
    
}

function encrypt_scytale($plaintext, $rod_diameter) {
    $ciphertext = "";
    $length = strlen($plaintext);

    // Calculate the number of rows needed on the imaginary rod
    $numRows = ceil($length / $rod_diameter);

    // Pad the plaintext if necessary to make it fit evenly on the rod
    $plaintext = str_pad($plaintext, $numRows * $rod_diameter, "_");
    
    // Write the message on the rod column by column
    for ($i = 0; $i < $rod_diameter; $i++) {
        for ($j = 0; $j < $numRows; $j++) {
            $ciphertext .= $plaintext[$j * $rod_diameter + $i];
        }
    }

    return $ciphertext;
}
function decrypt_scytale($ciphertext, $rod_diameter) {
    $plaintext = "";
    $length = strlen($ciphertext);

    // Calculate the number of rows needed on the imaginary rod
    $numRows = ceil($length / $rod_diameter);

    // Read the message from the rod column by column to decrypt
    for ($i = 0; $i < $numRows; $i++) {
        for ($j = 0; $j < $rod_diameter; $j++) {
            $position = $j * $numRows + $i;
            if ($position < $length) {
                $plaintext .= substr($ciphertext,$position,1);
               // $plaintext .= $ciphertext[$position];
            }
        }
    }

    return rtrim($plaintext, "_");
}

if (isset($decrypt_request)) {
    $decryption_result = decrypt_scytale($decrypt_request, $rod_diameter);
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
        Encryption value: <?php echo $rsa_encryption_result; ?>
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