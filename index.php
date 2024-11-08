<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit;
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
            background-image: url('https://i.gifer.com/QWc9.gif');
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        h1 {
            color: white;
            margin-top: 40px;
            font-size: 2.5em;
            font-family: 'Courier New', Courier, monospace;
        }



        form {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin: 20px 0;
        }

        .button {
            background: linear-gradient(90deg, #00ffff, #0044ff);
            border: none;
            padding: 10px 20px;
            color: #e0e0e0;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1.1em;
            text-shadow: 0 0 2px #000;
            box-shadow: 0 0 10px rgba(0, 255, 255, 0.4), 0 0 20px rgba(0, 68, 255, 0.6);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .button:hover {
            transform: scale(1.1);
            box-shadow: 0 0 15px rgba(0, 255, 255, 0.6), 0 0 30px rgba(0, 68, 255, 0.8);
        }

        .selected {
            background-color: #1abc9c;
            border: 2px solid #16a085;
        }

        .results {
            width: 80%;
            margin: 20px auto;
            background-image: url('https://i.gifer.com/D4Ll.gif');
            color: white;
            font-family: 'Courier New', Courier, monospace;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .results h3 {
            color: whitesmoke;
            margin-bottom: 10px;
            font-family: 'Courier New', Courier, monospace;
        }

        .no-results {
            color: #e74c3c;
            font-weight: bold;
        }

        .sql-syntax {
            width: 80%;
            margin: 20px auto;
            padding: 20px;
            border-left: 5px solid #3498db;
            border-radius: 5px;
            font-family: 'Courier New', Courier, monospace;
            color: white;
            font-size: 1.1em;
            background: rgba(0, 0, 0, 0.7);
        }

        .sql-box {
            border: 1px solid #3a3f47;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.1), 0 0 20px rgba(0, 255, 255, 0.2);
            width: 80%;
            max-width: 600px;
            margin: 0 auto;
            text-align: center;
        }



        @media (max-width: 768px) {
            form {
                flex-direction: column;
            }
        }

        footer {
            font-size: 0.8em;
            color: #666;
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>

<body>

    <h1> SQL Join Types Viewer</h1>

    <?php
    // Database connection details
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "gaudicosddl";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Function to execute query and display results
    function executeAndDisplay($sql, $conn)
    {
        $result = $conn->query($sql);

        echo "<div class='results'>";
        if ($result->num_rows > 0) {
            echo "<h3>Results:</h3>";
            while ($row = $result->fetch_assoc()) {
                echo "<div>OrderDetailsID: <strong>" . $row["OrderDetailsID"] . "</strong>, "
                    . "OrderID: <strong>" . $row["OrderID"] . "</strong>, "
                    . "ProductID: <strong>" . $row["ProductID"] . "</strong>, "
                    . "Quantity: <strong>" . $row["Quantity"] . "</strong>, "
                    . "UnitPrice: <strong>" . $row["UnitPrice"] . "</strong>, "
                    . "Discount: <strong>" . $row["Discount"] . "</strong>, "
                    . "StatusID: <strong>" . $row["StatusID"] . "</strong><br></div>";
            }
        } else {
            echo "<div class='no-results'>No results found.</div>";
        }
        echo "</div>";
    }

    // Determine which join type button was clicked
    $selectedJoin = isset($_POST['join']) ? $_POST['join'] : 'INNER JOIN';

    // SQL syntax display
    $sqlSyntax = "";

    // Display buttons and highlight the selected one
    function displayButton($label, $selectedJoin)
    {
        $class = ($label == $selectedJoin) ? 'button selected' : 'button';
        echo "<button type='submit' name='join' value='$label' class='$class'>$label</button>";
    }
    ?>

    <!-- Form with SQL Join Type buttons -->
    <form method="POST">
        <?php
        displayButton('INNER JOIN', $selectedJoin);
        displayButton('LEFT JOIN', $selectedJoin);
        displayButton('RIGHT JOIN', $selectedJoin);
        displayButton('OUTER JOIN', $selectedJoin);
        ?>
    </form>


    <?php
    // SQL syntax and query execution based on selected join
    if ($selectedJoin == 'INNER JOIN') {
        $sql = "SELECT orderdetails.OrderDetailsID, orderdetails.OrderID, orderdetails.ProductID, orderdetails.Quantity, 
                       orderdetails.UnitPrice, orderdetails.Discount, orderdetails.StatusID, orders.OrderDate, products.ProductName
                FROM orderdetails 
                INNER JOIN orders ON orderdetails.OrderID = orders.OrderID
                INNER JOIN products ON orderdetails.ProductID = products.ID";
        $sqlSyntax = $sql;
        executeAndDisplay($sql, $conn);
    }

    if ($selectedJoin == 'LEFT JOIN') {
        $sql = "SELECT orderdetails.OrderDetailsID, orderdetails.OrderID, orderdetails.ProductID, orderdetails.Quantity, 
                       orderdetails.UnitPrice, orderdetails.Discount, orderdetails.StatusID, orders.OrderDate, products.ProductName
                FROM orderdetails 
                LEFT JOIN products ON orderdetails.ProductID = products.ID
                LEFT JOIN orders ON orderdetails.OrderID = orders.OrderID";
        $sqlSyntax = $sql;
        executeAndDisplay($sql, $conn);
    }

    if ($selectedJoin == 'RIGHT JOIN') {
        $sql = "SELECT orderdetails.OrderDetailsID, orderdetails.OrderID, orderdetails.ProductID, orderdetails.Quantity, 
                       orderdetails.UnitPrice, orderdetails.Discount, orderdetails.StatusID, orders.OrderDate, products.ProductName
                FROM orderdetails 
                RIGHT JOIN orders ON orderdetails.OrderID = orders.OrderID
                RIGHT JOIN products ON orderdetails.ProductID = products.ID";
        $sqlSyntax = $sql;
        executeAndDisplay($sql, $conn);
    }

    if ($selectedJoin == 'OUTER JOIN') {
        $sql = "SELECT orderdetails.OrderDetailsID, orderdetails.OrderID, orderdetails.ProductID, orderdetails.Quantity, 
                       orderdetails.UnitPrice, orderdetails.Discount, orderdetails.StatusID, orders.OrderDate, products.ProductName
                FROM orderdetails 
                LEFT JOIN products ON orderdetails.ProductID = products.ID
                LEFT JOIN orders ON orderdetails.OrderID = orders.OrderID
                UNION
                SELECT orderdetails.OrderDetailsID, orderdetails.OrderID, orderdetails.ProductID, orderdetails.Quantity, 
                       orderdetails.UnitPrice, orderdetails.Discount, orderdetails.StatusID, orders.OrderDate, products.ProductName
                FROM orderdetails 
                RIGHT JOIN orders ON orderdetails.OrderID = orders.OrderID
                RIGHT JOIN products ON orderdetails.ProductID = products.ID";
        $sqlSyntax = $sql;
        executeAndDisplay($sql, $conn);
    }
    ?>

    <!-- Display the SQL syntax used in a box -->
    <div class="sql-syntax">
        <strong>SQL Syntax Used:</strong>
        <div class="sql-box"><?php echo htmlspecialchars($sqlSyntax); ?></div>
    </div>

    <!-- Logout Button -->
    <form action="logout.php" method="POST" style="margin-top: 20px;">
        <button type="submit" class="button">Logout</button>
    </form>

    <?php
    // Close database connection
    $conn->close();
    ?>

</body>

</html>