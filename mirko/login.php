

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="styles.css">
  <title>Sign In</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<style>
    body {
  font-family: Arial, sans-serif;
  background-color: #f4f4f4;
}

.container {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
}

.sign-in-form {
  background-color: #fff;
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  width: 300px;
}

.sign-in-form h2 {
  margin-bottom: 20px;
  text-align: center;
}

.input-field {
  margin-bottom: 15px;
}

.input-field input {
  width: 100%;
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 5px;
}

button {
  width: 100%;
  padding: 10px;
  background-color: #007bff;
  color: #fff;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

button:hover {
  background-color: #0056b3;
}

.options {
  margin-top: 15px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.options a {
  text-decoration: none;
  color: #007bff;
  display: flex;
  align-items: center;
}

.options a:hover {
  text-decoration: underline;
}

.options a i {
  margin-left: 5px;
}

.error-message {
  color: red;
  font-size: 12px;
  margin-top: 5px;
}

</style>
</head>
<body>
  <div class="container">
    <form class="sign-in-form" action="index.php" method="post" id="signInForm">
      <h2>Sign In</h2>
      <div class="input-field">
        <input type="email" placeholder="Email" name="email" id="email">
        <span class="error-message" id="emailError"></span>
      </div>
      <div class="input-field">
        <input type="password" placeholder="Password" name="password" id="password">
        <span class="error-message" id="passwordError"></span>
      </div>
      <button type="submit">Sign In</button>
      <div class="options">
        <a href="#" class="forgot-password">Forgot Password <i class="fas fa-question-circle"></i></a>
        <a href="index.php #registerForm" class="sign-up">Sign Up <i class="fas fa-user-plus"></i></a>
      </div>
    </form>
  </div>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script>
    $(document).ready(function() {
      $('#signInForm').submit(function(event) {
        $('.error-message').text('');

        var email = $('#email').val().trim();
        var password = $('#password').val().trim();
        var isValid = true;

        if (email === '') {
          $('#emailError').text('Please enter your email.');
          isValid = false;
        } else {
          var emailRegex = /^[a-zA-Z0-9._%+-]+@(gmail\.com|.*\.ac\.in)$/;
          if (!emailRegex.test(email)) {
            $('#emailError').text('Please enter a valid Gmail or .ac.in email.');
            isValid = false;
          }
        }

        if (password === '') {
          $('#passwordError').text('Please enter your password.');
          isValid = false;
        } else {
          var passwordRegex = /^(?=.*[a-zA-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
          if (!passwordRegex.test(password)) {
            $('#passwordError').text('Password must be at least 8 characters long and contain at least one letter, one number, and one special character.');
            isValid = false;
          }
        }

        if (!isValid) {
          event.preventDefault();
        }
      });
    });
  </script>
</body>
</html>
<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if all required form fields are set
    if (!isset($_POST['email'], $_POST['password'])) {
        die("Error: All fields are required.");
    }
    
    // Database connection parameters
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "radglobal";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get form data
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare and bind the SQL statement to retrieve user data
    $stmt = $conn->prepare("SELECT email, password FROM formdata WHERE email = '$email'");
    $stmt->bind_param("s", $email);

    // Execute the statement
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if user exists
    if ($result->num_rows > 0) {
        // Fetch user data
        $row = $result->fetch_assoc();
        $hashed_password = $row['password'];

        // Verify password
        if (password_verify($password, $hashed_password)) {
            // Password is correct, create session and redirect
            $_SESSION['email'] = $email;
            header("Location: index.php"); // Redirect to dashboard page
            exit();
        } else {
            // Incorrect password
            echo "Incorrect password";
        }
    } else {
        // User does not exist
        echo "User does not exist";
        header("Location: login.php"); // Redirect to dashboard page
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>


