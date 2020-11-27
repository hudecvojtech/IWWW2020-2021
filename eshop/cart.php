<?php
/**
 * @var $pdo
 */
session_start();
require_once "connection.php";

if(!isset($_SESSION["login"]))
    header("Location: index.php");

function getBy($att, $value, $array)
{
    foreach ($array as $key => $val) {
        if ($val[$att] == $value) {
            return $key;
        }
    }
    return null;
}

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

if (isset($_GET["action"])) {
    if ($_GET["action"] == "add" && !empty($_GET["id"])) {
        addToCart($_GET["id"]);
        header("Location: cart.php");
    }

    if ($_GET["action"] == "remove" && !empty($_GET["id"])) {
        removeFromCart($_GET["id"]);
        header("Location: cart.php");
    }

    if ($_GET["action"] == "delete" && !empty($_GET["id"])) {
        deleteFromCart($_GET["id"]);
        header("Location: cart.php");
    }
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
    <title>ESHOP</title>
    <style>
        .cart-button {
            margin: 5px;
            padding: 5px;
            border: 1px solid yellow;
            background-color: yellowgreen;
            text-align: center;
        }

        .cart-item {
            justify-content: space-between;
            display: flex;
            margin: 5px;
            border: 1px solid yellowgreen;
            padding: 5px;
        }

        .cart-quantity {
            margin: 10px;
        }

        .cart-price {
            margin: 10px;

        }

        .cart-control {
            display: flex;
        }

        #cart-total-price {
            font-weight: bold;
        }
    </style>
</head>
<body>
<header>
    <nav>
        <ul>
            <?php
                if(!isset($_SESSION["login"])) {
                    echo '<li><a href="login.php">Login</a></li>';
                    echo '<li><a href="register.php">Register</a></li>';
                } else {
                    echo '<li><a href="index.php">Catalog</a></li>';
                    echo '<li><a href="orders.php">Orders</a></li>';
                    echo '<li><a href="logout.php">Log out</a></li>';
                }
            ?>
        </ul>
    </nav>
</header>

<section>
<h2>Cart</h2>
    <?php
    if (isset($_SESSION["cart"])) {
        $totalPrice = 0;
        foreach ($_SESSION["cart"] as $key => $value) {

            $item = $catalog[getBy("id", $key, $catalog)];
            $totalPrice = $totalPrice + ($value["quantity"] * $item["price"]);
            echo '
<div class="cart-item">
<div class="cart-img">
' . $item["img"] . '
</div>
<div>
' . $item["name"] . '
</div>
<div class="cart-control">
<div class="cart-price">
' . $item["price"] . '
</div>
<div class="cart-quantity">
' . ($value["quantity"]) . '
</div>
<div class="cart-quantity">
' . ($value["quantity"] * $item["price"]) . '
</div>
<a href="cart.php?action=add&id=' . $item["id"] . '" class="cart-button">
+
</a>
<a href="cart.php?action=remove&id=' . $item["id"] . '" class="cart-button">
-
</a>
<a href="cart.php?action=delete&id=' . $item["id"] . '" class="cart-button">
x
</a>
</div>
</div>';
        }
        if($totalPrice != 0) {
            echo '<div id="cart-total-price">Total price: ' . $totalPrice . '</div>
            <form action="index.php" method="post">
            <input type="submit" value="Order" name="Order">
            </form>';
        }
    }
    ?>

</section>
</body>
</html>