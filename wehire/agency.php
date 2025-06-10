<?php
// Database connection parameters
$servername = "localhost"; // Change if your DB is hosted elsewhere
$username = "root"; // Change to your database username
$password = "ashuannu"; // Change to your database password
$dbname = "wehire"; // Change to your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form data is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $age = $_POST['age'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $aadhar_no = $_POST['aadhar_no']; // Retrieve Aadhaar number
    $verification = $_POST['verification'];
    $type_of_service = $_POST['type_of_service'];
    $mass_hiring = $_POST['mass_hiring'];
    $password = $_POST['password'];

    // Prepare SQL statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO Agency (name, age, address, phone_no, aadhar_no, Police_ve_stat, type_of_service, mass_hiring, ratings, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sissssssss", $name, $age, $address, $phone, $aadhar_no, $verification, $type_of_service, $mass_hiring, $ratings, $password);

    // Set default rating (e.g., 0) or change according to your logic
    $ratings = 0;

    // Execute the statement
    if ($stmt->execute()) {
        // Redirect to home page
        header("Location: ahome.html"); // Change to your actual home page URL
        exit();
    } else {
        echo "<h1>Error</h1>";
        echo "<p>There was an error registering your account: " . $stmt->error . "</p>";
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
} 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agency Registration</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #f4f4f4;
            text-align: center;
            padding: 20px;
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
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: inline-block;
            text-align: left; /* Align text to the left */
        }

        input, select {
            display: block;
            margin: 10px 0;
            padding: 10px;
            width: 300px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            padding: 10px 20px;
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
        <h1>Agency Registration</h1>
    </header>
    <main>
        <form id="agencyForm" method="post" action="" onsubmit="return validateForm()">
            <input type="text" name="name" id="name" placeholder="Name" required>
            <input type="number" name="age" id="age" placeholder="Age" required>
            <input type="text" name="address" id="address" placeholder="Address" required>
            <input type="tel" name="phone" id="phone" placeholder="Phone Number" required pattern="\d{10}">
            <input type="text" name="aadhar_no" id="aadhar_no" placeholder="Aadhaar Number" required pattern="\d{12}">

            <div class="radio-group">
                <p>Police Verification status:</p>
                <label>
                    <input type="radio" name="verification" value="yes" required> Yes
                </label>
                <label>
                    <input type="radio" name="verification" value="no"> No
                </label>
            </div>

            <label for="serviceType">Type of Service:</label>
            <select name="type_of_service" id="serviceType" required>
                <option value="" disabled selected>Select service type</option>
                <option value="tutors">Tutors</option>
                <option value="nurses">Nurses</option>
                <option value="drivers">Drivers</option>
                <option value="security">Security</option>
                <option value="maids">Maids</option>
                <option value="labourers">Labourers</option>
            </select>

            <div class="radio-group">
                <p>Mass Hiring:</p>
                <label>
                    <input type="radio" name="mass_hiring" value="yes" required> Yes
                </label>
                <label>
                    <input type="radio" name="mass_hiring" value="no"> No
                </label>
            </div>

            <input type="password" name="password" id="password" placeholder="Create Password" required>
            <input type="password" id="confirmPassword" placeholder="Confirm Password" required>
            <button type="submit">Register</button>
            <div class="error" id="error"></div>
        </form>
    </main>

    <script>
        function validateForm() {
            const name = document.getElementById('name').value;
            const age = document.getElementById('age').value;
            const address = document.getElementById('address').value;
            const phone = document.getElementById('phone').value;
            const aadhar_no = document.getElementById('aadhar_no').value; // Validate Aadhaar
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            const errorDiv = document.getElementById('error');

            // Clear any previous error messages
            errorDiv.textContent = "";

            // Check age requirement
            if (age < 18) {
                errorDiv.textContent = "You must be at least 18 years old.";
                return false; // Prevent form submission
            }

            // Check Aadhaar number requirement
            if (!/^\d{12}$/.test(aadhar_no)) {
                errorDiv.textContent = "Aadhaar number must be exactly 12 digits.";
                return false; // Prevent form submission
            }

            // Check password requirements
            const passwordRequirements = /^(?=.*[0-9])(?=.*[!@#$%^&*])[A-Za-z0-9!@#$%^&*]{8,}$/;
            if (!passwordRequirements.test(password)) {
                errorDiv.textContent = "Password must be at least 8 characters long, contain at least one number and one special character.";
                return false; // Prevent form submission
            }

            // Check if passwords match
            if (password !== confirmPassword) {
                errorDiv.textContent = "Passwords do not match.";
                return false; // Prevent form submission
            }

            // If all validations pass
            return true; // Allow form submission
        }
    </script>
</body>
</html>
