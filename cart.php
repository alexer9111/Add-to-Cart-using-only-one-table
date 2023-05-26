<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "product";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if(!$conn){
    echo "Failed to connect: " . mysqli_connect_error();
}

if(isset($_POST["add"])){
    if(isset($_SESSION["shopping_cart"])){
        $item_array_id = array_column($_SESSION["shopping_cart"],"product_id");
        if(!in_array($_GET["id"],$item_array_id)){
            $count = count($_SESSION["shopping_cart"]);
            $item_array = array(
                'product_id' => $_GET["id"],
                'product_name' => $_POST["hidden_name"],
                'product_price' => $_POST["hidden_price"],
                'product_quantity' => $_POST["quantity"],
            );
            $_SESSION["shopping_cart"][$count] = $item_array;
            echo '<script>window.location="index.php"</script>';
        }
    }else{
        $item_array = array(
            'product_id' => $_GET["id"],
            'product_name' => $_POST["hidden_name"],
            'product_price' => $_POST["hidden_price"],
            'product_quantity' => $_POST["quantity"],
        );
        $_SESSION["shopping_cart"][0] = $item_array;
    }
}

if(isset($_GET["action"])){
    if($_GET["action"] == "delete"){
        foreach($_SESSION["shopping_cart"] as $keys => $value){
            if($value["product_id"] == $_GET["id"]){
                unset($_SESSION["shopping_cart"][$keys]);
                echo '<script>window.location="cart.php"</script>';
            }
        }
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Cart</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav>
        <a href="index.php"><span>Shop Here</span></a>
        <div>
            <a href="login.php">Login</a>
            <span>Cart</span>

        </div>
    </nav>

    <h3>Cart</h3>
    <div>
        <table>
            <tr>
                <th>Product Img</th>
                <th>Name</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total Price</th>
                <th>Remove Item</th>
            </tr>
            <?php
            if(!empty($_SESSION["shopping_cart"])){
                $total=0;
                foreach($_SESSION["shopping_cart"] as $key => $value){
                    ?>
                    <tr>
                        <td><img src="img/<?php echo $row["image"];?>"></td>
                        <td><?php echo $value["product_name"];?></td>
                        <td><?php echo $value["product_quantity"];?></td>
                        <td><?php echo $value["product_price"];?></td>
                        <td><?php echo number_format($value["product_quantity"]*$value["product_price"],2);?></td>
                        <td><a href="index.php?action=delete&id=<?php echo $value["product_id"]; ?>"><span>Remove Item</span></a></td>
                    </tr>
                    <?php
                    $total = $total + ($value["product_quantity"]*$value["product_price"]);
                }
                ?>
                <tr></tr>
                <tr></tr>
                <tr></tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>Total</td>
                    <td><?php echo number_format($total,2);?></td>
                    <td><button>Proceed to Payment</button></td>
                </tr>
                <?php
            }
            ?>
        </table>
    </div>
    <footer></footer>
</body>
</html>
