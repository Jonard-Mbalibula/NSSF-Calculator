<?php
// Check if form data was submitted
//if (isset($_POST['StaffId']) && isset($_POST['Salary'])) {
    $StaffId = $_POST['StaffID'];
    $Salary = $_POST['Salary'];
    $Name = $_POST['Name'];
    // Validate salary input
    if (!is_numeric($Salary) || $Salary <= 0) {
        echo "Invalid salary input.";
        exit;
    }

    // Connect to the database
    $conn = new mysqli('localhost', 'root', '', 'VSCODE');

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL statement
    $sql = "UPDATE Staff SET Salary = ? , Name = ? WHERE StaffID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('dsi', $Salary, $Name, $StaffId); 
    if ($stmt->execute()) {
        echo "<br>✅ Update Successful for:<br>Staff ID: $StaffId, New Salary: $Salary";
    } else {
        echo "❌ Error updating record: " . $stmt->error;
    }

    
    $stmt->close();
    $conn->close(); 
// else { echo "Please provide both Staff ID and Salary to update.";

?>
