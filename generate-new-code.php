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
    $file = 'codes.json';
    
    // Check if the file exists and is readable
    if (file_exists($file)) {
        $currentData = json_decode(file_get_contents($file), true);
        if (!is_array($currentData)) {
            $currentData = [];
        }
    } else {
        $currentData = [];
    }

    // Append the new code with a timestamp
    $currentData[] = [
        'code' => $newCode,
        'created_at' => date('Y-m-d H:i:s')
    ];

    // Store the updated codes array
    file_put_contents($file, json_encode($currentData, JSON_PRETTY_PRINT));
    return $newCode;
}

// Read JSON input
$input = json_decode(file_get_contents('php://input'), true);

if ($input && isset($input['code']) && isset($input['secret_key'])) {
    $newCode = trim($input['code']);
    $secretKey = $input['secret_key'];

    // Check if the secret key matches
    if ($secretKey === $adminSecretKey) {
        if (!empty($newCode)) {
            generateNewVerificationCode($newCode);
            echo json_encode(["message" => "Verification code added successfully", "code" => $newCode]);
        } else {
            http_response_code(400);
            echo json_encode(["error" => "Code cannot be empty"]);
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
