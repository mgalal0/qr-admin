<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Allow CORS from any origin (or specify the frontend origin)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204); // No Content
    exit;
}

// Function to get all verification codes
function getAllVerificationCodes() {
    $file = 'codes.json';
    if (file_exists($file)) {
        $codes = json_decode(file_get_contents($file), true);
        if (is_array($codes)) {
            return $codes;
        }
    }
    return [];
}

// Get and return all verification codes
$allCodes = getAllVerificationCodes();
echo json_encode(["codes" => $allCodes]);
?>
