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

    // Output QR code to browser with the unique filename
    QRcode::png($vcard, $filename, QR_ECLEVEL_Q, 10);

    // Return the filename
    return $filename;
}

// Check if form is submitted
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

    // Generate QR code and get the filename
    $qrCodeFilename = generateQRCode($data);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


  <meta name="description" content="AVNology is a digital agency that specializes in software development, interactive design, and digital marketing. We help businesses achieve their goals with engaging digital experiences, visual elements, and social media presence.">
  <meta name="keywords" content="digital agency, software development, interactive design, digital marketing, visual elements, social media presence , Qr Tool , qrcode , qr,code , tool , generate , making">
  <meta name="author" content="AVNology">
  <meta name="robots" content="index, follow">
  <meta name="googlebot" content="index, follow">
  <meta name="msnbot" content="index, follow">
  <link rel="canonical" href="https://avnology.com/">
  <link rel="shortcut icon" type="image/x-icon" href="your-favicon-url">


</head>



    <title>Avnology QR Tool</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #333;
            color: #fff;
            padding: 10px 0;
            text-align: center;
        }

        header img {
            width: 300px; /* Adjust size as needed */
            height: auto;
            margin-bottom: 30px;
        }

        header h1 {
            margin: 0;
            font-size: 24px;
        }

        .container {
            max-width: 800px; /* Adjust width as needed */
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background-color: #f9f9f9;
            display: flex; /* Use flexbox for layout */
            flex-direction: row; /* Horizontal layout */
            justify-content: space-between; /* Align elements */
        }

        form {
            flex: 1; /* Occupy remaining space */
            margin-right: 20px; /* Add spacing */
        }

        form label {
            font-weight: bold;
        }

        form input[type="text"],
        form input[type="email"],
        form textarea {
            width: 100%; /* Take full width */
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }

        form textarea {
            height: 100px;
        }

        form input[type="submit"] {
            background-color: #333;
            color: #fff;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
        }

        form input[type="submit"]:hover {
            background-color: #555;
        }

        #qr-code {
            text-align: center;
            margin-top: 20px;
            flex: 1; /* Occupy remaining space */
            display: flex; /* Use flexbox for layout */
            justify-content: center; /* Center horizontally */
            align-items: center; /* Center vertically */
        }

        #qr-code img {
            max-width: 100%; /* Adjust size as needed */
            max-height: 300px; /* Max height */
            border: 2px solid #333;
            border-radius: 10px;
        }

        footer {
            bottom: 0;
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 30px 0;
            width: 100%;
        }

        footer a {
            color: #fff;
            text-decoration: none;
            font-size: 30px;
        }

            </style>
</head>
<body>
    <header>
        <img class="logo" src="avnology-logo.png" alt="Avnology Logo">
        <h1>Avnology QR Tool</h1>
    </header>

    <div class="container">
        <form id="qr-form" method="post">
         <h2>Generate Contact Card QR Code</h2>
            <label for="first_name">First Name:</label><br>
            <input type="text" id="first_name" name="first_name" required><br>

            <label for="last_name">Last Name:</label><br>
            <input type="text" id="last_name" name="last_name" required><br>

            <label for="phone_number">Phone Number:</label><br>
            <input type="text" id="phone_number" name="phone_number" required><br>

            <label for="job_title">Job Title:</label><br>
            <input type="text" id="job_title" name="job_title" required><br>

            <label for="email">E-mail:</label><br>
            <input type="email" id="email" name="email" required><br>

            <label for="social_media">Social Media Links (One link per line):</label><br>
            <textarea id="social_media" name="social_media" rows="4" required></textarea><br>

            <input type="submit" value="Generate Contact Card QR Code">
        
        
        </form>

        <div id="qr-code">
            <?php if ($_SERVER["REQUEST_METHOD"] == "POST") : ?>
                <img src="<?php echo $qrCodeFilename; ?>" alt="QR Code">
            <?php endif; ?>
        </div>
    </div>

    <footer>
        <a href="https://avnology.com" target="_blank">Avnology</a>
    </footer>

    <!-- JavaScript to redirect after 15 minutes -->
    <script>
        setTimeout(function() {
            window.location.href = "https://avnology.com";
        }, 900000); // 900000 milliseconds = 15 minutes
    </script>
</body>
</html>
