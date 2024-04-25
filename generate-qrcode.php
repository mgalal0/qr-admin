<?php
// Include the QR code generator library
include "phpqrcode-master/qrlib.php";

// Function to generate QR code
function generateQRCode($data) {
    // Format data as vCard
    $vcard = "BEGIN:VCARD\n";
    $vcard .= "VERSION:3.0\n";
    $vcard .= "FN:" . $data['first_name'] . " " . $data['last_name'] . "\n";
    $vcard .= "TEL:" . $data['phone_number'] . "\n";
    $vcard .= "TITLE:" . $data['job_title'] . "\n";
    $vcard .= "EMAIL:" . $data['email'] . "\n";
    foreach ($data['social_media'] as $link) {
        $vcard .= "URL:" . $link . "\n";
    }
    $vcard .= "END:VCARD";

    // Generate a unique filename for the QR code image
    $filename = uniqid() . '.png';

    // Generate QR code and save it to a file
    QRcode::png($vcard, $filename);

    // Return the filename
    return $filename;
}

// Check if form data is received
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Form data
    $data = array(
        'first_name' => $_POST['first_name'],
        'last_name' => $_POST['last_name'],
        'phone_number' => $_POST['phone_number'],
        'job_title' => $_POST['job_title'],
        'email' => $_POST['email'],
        'social_media' => explode("\n", $_POST['social_media'])
    );

    // Generate QR code
    $qrCodeFilename = generateQRCode($data);

    // Return the filename as a response
    echo $qrCodeFilename;
    exit; // Stop further execution
}
?>
