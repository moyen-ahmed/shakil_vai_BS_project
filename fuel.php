<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection details
$host = "localhost";
$username = "root";
$password = "";
$database = "bus_system"; // Replace with your actual database name

// Create a connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("<p class='error'>Connection failed: " . $conn->connect_error . "</p>");
}

// Fetch available buses from the bus table
$buses = [];
$bus_query = "SELECT Bus_Id FROM bus"; // Assuming a Bus_Name column exists
$result = $conn->query($bus_query);

if ($result && $result->num_rows > 0) {
    $buses = $result->fetch_all(MYSQLI_ASSOC);
}

// Process the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $bus_id = intval($_POST['bus_id']);
    $date = $_POST['date'];
    $fuel_amount = floatval($_POST['fuel_amount']);
    $cost = floatval($_POST['cost']);

    // Prepare the SQL statement
    $stmt = $conn->prepare("INSERT INTO Fuel_Log (Bus_Id, Date, Fuel_Amount, Cost) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isdd", $bus_id, $date, $fuel_amount, $cost);

    // Execute the query and display success or error messages
    if ($stmt->execute()) {
        echo "<p class='success'>Fuel log added successfully!</p>";
    } else {
        echo "<p class='error'>Error: " . $stmt->error . "</p>";
    }

    // Close the statement
    $stmt->close();
}

// Close the connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Fuel Log Entry</title>
   <style>
       body {
           font-family: Arial, sans-serif;
           background-color: #f4f4f4;
           margin: 0;
           padding: 0;
           display: flex;
           justify-content: center;
           align-items: center;
           height: 100vh;
       }
       .form-container {
           background: #fff;
           padding: 20px 30px;
           border-radius: 10px;
           box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
           max-width: 400px;
           width: 100%;
       }
       .form-container h2 {
           text-align: center;
           margin-bottom: 20px;
       }
       .form-group {
           margin-bottom: 15px;
       }
       .form-group label {
           display: block;
           margin-bottom: 5px;
           font-weight: bold;
       }
       .form-group input {
           width: 100%;
           padding: 10px;
           border: 1px solid #ccc;
           border-radius: 5px;
           font-size: 14px;
       }
       .form-group input:focus {
           outline: none;
           border-color: #007bff;
       }
       .submit-btn {
           width: 100%;
           padding: 10px;
           background: #007bff;
           color: #fff;
           border: none;
           border-radius: 5px;
           font-size: 16px;
           cursor: pointer;
       }
       .submit-btn:hover {
           background: #0056b3;
       }
       .success, .error {
           text-align: center;
           margin-top: 10px;
       }
       .success {
           color: green;
       }
       .error {
           color: red;
       }
   </style>
</head>
<body>
   <div class="form-container">
       <h2>Fuel Log Entry</h2>
       
       <!-- Form -->
       <form method="post" action="">
           <div class="form-group">
               <label for="bus_id">Bus ID:</label>
               <input type="number" id="bus_id" name="bus_id" placeholder="Enter Bus ID" required>
           </div>
           <div class="form-group">
               <label for="date">Date:</label>
               <input type="date" id="date" name="date" required>
           </div>
           <div class="form-group">
               <label for="fuel_amount">Fuel Amount (liters):</label>
               <input type="number" step="0.01" id="fuel_amount" name="fuel_amount" placeholder="Enter Fuel Amount" required>
           </div>
           <div class="form-group">
               <label for="cost">Cost ($):</label>
               <input type="number" step="0.01" id="cost" name="cost" placeholder="Enter Cost" required>
           </div>
           <button type="submit" class="submit-btn">Submit</button>
       </form>
   </div>
</body>
</html>





