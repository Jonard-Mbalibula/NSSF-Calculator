<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Payroll Summary</title>
   <style>
      body {
         font-family: Arial, sans-serif;
         background: #f0f4f8;
         padding: 40px;
         color: #333;
      }

      .summary-box {
         background: #fff;
         border-radius: 10px;
         box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
         padding: 30px;
         max-width: 600px;
         margin: auto;
      }

      .summary-box h2 {
         text-align: center;
         color: #2c3e50;
         margin-bottom: 25px;
      }

      .summary-box p {
         font-size: 18px;
         margin: 12px 0;
      }

      .success {
         color: green;
         font-weight: bold;
         margin-top: 20px;
         text-align: center;
      }

      .error {
         color: red;
         font-weight: bold;
         margin-top: 20px;
         text-align: center;
      }

      /* Payslip button styling */
      .print-btn {
         display: inline-block;
         padding: 10px 20px;
         background: #27ae60;
         color: #fff;
         text-decoration: none;
         border-radius: 4px;
         transition: background .2s;
         margin-top: 20px;
      }
      .print-btn:hover {
         background: #219653;
      }
   </style>
</head>
<body>
    <a href="index.html">Back to Homepage</a>
<?php

$StaffID = $_POST['StaffID'];

function calculatePayroll($salary)
{
    $employeeNSSF = $salary * 0.05;
    $employerNSSF = $salary * 0.10;
    $totalNSSF = $employeeNSSF + $employerNSSF;

    $taxableIncome = $salary - $employeeNSSF;
    $tax = 0;

    if ($taxableIncome <= 235000) {
        $tax = 0;
    } elseif ($taxableIncome <= 335000) {
        $tax = ($taxableIncome - 235000) * 0.10;
    } elseif ($taxableIncome <= 410000) {
        $tax = ($taxableIncome - 335000) * 0.20 + 10000;
    } else {
        $tax = ($taxableIncome - 410000) * 0.30 + 25000;
    }

    $netSalary = $salary - $employeeNSSF - $tax;

    return [$totalNSSF, $tax, $netSalary];
}

$Name = "";
$Salary = 0;

if (isset($_POST['Name']) && isset($_POST['Salary'])) {
    $Name = htmlspecialchars($_POST['Name']);
    $Salary = $_POST['Salary'];
}

if (!is_numeric($Salary) || $Salary <= 0) {
    echo "<div class='error'>Invalid salary input.</div>";
    exit;
}

list($nssf, $tax, $netSalary) = calculatePayroll($Salary);
?>

<div class="summary-box">
   <h2>Payroll Summary for <?php echo htmlspecialchars($Name); ?></h2>
   <p><strong>Gross Salary:</strong> UGX <?php echo number_format($Salary); ?></p>
   <p><strong>Total NSSF Contribution (15%):</strong> UGX <?php echo number_format($nssf); ?></p>
   <p><strong>Tax (PAYE):</strong> UGX <?php echo number_format($tax); ?></p>
   <p><strong>Net Salary:</strong> UGX <?php echo number_format($netSalary); ?></p>

<?php
$conn = new mysqli('localhost', 'root', '', 'VSCODE');

if ($conn->connect_error) {
    echo "<p class='error'>Connection failed: " . $conn->connect_error . "</p>";
    exit;
}

$sql = "INSERT INTO Staff (StaffID, Name, Salary, NSSF, TAX, Net_Salary) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("isdddd", $StaffID, $Name, $Salary, $nssf, $tax, $netSalary);

if ($stmt->execute()) {
    echo "<p class='success'>New record created successfully.</p>";
    // Payslip button:
    echo "<p style='text-align:center;'>
            <a href='payslip.php?StaffID=" . urlencode($StaffID) . "' class='print-btn'>
               View / Print Payslip
            </a>
          </p>";
} else {
    echo "<p class='error'>Error: " . $stmt->error . "</p>";
}

$stmt->close();
$conn->close();
?>
</div>

</body>
</html>
