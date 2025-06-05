<?php
session_start();

$conn = new mysqli('localhost', 'root', '', 'VSCODE');
if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
}

if (isset($_POST['username'], $_POST['password'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);  // change to "s" only if password is hashed

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Login successful
        $_SESSION['user'] = $username;
        header("Location: dashboard.php"); // redirect to a logged-in homepage
        exit();
    } else {
        echo "❌ Invalid credentials. Try again.";
    }

    $stmt->close();
} else {
    echo "❌ Please enter both username and password.";
}

$conn->close();
?>
