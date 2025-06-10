<?php
// register_employer.php
$host = 'localhost'; // Your database host
$db = 'wehire'; // Your database name
$user = 'root'; // Your database username
$pass = 'ashuannu'; // Your database password

// Create a new PDO instance
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Check if form data is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the POST data
    $name = $_POST['name'];
    $age = $_POST['age'];
    $aadhaar = $_POST['aadhaar'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $password = $_POST['password']; // It's highly recommended to hash passwords in production

    // Prepare and execute the insert statement
    $stmt = $pdo->prepare("INSERT INTO Customer (name, age, aadhaar_no, address, phone_no, password) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$name, $age, $aadhaar, $address, $phone, $password]);

    // Redirect to home page on successful registration
    header("Location: home.php"); // Change to your actual home page URL
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employer Registration</title>
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
        }

        input {
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
        <h1>Employer Registration</h1>
    </header>
    <main>
        <form id="employerForm" method="post" action="">
            <input type="text" name="name" placeholder="Name" required>
            <input type="number" name="age" placeholder="Age" required>
            <input type="text" name="aadhaar" placeholder="Aadhaar No" required pattern="\d{12}">
            <input type="text" name="address" placeholder="Address" required>
            <input type="tel" name="phone" placeholder="Phone Number" required pattern="\d{10}">
            <input type="password" name="password" placeholder="Create Password" required>
            <input type="password" name="confirmPassword" placeholder="Confirm Password" required>
            <button type="submit">Register</button>
            <div class="error" id="error"></div>
        </form>
    </main>

    <script>
        document.getElementById('employerForm').addEventListener('submit', function(event) {
            const password = document.querySelector('input[name="password"]').value;
            const confirmPassword = document.querySelector('input[name="confirmPassword"]').value;
            const errorDiv = document.getElementById('error');

            // Clear previous error messages
            errorDiv.textContent = "";

            // Check if passwords match
            if (password !== confirmPassword) {
                errorDiv.textContent = "Passwords do not match.";
                event.preventDefault(); // Prevent form submission
                return;
            }
        });
    </script>
</body>
</html>
