<?php
/**
 * @var $pdo
 */

session_start();
require_once "connection.php";

if(!isset($_SESSION["login"]))
    header("Location: index.php");

?>

<html>
<head>
    <title>Orders</title>
</head>
<body>
<header>
    <nav>
        <ul>
            <li><a href="index.php">Catalog</a></li>
            <li><a href="cart.php">Cart</a></li>
            <li><a href="logout.php">Log out</a></li>
        </ul>
    </nav>
</header>
<section>
    <h2>Orders</h2>
    <?php
    $query = "SELECT * FROM orders WHERE id_user = " . $_SESSION["id"];
    $stmt = $pdo->query($query);
    while ($row = $stmt->fetch()) {
        echo '<a href="detail.php?id=' . $row["id"] . '">Order No. ' . $row["id"] . ' Detail</a><br>';
    }
    ?>
</section>
</body>
</html>