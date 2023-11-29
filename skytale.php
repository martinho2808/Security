<?php

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
                $plaintext .= $ciphertext[$position];
            }
        }
    }

    return rtrim($plaintext, "_");
}

// Example usage
$plaintext = "HELPMEIAMUNDERATTACK";
$rod_diameter = 4; // Change this value to adjust the Scytale rod diameter

// Encrypt the password using the Scytale cipher
$ciphertext = encrypt_scytale($plaintext, $rod_diameter);
echo "Encrypted Password: " . $ciphertext . "\n";

// Decrypt the password using the Scytale cipher
$decrypted_password = decrypt_scytale($ciphertext, $rod_diameter);
echo "Decrypted Password: " . $decrypted_password . "\n";

?>
