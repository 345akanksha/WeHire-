<?php
// database connection settings
$servername = "localhost";
$username = "root";
$password = "ashuannu";
$dbname = "wehire";

// Create connection to MySQL
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $workerId = $_POST['workerId'];
    $contractorId = $_POST['contractorId'];
    $salary = $_POST['salary'];
    $verificationStatus = $_POST['verificationStatus'];
    $service = $_POST['service']; // NEW FIELD

    // Debugging: Output the worker ID, contractor ID, and service
    echo "<p>Worker ID: $workerId, Contractor ID: $contractorId, Service: $service</p>";

    // If salary is provided, update the salary
    if (!empty($salary)) {
        $stmt = $conn->prepare("CALL UpdateWorkerSalary(?, ?, ?,?)");
        // Bind the parameters (workerID, contractorID, salary, service)
        if ($stmt) {
            $stmt->bind_param("isss", $workerId, $contractorId, $salary, $service);

            // Execute the stored procedure
            if ($stmt->execute()) {
                echo "<p>Salary for Worker ID $workerId and Contractor ID $contractorId updated successfully!</p>";
            } else {
                echo "<p>Error updating salary: " . $stmt->error . "</p>";
            }
        } else {
            echo "<p>Error preparing salary statement: " . $conn->error . "</p>";
        }

        // Close the statement
        $stmt->close();
    }

    // If verification status is provided, update the verification status
    if (!empty($verificationStatus)) {
        $stmt = $conn->prepare("CALL UpdateWorkerVerification(?, ?, ?, ?)");
        // Bind the parameters (workerID, contractorID, newVerificationStatus, service)
        if ($stmt) {
            $stmt->bind_param("isss", $workerId, $contractorId, $verificationStatus, $service);

            // Execute the stored procedure
            if ($stmt->execute()) {
                echo "<p>Verification status for Worker ID $workerId, Contractor ID $contractorId, and Service $service updated successfully!</p>";
            } else {
                echo "<p>Error updating verification status: " . $stmt->error . "</p>";
            }
        } else {
            echo "<p>Error preparing verification statement: " . $conn->error . "</p>";
        }

        // Close the statement
        $stmt->close();
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        header {
            text-align: center;
            margin-bottom: 30px;
        }

        .form-container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            margin: auto;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="number"],
        select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: black;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: grey;
        }

        .message {
            margin-top: 20px;
            text-align: center;
            color: green;
        }
    </style>
</head>
<body>
    <header>
        <h1>Update Contractor Details</h1>
    </header>
    <div class="form-container">
        <form id="updateForm" action="" method="POST"> <!-- Changed action to update_worker.php -->
            <div class="form-group">
                <label for="workerId">Worker ID</label>
                <input type="text" id="workerId" name="workerId" required>
            </div>
            <div class="form-group">
                <label for="contractorId">Contractor ID</label>
                <input type="text" id="contractorId" name="contractorId" required> <!-- Added name attribute -->
            </div>
            <div class="form-group">
                <label for="salary">Update Salary</label>
                <input type="number" id="salary" name="salary"> <!-- Added name attribute -->
            </div>
		<div class="form-group">
    <label for="service">Service Type</label>
    <select id="service" name="service" required>
        <option value="">Select Service</option>
        <option value="maid">Maid</option>
        <option value="nurse">Nurse</option>
        <option value="driver">Driver</option>
        <option value="tutor">Tutor</option>
        <option value="security">Security</option>
        <option value="maintenance">Maintenance</option>
    </select>
</div>

            <div class="form-group">
                <label for="verificationStatus">Police Verification Status</label>
                <select id="verificationStatus" name="verificationStatus"> <!-- Added name attribute -->
                    <option value="">Select Status</option>
                    <option value="YES">Yes</option>
        		<option value="NO">No</option>
                </select>
            </div>
            <button type="submit">Update Details</button>
        </form>
        <div class="message" id="message"></div>
    </div>
</body>
</html>
