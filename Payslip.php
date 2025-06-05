<?php
// payslip.php

// 1. Get the StaffID from the query string
if (!isset($_GET['StaffID']) || !is_numeric($_GET['StaffID'])) {
    die('Invalid or missing StaffID.');
}
$StaffID = (int) $_GET['StaffID'];

// 2. Connect to the database
$conn = new mysqli('localhost', 'root', '', 'VSCODE');
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// 3. Fetch the employee’s most recent record
$sql = "
    SELECT Name, Salary, NSSF, TAX, Net_Salary, `timestamp`
      FROM Staff
     WHERE StaffID = ?
  ORDER BY `timestamp` DESC
     LIMIT 1
";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $StaffID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die('No payslip found for StaffID ' . htmlspecialchars($StaffID));
}

$row = $result->fetch_assoc();
$stmt->close();
$conn->close();

// 4. Helper to format numbers
function fmt($n) {
    return number_format($n, 2, '.', ',');
}

// 5. Render HTML payslip
?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Payslip for #<?= htmlspecialchars($StaffID) ?></title>
  <style>
    body { font-family: Arial, sans-serif; background: #f7f7f7; padding: 40px; }
    .payslip { max-width: 600px; margin: auto; background: #fff;
               padding: 30px; border-radius: 8px;
               box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
    .payslip h1 { text-align: center; margin-bottom: 20px; color: #2c3e50; }
    .payslip .info, .payslip .breakdown { width: 100%; margin-bottom: 20px; }
    .info td { padding: 4px 8px; }
    .breakdown th, .breakdown td { text-align: left; padding: 8px; }
    .breakdown th { background: #ecf0f1; }
    .total { font-weight: bold; }
    .print-btn { display: block; width: 100%; margin-top: 20px;
                 padding: 10px; background: #27ae60; color: white;
                 text-align: center; text-decoration: none;
                 border-radius: 4px; }
    .print-btn:hover { background: #219653; }
  </style>
</head>
<body>
<a href="index.html">Back to Homepage</a>
  <div class="payslip">
    <h1>Employee Payslip</h1>

    <table class="info">
      <tr>
        <td><strong>Staff ID:</strong> <?= htmlspecialchars($StaffID) ?></td>
        <td><strong>Date:</strong> <?= date('F j, Y, g:i a', strtotime($row['timestamp'])) ?></td>
      </tr>
      <tr>
        <td colspan="2"><strong>Name:</strong> <?= htmlspecialchars($row['Name']) ?></td>
      </tr>
    </table>

    <table class="breakdown" border="0" cellspacing="0">
      <tr><th>Earnings</th><th>Amount (UGX)</th></tr>
      <tr>
        <td>Gross Salary</td>
        <td><?= fmt($row['Salary']) ?></td>
      </tr>
      <tr><th>Deductions</th><th>Amount (UGX)</th></tr>
      <tr>
        <td>Total NSSF (15%)</td>
        <td><?= fmt($row['NSSF']) ?></td>
      </tr>
      <tr>
        <td>PAYE Tax</td>
        <td><?= fmt($row['TAX']) ?></td>
      </tr>
      <tr class="total">
        <td>Net Salary</td>
        <td><?= fmt($row['Net_Salary']) ?></td>
      </tr>
    </table>

    <a href="javascript:window.print()" class="print-btn">Print Payslip</a>
  </div>
</body>
</html>
