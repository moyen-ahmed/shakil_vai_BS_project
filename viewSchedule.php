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

// Query to fetch schedule records
$sql = "SELECT Schedule.Schedule_Id, Bus.Bus_number, Route.Route_Name, Schedule.Departure_Time, Schedule.Arrival_Time, Driver.Name as Driver_Name
    FROM Schedule
    INNER JOIN Bus ON Schedule.Bus_Id = Bus.Bus_Id
    INNER JOIN Route ON Schedule.Route_Id = Route.Route_Id
    INNER JOIN Driver ON Schedule.Driver_Id = Driver.Driver_Id";

$result = $con->query($sql);

// Check if the user is logged in
$is_logged_in = isset($_SESSION['contact_Info']);

// Handle delete request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_schedule_id'])) {
    $schedule_id = intval($_POST['delete_schedule_id']);
    $delete_sql = "DELETE FROM Schedule WHERE Schedule_Id = ?";
    $stmt = $con->prepare($delete_sql);
    $stmt->bind_param("i", $schedule_id);
    if ($stmt->execute()) {
        echo "<script>alert('Schedule deleted successfully.'); window.location.viewSchedule.php;</script>";
    } else {
        echo "<script>alert('Failed to delete schedule.');</script>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Schedule Records</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('1.jpg') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            margin: 0;
        }

        table {
            width: 80%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #ccc;
        }

        th, td {
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        td {
            background-color: #f9f9f9;
        }

        h2 {
            color: #FFFFFFFF;
        }

        .add-route-btn, .edit-btn, .delete-btn {
            padding: 8px 16px;
            margin: 5px;
            text-decoration: none;
            border-radius: 4px;
            font-size: 14px;
        }

        .add-route-btn {
            background-color: #4CAF50;
            color: white;
        }

        .add-route-btn:hover {
            background-color: #45a049;
        }

        .edit-btn {
            background-color: #FFA500;
            color: white;
        }

        .edit-btn:hover {
            background-color: #E69500;
        }

        .delete-btn {
            background-color: #FF6347;
            color: white;
        }

        .delete-btn:hover {
            background-color: #D45041;
        }

        @media (max-width: 768px) {
            table {
                width: 100%;
                margin: 10px;
            }

            .add-route-btn {
                width: 100%;
                text-align: center;
            }
        }
    </style>
</head>
<body>

    <h2>List of Bus Schedules</h2>

    <!-- Show "Add Schedule" button only if logged in -->
    <?php if ($is_logged_in): ?>
        <a href="schedule.php" class="add-route-btn">Add Schedule</a>
    <?php endif; ?>

    <?php if ($result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Bus Number</th>
                    <th>Route Name</th>
                    <th>Departure Time</th>
                    <th>Arrival Time</th>
                    <th>Driver Name</th>
                    <?php if ($is_logged_in): ?>
                        <th>Actions</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['Bus_number']); ?></td>
                        <td><?php echo htmlspecialchars($row['Route_Name']); ?></td>
                        <td><?php echo htmlspecialchars($row['Departure_Time']); ?></td>
                        <td><?php echo htmlspecialchars($row['Arrival_Time']); ?></td>
                        <td><?php echo htmlspecialchars($row['Driver_Name']); ?></td>
                        <?php if ($is_logged_in): ?>
                            <td>
                                <a href="edit_schedule.php?id=<?php echo $row['Schedule_Id']; ?>" class="edit-btn">Edit</a>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="delete_schedule_id" value="<?php echo $row['Schedule_Id']; ?>">
                                    <button type="submit" class="delete-btn" onclick="return confirm('Are you sure you want to delete this schedule?')">Delete</button>
                                </form>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No schedules found in the database.</p>
    <?php endif; ?>

    <?php
    // Close connection
    $con->close();
    ?>
<a href="home.php" class="add-route-btn">Back</a>
</body>
</html>
