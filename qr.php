<!DOCTYPE html>
<html>
<head>
    <title>Avnology QR Tool</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        /* Reset styles */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
        }

        .logo-2 {
            width: 250px;
        }

        /* Header styles */
        header {
            background-color: #333;
            color: #fff;
            padding: 20px 0;
            text-align: center;
        }

        header .logo {
            width: 150px;
            height: auto;
            margin-bottom: 10px;
        }

        header h1 {
            margin: 0;
            font-size: 24px;
        }

        /* Navigation bar styles */
        nav {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 10px 0;
        }

        nav a {
            color: #fff;
            text-decoration: none;
            padding: 10px 20px;
            display: inline-block;
        }

        /* Main content container */
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        /* Form styles */
        form {
            flex-basis: calc(50% - 10px);
            margin-right: 20px;
        }

        form label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        form input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        form input[type="submit"] {
            background-color: #333;
            color: #fff;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
            width: 100%;
        }

        form input[type="submit"]:hover {
            background-color: #555;
        }

        /* QR code output styles */
        #qr-code {
            flex-basis: calc(50% - 10px);
            text-align: center;
        }

        #qr-code img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-height: 200px; /* Set maximum height */
        }

        footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 30px 0;
        }

        footer a {
            color: #fff;
            text-decoration: none;
            font-size: 20px;
        }

        /* Media query for responsiveness */
        @media (max-width: 768px) {
            header .logo {
                width: 100px;
            }

            header h1 {
                font-size: 20px;
            }

            .container {
                padding: 10px;
            }

            form,
            #qr-code {
                flex-basis: 100%;
                margin-right: 0;
                margin-bottom: 20px;
            }
        }
    </style>
</head>
<body>

<header>
    <img class="logo-2" src="avnology-logo.png" alt="Avnology Logo">
    <h1>Avnology QR Tool</h1>
</header>

<nav>
    <a href="https://avnology.com">Home</a>
    <a href="index3.php">VCard</a>
    <a href="qr.php">Link Qr</a>
</nav>

<div class="container">
    <form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <label for="link">Enter the link:</label><br>
        <input type="text" id="link" name="data" required><br><br>
        <input type="submit" value="Generate QR Code">
    </form>

    <div id="qr-code">
        <?php
        // Include the QR Code library
        require_once "phpqrcode-master/qrlib.php";

        // Function to generate QR code
        function generateQRCode($data, $filename = false, $size = 200, $margin = 2) {
            // Generate QR code
            QRcode::png($data, $filename, QR_ECLEVEL_L, $size, $margin);
        }

        // Check if data is provided
        if (isset($_GET['data'])) {
            // Get the data from the URL parameter
            $data = $_GET['data'];

            // Generate QR code with the provided data
            $tempDir = 'temp/';
            $fileName = $tempDir . 'qr_' . md5($data) . '.png'; // Generate a unique filename
            generateQRCode($data, $fileName);

            // Output the QR code image
            echo '<img src="'.$fileName.'" alt="QR-Code">';
        } else {
            // If no data provided, display a message
            echo "Please provide a link to generate QR code.";
        }
        ?>
    </div>
</div>

<footer>
    <a href="https://avnology.com" target="_blank">Avnology</a>
</footer>

<script>
    setTimeout(function() {
        window.location.href = "https://avnology.com";
    }, 900000); // 900000 milliseconds = 15 minutes
</script>

</body>
</html>
