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
        }else{
            echo '<script>alert("Product is already in  the cart")</script>';
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
    if(isset($_GET["redirect"]) && $_GET["redirect"] == "cart") {
        header("Location: cart.php");
        exit();
    }
}

if(isset($_GET["action"])){
    if($_GET["action"] == "delete"){
        foreach($_SESSION["shopping_cart"] as $keys => $value){
            if($value["product_id"] == $_GET["id"]){
                unset($_SESSION["shopping_cart"][$keys]);
                echo '<script>alert("Product has been removed")</script>';
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

    <title>Shop Here</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav>
        <span>Shop Here</span>
        <div>
            <a href="login.php">Login</a>
            <a href="cart.php"><span>Cart</span></a>
        </div>
    </nav>
    <main>


    <h2>Products</h2>
<div class="container">
        <?php
        $query = "select * from product_details order by id asc";

        $result = mysqli_query($conn,$query);

        if(mysqli_num_rows($result)>0){
        
            while($row = mysqli_fetch_array($result)){
                ?>
                <div>
                    <form method="post" action="index.php?action=add&id=<?php echo $row["id"];?>&redirect=cart">
                        <div class="product">
                            <img src="img/<?php echo $row["image"];?>">
                            <h3><?php echo $row["description"];?></h3>
                            <p>Rs <?php echo $row["price"];?>.00</p>
                            <input type="text" id="quantity" name="quantity" value="1">
                            <input type="hidden" name="hidden_name" value="<?php echo $row["description"];?>">
                            <input type="hidden" name="hidden_price" value="<?php echo $row["price"];?>">
                            <input type="submit" name="add"  value="Add to cart">
                        </div>
                    </form>
                </div>
                <?php
            }
        }
        ?>
        </div>
        
        </main>  
        
        <footer></footer>
        </body>
        </html>
