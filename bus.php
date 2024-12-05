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

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $Bus_number = $_POST['Bus_number'];
    $capacity = $_POST['capacity'];
   

    // Insert query
    $sql = "INSERT INTO Bus ( Bus_number, capacity)
            VALUES (?, ?)";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ii",  $Bus_number, $capacity);

    if ($stmt->execute()) {
        echo "<script>alert('Bus record inserted successfully!');</script>";
    } else {
        echo "<script>alert('Error: Could not insert record');</script>";
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
    <title>Insert Bus Record</title>
    <style>
        /* Base Styles */
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
            max-width: 90%; /* Make the form container responsive */
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

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .form-container {
                width: 100%;
                padding: 15px;
            }

            button {
                padding: 12px;
                font-size: 16px;
            }
        }

        @media (max-width: 480px) {
            h2 {
                font-size: 1.5em;
            }

            label {
                font-size: 0.9em;
            }

            input[type="text"],
            input[type="number"] {
                font-size: 1em;
                padding: 8px;
            }

            button {
                padding: 10px;
                font-size: 1em;
            }
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Insert Bus Record</h2>
        <form method="POST" action="">
           

            <label for="bus_number">Bus Number:</label>
            <input type="text" id="bus_number" name="Bus_number" required>

            <label for="capacity">Capacity:</label>
            <input type="number" id="capacity" name="capacity" required>

            <button type="submit">Insert Bus</button>
        </form>
    </div>
</body>
</html>
