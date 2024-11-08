<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Database connection
$servername = "localhost"; // Replace with your server details
$username = "root"; // Replace with your DB username
$password = ""; // Replace with your DB password
$dbname = "gaudicosddl"; // Replace with your database name

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle which join to display based on button click
$joinType = isset($_GET['join']) ? $_GET['join'] : 'inner';
$query = '';

switch ($joinType) {
    case 'left':
        $query = "SELECT * FROM OrderDetails LEFT JOIN Orders ON OrderDetails.OrderID = Orders.OrderID";
        break;
    case 'right':
        $query = "SELECT * FROM OrderDetails RIGHT JOIN Orders ON OrderDetails.OrderID = Orders.OrderID";
        break;
    case 'outer':
        $query = "SELECT * FROM OrderDetails LEFT JOIN Orders ON OrderDetails.OrderID = Orders.OrderID
                  UNION
                  SELECT * FROM OrderDetails RIGHT JOIN Orders ON OrderDetails.OrderID = Orders.OrderID";
        break;
    case 'inner':
    default:
        $query = "SELECT * FROM OrderDetails INNER JOIN Orders ON OrderDetails.OrderID = Orders.OrderID";
        break;
}


// Fetch and display results
function fetchAndDisplay($query, $conn)
{
    $result = $conn->query($query);
    if ($result && $result->num_rows > 0) {
        echo "<table border='1'><tr>";
        while ($fieldInfo = $result->fetch_field()) {
            echo "<th>{$fieldInfo->name}</th>";
        }
        echo "</tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            foreach ($row as $value) {
                echo "<td>" . htmlspecialchars($value) . "</td>";
            }
            echo "</tr>";
        }
        echo "</table><br>";
    } else {
        echo "<p>No results found for the selected join type.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interactive SQL Joins Viewer</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #eef2f7;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            padding: 20px;
        }

        .container {
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            width: 100%;
            max-width: 10999px;
            text-align: center;
            margin: auto;
        }

        .table-container {
            display: flex;
            justify-content: center;

            width: 100%;
        }

        table {
            width: 90%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        h2 {
            color: #333;
        }

        .button-container {
            margin-bottom: 20px;
        }

        .button-container a {
            color: #fff;
            background-color: #0066cc;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin: 5px;
            display: inline-block;
            transition: background 0.3s ease;
        }

        .button-container a:hover {
            background-color: #005bb5;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Interactive SQL Joins Viewer</h1>
        <p>Hello, <?php echo htmlspecialchars($_SESSION['username']); ?>! Select a join type to view results:</p>

        <div class="button-container">
            <a href="join.php?join=inner">Inner Join</a>
            <a href="join.php?join=left">Left Join</a>
            <a href="join.php?join=right">Right Join</a>
            <a href="join.php?join=outer">Outer Join</a>
        </div>

        <?php fetchAndDisplay($query, $conn); ?>

        <a href="index.php">Back to Main Page</a>
    </div>




</body>

</html>

<?php
$conn->close();
?>