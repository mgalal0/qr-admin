<?php
// Allow CORS only from avnology.com
$allowed_origin = "https://avnology.com";

if (isset($_SERVER['HTTP_ORIGIN'])) {
    if ($_SERVER['HTTP_ORIGIN'] === $allowed_origin) {
        header("Access-Control-Allow-Origin: $allowed_origin");
        header("Access-Control-Allow-Methods: POST, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type");
    } else {
        http_response_code(403);
        exit; // Exit if the origin is not allowed
    }
} else {
    http_response_code(403);
    exit; // Exit if the origin is not provided
}

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit; // Exit for preflight request
}

// Include the QR code generator library
include "phpqrcode-master/qrlib.php";

// Function to generate vCard QR code
function generateVCardQRCode($data) {
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

    $filename = time() . '_' . uniqid() . '.png';
    QRcode::png($vcard, $filename, QR_ECLEVEL_Q, 10);
    return $filename;
}

// Function to generate link QR code
function generateLinkQRCode($data) {
    $filename = time() . '_' . uniqid() . '.png';
    QRcode::png($data, $filename, QR_ECLEVEL_L, 10);
    return $filename;
}

// Function to delete old QR code files
function deleteOldQRCodeFiles($directory, $expiration = 10) {
    $files = glob($directory . '/*.png');
    $now = time();

    foreach ($files as $file) {
        if (is_file($file)) {
            $file_creation_time = filectime($file);
            if ($now - $file_creation_time >= $expiration) {
                unlink($file); // Delete the file
            }
        }
    }
}

// Register a shutdown function to delete old files after script execution
register_shutdown_function('deleteOldQRCodeFiles', __DIR__);

// Read JSON input
$input = json_decode(file_get_contents('php://input'), true);

if ($input) {
    if ($input['type'] === 'vcard') {
        $data = $input['data'];
        $data['social_media'] = explode("\n", $data['social_media']);
        $filename = generateVCardQRCode($data);
    } elseif ($input['type'] === 'link') {
        $data = $input['data'];
        $filename = generateLinkQRCode($data);
    } else {
        http_response_code(400);
        echo json_encode(["error" => "Invalid type"]);
        exit;
    }

    // Return the filename as a JSON response
    echo json_encode(["filename" => $filename]);
} else {
    http_response_code(400);
    echo json_encode(["error" => "Invalid input"]);
}
?>
