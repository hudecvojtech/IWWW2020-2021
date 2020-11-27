<?php
/**
 * @var $pdo
 */

session_start();
require_once "connection.php";

if(!isset($_SESSION["login"]) || !isset($_GET["id"]))
    header("Location: index.php");

?>

<html>
<head>
    <title>Detail</title>
</head>
<body>
<header>
    <nav>
        <ul>
            <li><a href="index.php">Catalog</a></li>
            <li><a href="cart.php">Cart</a></li>
            <li><a href="orders.php">Orders</a></li>
            <li><a href="logout.php">Log out</a></li>
        </ul>
    </nav>
</header>
<section>
    <h2>Detail</h2>
    <?php
    if(isset($_GET["id"])) {
        $id = $_GET["id"];
        $query = "SELECT * FROM orders WHERE id_user = " . $_SESSION["id"] . " AND id = " . $id;
        $stmt = $pdo->query($query);
        if($stmt->rowCount() != 1)
            header("Location: index.php");

        $query = "SELECT * FROM ordereditems WHERE id_order = '$id'";
        $stmt = $pdo->query($query);
        $sum = 0;
        while($row = $stmt->fetch()) {
            $productId = $row["id_product"];
            $query = "SELECT name from products WHERE id = '$productId'";
            $stmtName = $pdo->query($query);
            echo $stmtName->fetch()[0] . ": ";
            echo $row["quantity"] . " pieces with price per one: ";
            echo $row["pricePerPiece"] . "<br>";
            $sum += $row["quantity"] * $row["pricePerPiece"];
        }
        echo "Total price: " . $sum;
    }
    ?>
</section>
</html>
