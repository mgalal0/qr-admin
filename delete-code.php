<?php
// Allow CORS from any origin (or specify the frontend origin)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Define your secret key
$adminSecretKey = "m1"; // Replace with your actual secret key

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204); // No Content
    exit;
}

// Function to delete a verification code by index
function deleteVerificationCode($index) {
    $file = 'codes.json';
    if (file_exists($file)) {
        $codes = json_decode(file_get_contents($file), true);
        if (is_array($codes) && isset($codes[$index])) {
            array_splice($codes, $index, 1);
            file_put_contents($file, json_encode($codes, JSON_PRETTY_PRINT));
            return true;
        }
    }
    return false;
}

// Read JSON input
$input = json_decode(file_get_contents('php://input'), true);

if ($input && isset($input['index']) && isset($input['secret_key'])) {
    $index = intval($input['index']);
    $secretKey = $input['secret_key'];

    // Check if the secret key matches
    if ($secretKey === $adminSecretKey) {
        if (deleteVerificationCode($index)) {
            echo json_encode(["message" => "Verification code deleted successfully"]);
        } else {
            http_response_code(400);
            echo json_encode(["error" => "Invalid index or code not found"]);
        }
    } else {
        http_response_code(403); // Forbidden
        echo json_encode(["error" => "Invalid secret key"]);
    }
} else {
    http_response_code(400);
    echo json_encode(["error" => "Invalid input"]);
}
?>
