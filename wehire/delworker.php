<?php
// delete_worker.php

// Database connection
$host = 'localhost'; // Database host
$db = 'wehire'; // Database name
$user = 'root'; // Database username
$pass = 'ashuannu'; // Database password

$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if POST data is received
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get POST data
    $contractorId = $_POST['co_id'];
    $workerId = $_POST['wo_id'];

    // Check if IDs are provided
    if (!empty($contractorId) && !empty($workerId)) {
        // Prepare the stored procedure call
        $stmt = $conn->prepare("CALL DeleteWorker(?, ?)");
        $stmt->bind_param("ii", $workerId, $contractorId);

        if ($stmt->execute()) {
            echo "Worker deleted successfully!";
        } else {
            echo "Error deleting worker: " . $conn->error;
        }

        // Close connections
        $stmt->close();
    } else {
        echo "Error: Missing contractor ID or worker ID.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Worker</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
            text-align: center;
        }

        header {
            background-color: #333;
            color: white;
            padding: 20px 0;
        }

        h1 {
            margin: 0;
        }

        form {
            background: white;
            padding: 30px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: inline-block;
            margin-top: 20px;
            text-align: left;
        }

        input {
            display: block;
            margin: 10px 0;
            padding: 10px;
            width: 100%;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            padding: 10px 10px;
            background-color: black;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: grey;
        }

        .error {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <header>
        <h1>Delete Worker</h1>
    </header>
    <main>
        <form id="deleteWorkerForm" action="" method="POST">
            <label for="contractorId"><b>Enter Contractor ID</b></label>
            <input type="number" id="contractorId" placeholder="Contractor ID" name="co_id" required>
            <br>
            <label for="workerId"><b>Enter Worker ID</b></label>
            <input type="number" id="workerId" placeholder="Worker ID" name="wo_id" required>
            <br>
            <button type="submit">Delete Worker</button>
            <div class="error" id="error"></div>
        </form>
    </main>

    <script>
        document.getElementById('deleteWorkerForm').addEventListener('submit', function(event) {
            event.preventDefault();

            const contractorId = document.getElementById('contractorId').value;
            const workerId = document.getElementById('workerId').value;
            const errorDiv = document.getElementById('error');

            // Clear previous error messages
            errorDiv.textContent = "";

            // Example validation (you can add more specific checks)
            if (!contractorId || !workerId) {
                errorDiv.textContent = "Please fill in both fields.";
                return;
            }

            // Confirmation before deletion
            const confirmDeletion = confirm(`Are you sure you want to delete Worker ID: ${workerId} for Contractor ID: ${contractorId}?`);
            if (confirmDeletion) {
                // Submit the form if confirmed
                document.getElementById('deleteWorkerForm').submit();
            }
        });
    </script>
</body>
</html>
