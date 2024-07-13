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

// Function to generate and return a new verification code
function generateNewVerificationCode($newCode) {
    // Store $newCode securely or implement logic to manage it
    file_put_contents('codes.json', json_encode(['code' => $newCode])); // Store new code
    return $newCode;
}

// Read JSON input
$input = json_decode(file_get_contents('php://input'), true);

if ($input && isset($input['code']) && isset($input['secret_key'])) {
    $newCode = $input['code'];
    $secretKey = $input['secret_key'];

    // Check if the secret key matches
    if ($secretKey === $adminSecretKey) {
        generateNewVerificationCode($newCode);
        echo json_encode(["message" => "Verification code updated successfully", "code" => $newCode]);
    } else {
        http_response_code(403); // Forbidden
        echo json_encode(["error" => "Invalid secret key"]);
    }
} else {
    http_response_code(400);
    echo json_encode(["error" => "Invalid input"]);
}
?>
