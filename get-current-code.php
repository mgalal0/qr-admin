<?php
// Allow CORS from any origin (or specify the frontend origin)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204); // No Content
    exit;
}

// Function to get the current verification code
function getVerificationCode() {
    $codes = json_decode(file_get_contents('codes.json'), true); // Retrieve codes from file
    return $codes['code'] ?? ''; // Return current code or empty string if not found
}

// Get and return the current verification code
$currentCode = getVerificationCode();
echo json_encode(["code" => $currentCode]);
?>
