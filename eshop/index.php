<?php
/**
 * @var $pdo
 */

session_start();
ob_start();
require_once "connection.php";

function createCatalog($pdo)
{
    $arr[][] = NULL;
    $i = 0;
    $query = "SELECT * FROM products";
    $stmt = $pdo->query($query);
    while ($row = $stmt->fetch()) {
        $arr[$i]["id"] = $row["id"];
        $arr[$i]["img"] = $row["img"];
        $arr[$i]["name"] = $row["name"];
        $arr[$i]["price"] = $row["price"];
        $i++;
    }
    return $arr;
}

$catalog = createCatalog($pdo);

function getBy($att, $value, $array)
{
    foreach ($array as $key => $val) {
        if ($val[$att] == $value) {
            return $key;
        }
    }
    return null;
}

if (isset($_GET["action"])) {
    if ($_GET["action"] == "add" && !empty($_GET["id"])) {
        addToCart($_GET["id"]);
        header("Location: index.php");
    }

    if ($_GET["action"] == "remove" && !empty($_GET["id"])) {
        removeFromCart($_GET["id"]);
        header("Location: index.php");
    }

    if ($_GET["action"] == "delete" && !empty($_GET["id"])) {
        deleteFromCart($_GET["id"]);
        header("Location: index.php");
    }
}

if (isset($_POST["Order"])) {
    $query = 'INSERT INTO orders(id_user) VALUES(' . $_SESSION["id"] . ')';
    $pdo->query($query);
    $query = "SELECT id FROM orders ORDER BY id DESC LIMIT 1";
    $stmt = $pdo->query($query);
    $orderId = $stmt->fetch()[0];
    foreach ($_SESSION["cart"] as $key => $value) {
        $item = $catalog[getBy("id", $key, $catalog)];
        $query = 'INSERT INTO ordereditems(id_order, id_product, quantity, pricePerPiece) VALUES(' . $orderId . ', ' . $item["id"] . ', ' . $value["quantity"] . ',' . $item["price"] . ')';
        $pdo->query($query);
    }

    echo "<p>OK</p>";
}

function addToCart($productId)
{
    if (!array_key_exists($productId, $_SESSION["cart"])) {
        $_SESSION["cart"][$productId]["quantity"] = 1;
    } else {
        $_SESSION["cart"][$productId]["quantity"]++;
    }
}

function removeFromCart($productId)
{
    if (array_key_exists($productId, $_SESSION["cart"])) {
        if ($_SESSION["cart"][$productId]["quantity"] <= 1) {
            deleteFromCart($productId);
        } else {
            $_SESSION["cart"][$productId]["quantity"]--;
        }
    }
}

function deleteFromCart($productId)
{
    unset($_SESSION["cart"][$productId]);
}

?>

<html>
<head>
    <title>Catalog</title>
    <style>
        .catalog-item {
            width: 200px;
            background-color: beige;
            height: 300px;
            margin: 5px;
        }

        .catalog-img {
            font-size: 100px;
        }

        .catalog-buy-button {
            margin: 5px;
            padding: 5px;
            border: 1px solid yellow;
            background-color: yellowgreen;
            text-align: center;
        }

        #catalog-items {
            display: flex;
        }

        a.catalog-buy-button {
            display: block;
        }
    </style>
</head>
<body>
<header>
    <nav>
        <ul>
            <?php
            if (!isset($_SESSION["login"])) {
                echo '<li><a href="login.php">Login</a></li>';
                echo '<li><a href="register.php">Register</a></li>';
            } else {
                echo '<li><a href="cart.php">Cart</a></li>';
                echo '<li><a href="orders.php">Orders</a></li>';
                echo '<li><a href="logout.php">Log out</a></li>';
            }
            ?>
        </ul>
    </nav>
</header>
<h2>Catalog</h2>
<section id="catalog-items">
    <?php
    foreach ($catalog as $item) {
        echo '
<div class="catalog-item">
<div class="catalog-img">
' . $item["img"] . '
</div>
<h3>
' . $item["name"] . '
</h3>
<div>
' . $item["price"] . '
</div>';
        if (isset($_SESSION["login"])) {
            echo '<a href="index.php?action=add&id=' . $item["id"] . '" class="catalog-buy-button">Buy</a>';
        }
        echo '</div>';
    }
    ?>
</section>
</body>
</html>