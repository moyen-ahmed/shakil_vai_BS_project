<?php
// Database connection details
$host = "localhost";
$username = "root";
$password = "";
$database = "bus_system"; // Replace with your database name

// Create a connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to fetch fuel logs and related bus information
$sql = "SELECT * FROM fuel_log ORDER BY Fuel_Log_Id";;

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Fuel Logs</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .no-data {
            text-align: center;
            color: #555;
            font-size: 18px;
            margin: 20px 0;
        }

        .button-container {
            text-align: center;
        }

        .button-container a {
            display: inline-block;
            text-decoration: none;
            color: #fff;
            background-color: #4CAF50;
            padding: 10px 20px;
            border-radius: 4px;
            font-size: 14px;
            margin: 5px;
        }

        .button-container a:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Fuel Logs</h1>
        <?php if ($result && $result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Fuel Log ID</th>
                        <th>Bus ID</th>
                        
                        <th>Date</th>
                        <th>Fuel Amount (Liters)</th>
                        <th>Cost</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['Fuel_Log_Id']) ?></td>
                            <td><?= htmlspecialchars($row['Bus_Id']) ?></td>
                            
                            <td><?= htmlspecialchars($row['Date']) ?></td>
                            <td><?= htmlspecialchars($row['Fuel_Amount']) ?></td>
                            <td><?= htmlspecialchars($row['Cost']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="no-data">No fuel logs found.</p>
        <?php endif; ?>
        <div class="button-container">
            <a href="fuel.php">Add Fuel Log</a>
            <a href="home.php">Home</a>
        </div>
    </div>
</body>
</html>

<?php
// Close the connection
$conn->close();
?>
