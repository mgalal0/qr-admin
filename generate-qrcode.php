<?php
// Allow CORS from any origin (or specify the frontend origin)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204); // No Content
    exit;
}

// Include the QR code generator library
include "phpqrcode-master/qrlib.php";
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Function to generate vCard QR code and save data to Excel
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

    // Save data to Excel
    saveVCardDataToExcel($data);

    return $filename; 
}

// Function to save vCard data to an Excel file
function saveVCardDataToExcel($data) {
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Check if the file exists to append data
    $filePath = 'vcard_data.xlsx';
    if (file_exists($filePath)) {
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $lastRow = $sheet->getHighestRow();
    } else {
        // Add headers if the file does not exist
        $lastRow = 1;
        $sheet->setCellValue('A1', 'First Name');
        $sheet->setCellValue('B1', 'Last Name');
        $sheet->setCellValue('C1', 'Phone Number');
        $sheet->setCellValue('D1', 'Job Title');
        $sheet->setCellValue('E1', 'Email');
        $sheet->setCellValue('F1', 'Social Media');
    }

    $sheet->setCellValue('A' . ($lastRow + 1), $data['first_name']);
    $sheet->setCellValue('B' . ($lastRow + 1), $data['last_name']);
    $sheet->setCellValue('C' . ($lastRow + 1), $data['phone_number']);
    $sheet->setCellValue('D' . ($lastRow + 1), $data['job_title']);
    $sheet->setCellValue('E' . ($lastRow + 1), $data['email']);
    $sheet->setCellValue('F' . ($lastRow + 1), implode(", ", $data['social_media']));

    $writer = new Xlsx($spreadsheet);
    $writer->save($filePath);
}

// Function to generate link QR code
function generateLinkQRCode($data) {
    $filename = time() . '_' . uniqid() . '.png';
    QRcode::png($data, $filename, QR_ECLEVEL_L, 10);
    return $filename;
}

// Function to handle PDF upload and generate QR code link
function handlePDFUpload($file, $code) {
    $pdfUploadDir = __DIR__ . '/uploads/pdf/';
    
    // Ensure the upload directory exists
    if (!file_exists($pdfUploadDir)) {
        mkdir($pdfUploadDir, 0777, true);
    }
    
    $maxFileSize = 20 * 1024 * 1024; // 20 MB
    if ($file['size'] > $maxFileSize) {
        http_response_code(400);
        echo json_encode(["error" => "File size exceeds 20MB"]);
        exit;
    }
    
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    if (strtolower($ext) !== 'pdf') { 
        http_response_code(400);
        echo json_encode(["error" => "Invalid file type. Only PDF allowed"]);
        exit;
    }

    // Verify code
    $validCode = getVerificationCode(); // Retrieve valid code from function
    if ($code !== $validCode) {
        http_response_code(400);
        echo json_encode(["error" => "Invalid code"]);
        exit;
    }

    $filename = time() . '_' . uniqid() . '.' . $ext;
    $filePath = $pdfUploadDir . $filename;

    if (move_uploaded_file($file['tmp_name'], $filePath)) {
        // Generate QR code linking to the uploaded PDF
        $link = "http://localhost/Ge/uploads/pdf/" . $filename;
        return generateLinkQRCode($link);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Failed to upload PDF"]);
        exit;
    }
}

// Function to generate and return a new verification code
function generateNewVerificationCode() {
    $newCode = substr(md5(uniqid(rand(), true)), 0, 8); // Generate a random 8-character code
    // Store $newCode securely or implement logic to manage it
    file_put_contents('codes.json', json_encode(['code' => $newCode])); // Store new code
    return $newCode;
}

// Function to get the current verification code
function getVerificationCode() {
    $codes = json_decode(file_get_contents('codes.json'), true); // Retrieve codes from file
    return $codes['code'] ?? ''; // Return current code or empty string if not found
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

// Check for file upload
if (isset($_FILES['pdf'])) {
    if (!isset($_POST['code'])) {   
        http_response_code(400);
        echo json_encode(["error" => "Code is required"]);
        exit;
    }
    $file = $_FILES['pdf'];
    $code = $_POST['code'];
    $filename = handlePDFUpload($file, $code);
    echo json_encode(["filename" => $filename]);
    exit;
}

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
