<?php
// Ensure the form data is received
if (isset($_POST['StaffID'])) {
    $StaffID = $_POST['StaffID'];

    // Validate the StaffID to ensure it's numeric
    if (!is_numeric($StaffID) || $StaffID <= 0) {
        echo "Invalid Staff ID input.";
        exit;
    }

    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'VSCODE');

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare SQL query to delete the staff member
    $sql = "DELETE FROM Staff WHERE StaffID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $StaffID); // 'i' for integer (StaffID)

    // Execute the statement
    if ($stmt->execute()) {
        echo "<br>✅ Deletion Successful for Staff ID: $StaffID";
    } else {
        echo "❌ Error deleting record: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
} else {
    echo "❌ Please provide a valid Staff ID to delete.";
}
?>













