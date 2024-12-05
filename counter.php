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
    $Counter_Name = $_POST['Counter_Name'];
    $Operating_Hours = $_POST['Operating_Hours'];
    $Tickets_Sold = $_POST['Tickets_Sold'];

    // Insert query
    $sql = "INSERT INTO Counter (Counter_Name, Operating_Hours, Tickets_Sold) VALUES (?, ?, ?)";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ssi", $Counter_Name, $Operating_Hours, $Tickets_Sold);

    if ($stmt->execute()) {
        $message = "Counter record inserted successfully!";
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
    <title>Insert Counter Record</title>
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
        <h2>Insert Counter Record</h2>
        <form method="POST" action="">
            <label for="counter_name">Counter Name:</label>
            <input type="text" id="counter_name" name="Counter_Name" required>

            <label for="operating_hours">Operating Hours:</label>
            <input type="text" id="operating_hours" name="Operating_Hours" placeholder="6:00 AM - 10:00 PM" required>

            <label for="tickets_sold">Tickets Sold:</label>
            <input type="number" id="tickets_sold" name="Tickets_Sold" required>

            <button type="submit">Insert Counter</button>
        </form>

        <?php if (isset($message)): ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
