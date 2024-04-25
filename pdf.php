<?php
// Include the QR code generator library
include "phpqrcode-master/qrlib.php";

// Function to generate QR code from PDF file
function generateQRCodeFromPDF($pdfFile) {
    // Generate a unique filename for the QR code image
    $filename = uniqid() . '.png';

    // Generate QR code from PDF file
    QRcode::png(file_get_contents($pdfFile), $filename);

    // Return the filename of the generated QR code
    return $filename;
}

// Check if a PDF file is uploaded
if ($_FILES["pdf_file"]["error"] == UPLOAD_ERR_OK) {
    // Check if the uploaded file is a PDF
    $fileType = strtolower(pathinfo($_FILES["pdf_file"]["name"], PATHINFO_EXTENSION));
    if ($fileType == "pdf") {
        // Move the uploaded file to a temporary location
        $tmpFilePath = $_FILES["pdf_file"]["tmp_name"];

        // Generate QR code from the PDF file
        $qrCodeFilename = generateQRCodeFromPDF($tmpFilePath);

        // Display success message and QR code image
        echo "QR Code generated successfully.<br>";
        echo '<img src="' . $qrCodeFilename . '" alt="QR Code">';
    } else {
        // If the uploaded file is not a PDF, return an error message
        echo "Error: Only PDF files are allowed.";
    }
} else {
    // If no file is uploaded or an error occurred during upload, return an error message
    echo "Error: No file uploaded or an error occurred during upload.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload PDF for QR Code Generation</title>
</head>
<body>
    <h2>Upload PDF for QR Code Generation</h2>
    <form action="pdf.php" method="post" enctype="multipart/form-data">
        <input type="file" name="pdf_file" accept=".pdf" required>
        <button type="submit">Generate QR Code</button>
    </form>
</body>
</html>
    