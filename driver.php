<?php
// Database connection
$server = 'localhost';
$username = 'root';
$password = '';
$database = 'bus_system';

$con = new mysqli($server, $username, $password, $database);

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Initialize message variable
$message = '';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $Name = $_POST['Name'];
    $License_Number = $_POST['License_Number'];
    $Experience = $_POST['Experience'];

    // Insert query
    $sql = "INSERT INTO Driver (Name, License_Number, Experience) VALUES (?, ?, ?)";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ssi", $Name, $License_Number, $Experience);

    if ($stmt->execute()) {
        echo "<script>alert('driver add successfully.'); window.location.href = 'viewDriver.php';</script>";

    } else {
        $message = "Error: Could not insert record - " . $stmt->error;
    }

    $stmt->close();
}

$con->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert Driver Record</title>
    <style>
        /* Basic styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .form-container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            width: 350px;
            max-width: 90%;
        }

        h2 {
            text-align: center;
            color: #333333;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-top: 10px;
            color: #333333;
        }

        input[type="text"],
        input[type="number"] {
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #cccccc;
            border-radius: 4px;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 15px;
        }

        button:hover {
            background-color: #45a049;
        }

        /* Responsive styles */
        @media (max-width: 768px) {
            .form-container {
                width: 100%;
                padding: 15px;
            }
        }

        /* Message style */
        .message {
            text-align: center;
            color: #4CAF50;
            margin-top: 10px;
        }

        .error {
            color: #ff0000;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Insert Driver Record</h2>
        <form method="POST" action="">
            <label for="name">Name:</label>
            <input type="text" id="name" name="Name" required>

            <label for="license_number">License Number:</label>
            <input type="text" id="license_number" name="License_Number" required>

            <label for="experience">Experience (years):</label>
            <input type="number" id="experience" name="Experience" required>

            <button type="submit">Insert Driver</button>
        </form>

        <?php if (!empty($message)): ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
