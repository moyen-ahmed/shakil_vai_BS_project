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
    $Route_name = $_POST['Route_name'];
   
    $Total_Distance = $_POST['Total_Distance'];
    

    // Insert query
    $sql = "INSERT INTO Route (Route_Name, Total_Distance)
            VALUES (?, ?)";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("sd", $Route_name, $Total_Distance);

    if ($stmt->execute()) {
        echo "<script>window.location.href = 'viewRoute.php';</script>";
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
    <title>Insert Route Record</title>
    <style>
        /* Base styles */
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
            max-width: 90%; /* Makes it responsive */
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
        input[type="number"],
        input[type="time"] {
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
            input[type="number"],
            input[type="time"] {
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
        <h2>Insert Route Record</h2>
        <form method="POST" action="">
            <label for="route_name">Route Name:</label>
            <input type="text" id="route_name" name="Route_name" required>
           
            <label for="total_distance">Total Distance (km):</label>
            <input type="number" id="total_distance" name="Total_Distance" step="0.01" required>

            <button type="submit">Insert Route</button>
        </form>
    </div>
</body>
</html>
