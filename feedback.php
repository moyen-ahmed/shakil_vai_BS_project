<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bus_system";

// Enable MySQL error reporting
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Initialize message variable
$message = "";

$busOptions = [];
$driverOptions = [];

// Fetch Bus_Id and Driver_Id for dropdowns
try {
    // Create a connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Fetch bus options
    $result = $conn->query("SELECT Bus_Id FROM bus");
    while ($row = $result->fetch_assoc()) {
        $busOptions[] = $row;
    }

    // Fetch driver options
    $result = $conn->query("SELECT Driver_Id FROM driver");
    while ($row = $result->fetch_assoc()) {
        $driverOptions[] = $row;
    }

    $conn->close();
} catch (Exception $e) {
    $message = "Error fetching dropdown data: " . $e->getMessage();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Create a connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Retrieve and sanitize input
        $Bus_Id = $_POST['Bus_Id'];
        $Driver_Id = $_POST['Driver_Id'];
        $Feedback_Text = $_POST['Feedback_Text'];
        $Rating = isset($_POST['Rating']) ? $_POST['Rating'] : NULL;
        $Date = $_POST['Date'];

        // Validate foreign key references
        $busCheck = $conn->prepare("SELECT COUNT(*) FROM bus WHERE Bus_Id = ?");
        $busCheck->bind_param("i", $Bus_Id);
        $busCheck->execute();
        $busCheck->bind_result($busExists);
        $busCheck->fetch();
        $busCheck->close();

        $driverCheck = $conn->prepare("SELECT COUNT(*) FROM driver WHERE Driver_Id = ?");
        $driverCheck->bind_param("i", $Driver_Id);
        $driverCheck->execute();
        $driverCheck->bind_result($driverExists);
        $driverCheck->fetch();
        $driverCheck->close();

        if (!$busExists || !$driverExists) {
            throw new Exception("Invalid Bus_Id or Driver_Id. Please ensure they exist in the respective tables.");
        }

        // Prepare and bind
        $stmt = $conn->prepare("INSERT INTO Feedback (Bus_Id, Driver_Id, Feedback_Text, Rating, Date) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iisis", $Bus_Id, $Driver_Id, $Feedback_Text, $Rating, $Date);

        // Execute the query
        $stmt->execute();
        $message = "Feedback submitted successfully.";

        // Close the statement and connection
        $stmt->close();
        $conn->close();
    } catch (Exception $e) {
        $message = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Feedback Form</title>
   <style>
       body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; }
       .container { max-width: 600px; margin: 50px auto; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); }
       h1 { text-align: center; color: #333; margin-bottom: 20px; }
       label { display: block; margin-bottom: 8px; font-weight: bold; color: #555; }
       select, input[type="text"], input[type="number"], input[type="date"], textarea { width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 4px; font-size: 14px; }
       textarea { resize: vertical; height: 100px; }
       button { width: 100%; padding: 10px; background-color: #4CAF50; border: none; border-radius: 4px; color: #fff; font-size: 16px; cursor: pointer; }
       button:hover { background-color: #45a049; }
       .message { text-align: center; font-size: 16px; color: green; margin-bottom: 15px; }
       .error { text-align: center; font-size: 16px; color: red; margin-bottom: 15px; }
   </style>
</head>
<body>
   <div class="container">
       <h1>Submit Feedback</h1>
       <?php if ($message): ?>
           <div class="<?php echo strpos($message, 'Error') === false ? 'message' : 'error'; ?>">
               <?php echo $message; ?>
           </div>
       <?php endif; ?>
       <form action="" method="POST">
           <label for="Bus_Id">Bus</label>
           <select id="Bus_Id" name="Bus_Id" required>
               <option value="">Select a Bus</option>
               <?php foreach ($busOptions as $bus): ?>
                   <option value="<?php echo $bus['Bus_Id']; ?>"><?php echo $bus['Bus_Id']; ?></option>
               <?php endforeach; ?>
           </select>

           <label for="Driver_Id">Driver</label>
           <select id="Driver_Id" name="Driver_Id" required>
               <option value="">Select a Driver</option>
               <?php foreach ($driverOptions as $driver): ?>
                   <option value="<?php echo $driver['Driver_Id']; ?>"><?php echo $driver['Driver_Id']; ?></option>
               <?php endforeach; ?>
           </select>

           <label for="Feedback_Text">Feedback Text</label>
           <textarea id="Feedback_Text" name="Feedback_Text" required></textarea>

           <label for="Rating">Rating (Optional)</label>
           <input type="number" id="Rating" name="Rating" min="1" max="5">

           <label for="Date">Date</label>
           <input type="date" id="Date" name="Date" required>

           <button type="submit">Submit Feedback</button>
           <a href="viewBus.php" class="btn">Back</a>
       </form>
   </div>
</body>
</html>
