<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Staff Records</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            padding: 20px;
            text-align: center;
        }

        h1 {
            color: #333;
        }

        table {
            margin: 0 auto;
            border-collapse: collapse;
            width: 90%;
            background: #fff;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 12px 15px;
            border: 1px solid #ccc;
        }

        th {
            background: #007bff;
            color: white;
        }

        tr:nth-child(even) {
            background: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        a {
            display: inline-block;
            margin-top: 15px;
            color: #007bff;
            text-decoration: none;
        }
    </style>
</head>
<body>
<a href="index.html">Back to Homepage</a>
<h1>All Staff Records</h1>

<?php
$conn = new mysqli("localhost", "root", "", "VSCODE");

if ($conn->connect_error) {
    die("<p style='color:red;'>Connection failed: " . $conn->connect_error . "</p>");
}

$sql = "SELECT * FROM Staff ORDER BY timestamp DESC"; // Shows latest records first
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table>
            <tr>
                <th>Staff ID</th>
                <th>Name</th>
                <th>Salary</th>
                <th>NSSF</th>
                <th>Tax</th>
                <th>Net Salary</th>
                <th>Date</th>
            </tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['StaffID']}</td>
                <td>{$row['Name']}</td>
                <td>UGX " . number_format($row['Salary']) . "</td>
                <td>UGX " . number_format($row['NSSF']) . "</td>
                <td>UGX " . number_format($row['TAX']) . "</td>
                <td>UGX " . number_format($row['Net_Salary']) . "</td>
                <td>{$row['timestamp']}</td>
              </tr>";
    }

    echo "</table>";
} else {
    echo "<p>No records found.</p>";
}

$conn->close();
?>

<a href="register.html">← Back to Registration</a>

</body>
</html>
