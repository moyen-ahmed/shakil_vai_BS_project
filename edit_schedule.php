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
    echo "<script>alert('You must be logged in to edit schedules.'); window.location.href = 'login.php';</script>";
    exit;
}

// Get the schedule ID from the URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<script>alert('Invalid schedule ID.'); window.location.href = 'view_schedule.php';</script>";
    exit;
}

$schedule_id = intval($_GET['id']);

// Fetch the schedule details
$sql = "SELECT Schedule.*, Bus.Bus_number, Route.Route_Name, Driver.Name AS Driver_Name 
        FROM Schedule
        INNER JOIN Bus ON Schedule.Bus_Id = Bus.Bus_Id
        INNER JOIN Route ON Schedule.Route_Id = Route.Route_Id
        INNER JOIN Driver ON Schedule.Driver_Id = Driver.Driver_Id
        WHERE Schedule.Schedule_Id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $schedule_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    echo "<script>alert('Schedule not found.'); window.location.href = 'view_schedule.php';</script>";
    exit;
}

$schedule = $result->fetch_assoc();

// Fetch buses, routes, and drivers for the dropdown menus
$buses = $con->query("SELECT Bus_Id, Bus_number FROM Bus");
$routes = $con->query("SELECT Route_Id, Route_Name FROM Route");
$drivers = $con->query("SELECT Driver_Id, Name FROM Driver");

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bus_id = intval($_POST['bus_id']);
    $route_id = intval($_POST['route_id']);
    $driver_id = intval($_POST['driver_id']);
    $departure_time = $_POST['departure_time'];
    $arrival_time = $_POST['arrival_time'];

    $update_sql = "UPDATE Schedule SET Bus_Id = ?, Route_Id = ?, Driver_Id = ?, Departure_Time = ?, Arrival_Time = ? WHERE Schedule_Id = ?";
    $update_stmt = $con->prepare($update_sql);
    $update_stmt->bind_param("iiissi", $bus_id, $route_id, $driver_id, $departure_time, $arrival_time, $schedule_id);

    if ($update_stmt->execute()) {
        echo "<script>alert('Schedule updated successfully.'); window.location.href = 'view_schedule.php';</script>";
    } else {
        echo "<script>alert('Failed to update schedule. Please try again.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Schedule</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            margin: 0;
        }

        .container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            width: 100%;
        }

        h2 {
            color: #333333;
            margin-bottom: 20px;
        }

        form label {
            font-weight: bold;
            margin-top: 10px;
            display: block;
        }

        form select, form input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }

        .btn-primary {
            background-color: #4CAF50;
        }

        .btn-primary:hover {
            background-color: #45a049;
        }

        .btn-secondary {
            background-color: #555555;
        }

        .btn-secondary:hover {
            background-color: #444444;
        }

        .actions {
            display: flex;
            justify-content: space-between;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Edit Schedule</h2>
    <form method="POST">
        <label for="bus_id">Bus</label>
        <select name="bus_id" id="bus_id" required>
            <?php while ($bus = $buses->fetch_assoc()): ?>
                <option value="<?php echo $bus['Bus_Id']; ?>" <?php echo ($bus['Bus_Id'] == $schedule['Bus_Id']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($bus['Bus_number']); ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label for="route_id">Route</label>
        <select name="route_id" id="route_id" required>
            <?php while ($route = $routes->fetch_assoc()): ?>
                <option value="<?php echo $route['Route_Id']; ?>" <?php echo ($route['Route_Id'] == $schedule['Route_Id']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($route['Route_Name']); ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label for="driver_id">Driver</label>
        <select name="driver_id" id="driver_id" required>
            <?php while ($driver = $drivers->fetch_assoc()): ?>
                <option value="<?php echo $driver['Driver_Id']; ?>" <?php echo ($driver['Driver_Id'] == $schedule['Driver_Id']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($driver['Name']); ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label for="departure_time">Departure Time</label>
        <input type="datetime-local" name="departure_time" id="departure_time" value="<?php echo htmlspecialchars($schedule['Departure_Time']); ?>" required>

        <label for="arrival_time">Arrival Time</label>
        <input type="datetime-local" name="arrival_time" id="arrival_time" value="<?php echo htmlspecialchars($schedule['Arrival_Time']); ?>" required>

        <div class="actions">
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="viewSchedule.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

</body>
</html>
