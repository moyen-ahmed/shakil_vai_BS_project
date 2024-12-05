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
    echo "<script>alert('You must be logged in to access this page.'); window.location.href = 'login.php';</script>";
    exit();
}

// Check if Bus_Id is provided in the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>alert('Invalid bus ID.'); window.location.href = 'view_bus.php';</script>";
    exit();
}

$bus_id = $_GET['id'];

// Fetch the existing bus details
$sql = "SELECT * FROM Bus WHERE Bus_Id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $bus_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows != 1) {
    echo "<script>alert('Bus not found.'); window.location.href = 'view_bus.php';</script>";
    exit();
}

$bus = $result->fetch_assoc();

// Handle form submission to update the bus
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $bus_number = $_POST['bus_number'];
    $capacity = $_POST['capacity'];

    // Update query
    $update_sql = "UPDATE Bus SET Bus_number = ?, capacity = ? WHERE Bus_Id = ?";
    $update_stmt = $con->prepare($update_sql);
    $update_stmt->bind_param("sii", $bus_number, $capacity, $bus_id);

    if ($update_stmt->execute()) {
        echo "<script>alert('Bus updated successfully.'); window.location.href = 'viewBus.php';</script>";
    } else {
        echo "<script>alert('Failed to update the bus. Please try again.');</script>";
    }

    $update_stmt->close();
}

$stmt->close();
$con->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Bus</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            margin: 0;
            padding: 20px;
        }

        .edit-form {
            background: #fff;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .edit-form h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        .edit-form label {
            display: block;
            margin-bottom: 8px;
            color: #555;
        }

        .edit-form input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        .edit-form button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }

        .edit-form button:hover {
            background-color: #45a049;
        }

        .edit-form .cancel-btn {
            background-color: #e74c3c;
        }

        .edit-form .cancel-btn:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>
    <form method="POST" class="edit-form">
        <h2>Edit Bus</h2>
        <label for="bus_number">Bus Number:</label>
        <input type="text" id="bus_number" name="bus_number" value="<?php echo htmlspecialchars($bus['Bus_number']); ?>" required>
        
        <label for="capacity">Capacity:</label>
        <input type="number" id="capacity" name="capacity" value="<?php echo htmlspecialchars($bus['capacity']); ?>" required>
        
        <button type="submit">Update Bus</button>
        <a href="view_bus.php" class="cancel-btn" style="text-align:center; display: block; margin-top: 10px;">Cancel</a>
    </form>
</body>
</html>
