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
    $phoneNum = $_POST['phonenum']; // Phone number input name
    $password = $_POST['psw']; // Password input name

    // Prepare SQL statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM customer WHERE phone_no = ?");
    $stmt->bind_param("s", $phoneNum);
    
    // Execute the statement
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if user exists
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc(); // Fetch user data
        // Verify the password using password_verify()
        if (password_verify($password, $user['password'])) {
            // Successful login
            echo "<h1>Welcome!</h1>";
            echo "<p>You have successfully logged in.</p>";
            // Always exit after header redirection
        } else {
            // Invalid password
            echo "<h1>Login Failed</h1>";
            echo "<p class='error'>Invalid phone number or password.</p>";
        }
    } else {
        // Invalid phone number
        echo "<h1>Login Failed</h1>";
        echo "<p class='error'>Invalid phone number or password.</p>";
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
} 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>SIGN IN PAGE</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #f4f4f4;
            text-align: center;
            padding: 20px;
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
    <form class="signin-form" method="post" action="">
        <h1><u> Employer Login</u></h1>
        <p>Use your registered phone number to sign into your account.</p>
        <hr>
        
        <label for="phonenum"><b>Phone Number</b></label>
        <input type="text" placeholder="Enter Phone number" name="phonenum" id="phonenum" required>
        
        <br><hr>
        
        <label for="psw"><b>Password</b></label>
        <input type="password" placeholder="Enter Password" name="psw" id="psw" required>
        
        <br><hr>
        
        <button type="submit" class="signinbtn">Sign in</button>
        <p class="error" id="error-message"></p>
    </form>
    
    <div class="register">
        <p>Don't have an account? <a href="employer.php">Register</a>.</p>
    </div>
</body>
<script>
        document.addEventListener("DOMContentLoaded", function () {
            const form = document.querySelector(".signin-form");
            const phoneInput = document.getElementById("phonenum");
            const passwordInput = document.getElementById("psw");
            const errorMessage = document.getElementById("error-message");

            form.addEventListener("submit", function (event) {
                errorMessage.textContent = ""; // Clear previous errors

                // Validate phone number (must be 10 digits)
                const phonePattern = /^\d{10}$/;
                if (!phonePattern.test(phoneInput.value)) {
                    errorMessage.textContent = "Please enter a valid 10-digit phone number.";
                    event.preventDefault(); // Prevent form submission
                    return;
                }

                // Validate password (check minimum length)
                if (passwordInput.value.length < 6) {
                    errorMessage.textContent = "Password must be at least 6 characters long.";
                    event.preventDefault(); // Prevent form submission
                    return;
                }
            });
        });
    </script>
</html>
