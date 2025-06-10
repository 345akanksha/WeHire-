<?php
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
    $contractorId = $_POST['contractorId'];
    $name = $_POST['name'];
    $aadhaar = $_POST['aadhaar'];
    $age = $_POST['age'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $verification = $_POST['verification'];
    $serviceType = $_POST['serviceType']; // Service type field
    $wages = $_POST['wages']; // Wages field

    // Validation: Wages must be a positive number
    if (!is_numeric($wages) || $wages <= 0) {
        echo "Invalid wages. Please enter a valid number.";
        exit();
    }

    // Determine the table to insert based on service type
    $table = '';
    switch ($serviceType) {
        case 'maid':
            $table = 'maid';
            break;
        case 'nurse':
            $table = 'nurse';
            break;
        case 'tutor':
            $table = 'tutor';
            break;
        case 'driver':
            $table = 'driver';
            break;
        case 'security':
            $table = 'security';
            break;
        case 'maintenance':
            $table = 'maintenance';
            break;
        default:
            echo "Invalid service type selected.";
            exit();
    }

    // Prepare and execute the query to insert into the selected service table
    // Prepare and execute the query to insert into the selected service table
$sql = "INSERT INTO $table (co_id, name, aadhar_no, age, phone_no, address, police_ve_stat, wages) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Error preparing statement: " . $conn->error); // Debug the SQL error
}

$stmt->bind_param("issiissi", $contractorId, $name, $aadhaar, $age, $phone, $address, $verification, $wages);

if ($stmt->execute()) {
    echo "Worker added successfully to the $serviceType table!";
} else {
    echo "Error adding worker: " . $conn->error;
}


    // Close connections
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Worker</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="number"],
        input[type="tel"],
        textarea {
            width: 96%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        textarea {
            resize: vertical;
        }

        button {
            background-color: black;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }

        button:hover {
            background-color: grey;
        }

        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Add Worker</h1>
        <form id="workerForm" action="" method="POST">
            <label for="contractorId">Contractor ID:</label>
            <input type="text" id="contractorId" name="contractorId" required>

            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="aadhaar">Aadhaar Card:</label>
            <input type="text" id="aadhaar" name="aadhaar" required>

            <label for="age">Age:</label>
            <input type="number" id="age" name="age" required>

            <label for="phone">Phone Number:</label>
            <input type="tel" id="phone" name="phone" required>

            <label for="address">Address:</label>
            <textarea id="address" name="address" required></textarea>

            <label>Police Verification Status:</label>
            <label for="verified-yes">
                <input type="radio" id="verified-yes" name="verification" value="yes" required> Yes
            </label>
            <label for="verified-no">
                <input type="radio" id="verified-no" name="verification" value="no"> No
            </label>

            <label for="serviceType">Select Worker Service:</label>
            <select id="serviceType" name="serviceType" required>
                <option value="" disabled selected>Select a service type</option>
                <option value="maid">Maid</option>
                <option value="nurse">Nurse</option>
                <option value="tutor">Tutor</option>
                <option value="driver">Driver</option>
                <option value="security">Security</option>
                <option value="maintenance">Maintenance</option>
                <!-- Add other worker categories here -->
            </select>

            <label for="wages">Wages (in INR):</label>
            <input type="number" id="wages" name="wages" required>

            <button type="submit">Add Worker</button>
        </form>
    </div>

    <script>
        document.getElementById('workerForm').addEventListener('submit', function(event) {
            const age = parseInt(document.getElementById('age').value);
            const phone = document.getElementById('phone').value;
            const aadhaar = document.getElementById('aadhaar').value;
            const wages = parseFloat(document.getElementById('wages').value);

            // Validation
            if (age < 18) {
                alert("Age must be greater than 18.");
                event.preventDefault();
                return;
            }

            if (phone.length !== 10 || isNaN(phone)) {
                alert("Phone number must be 10 digits.");
                event.preventDefault();
                return;
            }

            if (aadhaar.length !== 12 || isNaN(aadhaar)) {
                alert("Aadhaar number must be 12 digits.");
                event.preventDefault();
                return;
            }

            if (wages <= 0 || isNaN(wages)) {
                alert("Wages must be a valid number greater than 0.");
                event.preventDefault();
                return;
            }
        });
    </script>
</body>
</html>
