<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

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

// Fetch options for Bus_Id, Route_Id, and Driver_Id
$busOptions = $routeOptions = $driverOptions = [];

// Query to get Bus options
$busQuery = "SELECT Bus_Id, Bus_number FROM Bus";
$busResult = $con->query($busQuery);
if ($busResult) {
    while ($row = $busResult->fetch_assoc()) {
        $busOptions[] = $row;
    }
} else {
    echo "Error fetching buses: " . $con->error;
}

// Query to get Route options
$routeQuery = "SELECT Route_Id, Route_Name FROM Route";
$routeResult = $con->query($routeQuery);
if ($routeResult) {
    while ($row = $routeResult->fetch_assoc()) {
        $routeOptions[] = $row;
    }
} else {
    echo "Error fetching routes: " . $con->error;
}

// Query to get Driver options
$driverQuery = "SELECT Driver_Id, Name FROM Driver";
$driverResult = $con->query($driverQuery);
if ($driverResult) {
    while ($row = $driverResult->fetch_assoc()) {
        $driverOptions[] = $row;
    }
} else {
    echo "Error fetching drivers: " . $con->error;
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check for empty fields
    if (empty($_POST['Bus_Id']) || empty($_POST['Route_Id']) || empty($_POST['Departure_Time']) || empty($_POST['Arrival_Time']) || empty($_POST['Driver_Id'])) {
        $message = "Please fill in all required fields.";
    } else {
        $Bus_Id = $_POST['Bus_Id'];
        $Route_Id = $_POST['Route_Id'];
        $Departure_Time = $_POST['Departure_Time'];
        $Arrival_Time = $_POST['Arrival_Time'];
        $Driver_Id = $_POST['Driver_Id'];

        // Insert query
        $sql = "INSERT INTO Schedule (Bus_Id, Route_Id, Departure_Time, Arrival_Time, Driver_Id) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $con->prepare($sql);
        if ($stmt === false) {
            die("Error preparing statement: " . $con->error);
        }
        $stmt->bind_param("iissi", $Bus_Id, $Route_Id, $Departure_Time, $Arrival_Time, $Driver_Id);

        if ($stmt->execute()) {
            $message = "Schedule record inserted successfully!";
        } else {
            $message = "Error: Could not insert record - " . $stmt->error;
        }

        $stmt->close();
    }
}

$con->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert Schedule Record</title>
    <style>
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
        input[type="time"],
        select {
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
        <h2>Insert Schedule Record</h2>
        <form method="POST" action="">
            <label for="bus_id">Bus:</label>
            <select id="bus_id" name="Bus_Id" required>
                <option value="" disabled selected>Select Bus</option>
                <?php foreach ($busOptions as $bus): ?>
                    <option value="<?= $bus['Bus_Id'] ?>"><?= htmlspecialchars($bus['Bus_number']) ?></option>
                <?php endforeach; ?>
            </select>

            <label for="route_id">Route:</label>
            <select id="route_id" name="Route_Id" required>
                <option value="" disabled selected>Select Route</option>
                <?php foreach ($routeOptions as $route): ?>
                    <option value="<?= $route['Route_Id'] ?>"><?= htmlspecialchars($route['Route_Name']) ?></option>
                <?php endforeach; ?>
            </select>

            <label for="departure_time">Departure Time:</label>
            <input type="time" id="departure_time" name="Departure_Time" required>

            <label for="arrival_time">Arrival Time:</label>
            <input type="time" id="arrival_time" name="Arrival_Time" required>

            <label for="driver_id">Driver:</label>
            <select id="driver_id" name="Driver_Id" required>
                <option value="" disabled selected>Select Driver</option>
                <?php foreach ($driverOptions as $driver): ?>
                    <option value="<?= $driver['Driver_Id'] ?>"><?= htmlspecialchars($driver['Name']) ?></option>
                <?php endforeach; ?>
            </select>

            <button type="submit">Insert Schedule</button>
        </form>

        <?php if (!empty($message)): ?>
            <p class="message"><?= $message ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
