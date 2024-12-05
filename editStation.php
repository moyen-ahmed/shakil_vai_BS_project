<?php
session_start();

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

// Ensure the user is logged in
if (!isset($_SESSION['contact_Info'])) {
    echo "<script>alert('You must be logged in to edit a station.'); window.location.href = 'viewStation.php';</script>";
    exit;
}

// Retrieve Station_Id from query parameter
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>alert('Invalid station ID.'); window.location.href = 'viewStation.php';</script>";
    exit;
}

$station_id = $_GET['id'];

// Fetch station details
$sql = "SELECT * FROM Bus_Station WHERE Station_Id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $station_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<script>alert('Station not found.'); window.location.href = 'viewStation.php';</script>";
    exit;
}

$station = $result->fetch_assoc();

// Handle form submission to update the station
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $station_name = $_POST['station_name'];
    $location = $_POST['location'];
    $contact_info = $_POST['contact_info'];
    $operating_hours = $_POST['operating_hours'];

    // Update query
    $update_sql = "UPDATE Bus_Station SET Station_name = ?, Location = ?, Contact_Info = ?, Operating_Hours = ? WHERE Station_Id = ?";
    $update_stmt = $con->prepare($update_sql);
    $update_stmt->bind_param("ssssi", $station_name, $location, $contact_info, $operating_hours, $station_id);

    if ($update_stmt->execute()) {
        echo "<script>alert('Station updated successfully.'); window.location.href = 'viewStation.php';</script>";
    } else {
        echo "<script>alert('Failed to update the station. Please try again.');</script>";
    }

    $update_stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Station</title>
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
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 400px;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            font-weight: bold;
            margin-top: 10px;
        }

        input[type="text"], textarea {
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 100%;
        }

        button {
            margin-top: 20px;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #45a049;
        }

        .cancel-btn {
            background-color: #e74c3c;
            text-align: center;
            margin-top: 10px;
        }

        .cancel-btn:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Edit Station</h2>
        <form method="POST">
            <label for="station_name">Station Name:</label>
            <input type="text" name="station_name" id="station_name" value="<?= htmlspecialchars($station['Station_name']) ?>" required>

            <label for="location">Location:</label>
            <input type="text" name="location" id="location" value="<?= htmlspecialchars($station['Location']) ?>" required>

            <label for="contact_info">Contact Info:</label>
            <input type="text" name="contact_info" id="contact_info" value="<?= htmlspecialchars($station['Contact_Info']) ?>" required>

            <label for="operating_hours">Operating Hours:</label>
            <textarea name="operating_hours" id="operating_hours" rows="3" required><?= htmlspecialchars($station['Operating_Hours']) ?></textarea>

            <button type="submit">Update Station</button>
            <a href="viewStation.php" class="cancel-btn">Cancel</a>
        </form>
    </div>
</body>
</html>

<?php
$stmt->close();
$con->close();
?>
