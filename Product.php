<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Product Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: url('Photos/background.jpg') no-repeat center center fixed;
            background-size: cover;
            position: relative;
            overflow: hidden;
        }

        .content {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
    </style>
</head>
<body class="content">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="Icons/icon.png" alt="Link 2" class="d-inline-block align-text-top me-2" ID="brand" style="max-height: 25px;">
                <b>Warland</b>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" ID="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="#"></a></li>
                    <li class="nav-item"><a class="nav-link" href="Main.php"><b>Main page</b></a></li>
                    <li class="nav-item"><a class="nav-link" href="Cart.php"><b>Cart</b></a></li>
                    <li class="nav-item"><a class="nav-link" href="#"><b>Profile</b></a></li>
                    <li class="nav-item"><a class="nav-link" href="#"><b>Settings</b></a></li>
                </ul>
            </div>
        </div>
    </nav>
    <br><br>

    <!-- Content -->

    <?php
    include "Conn.php";

    if (isset($_GET['ID']) && is_numeric($_GET['ID'])) {
        $productID = $_GET['ID'];
        $stmt = $mysqli->prepare("SELECT * FROM Products WHERE ID = ?");
        $stmt->bind_param("i", $productID);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $productDetails = $result->fetch_assoc();
    ?>

    <div class="container my-5 rounded bg-secondary" style="padding: 20px;">
    
        <div class="row align-items-center">
        <div class="row">
        <div class="col">
        <h1 class="mb-4"><b><?php echo htmlspecialchars($productDetails['Name']); ?></b></h1>
        </div>
        <div class="col d-flex justify-content-end">
            <a href="Main.php" class="btn btn-primary " style="height:40px;" >Go back</a>
        </div>
    </div>
            <div class="col-md-6">
                <div class="position-relative">
                    <img src="Photos/<?php echo htmlspecialchars($productDetails['Product_Link']); ?>" class="product-img img-fluid rounded" alt="<?php echo htmlspecialchars($productDetails['Name']); ?>">
                </div>
            </div>
            <div class="col-md-6 ">
                <div class="product-description border bg-white p-2 rounded" style="border-width: 5px; border-radius: 0;">
                    <h3 class="fw-bold"><?php echo htmlspecialchars($productDetails['Name']); ?></h3>
                    <span class="lead mb-4">Description: <?php echo htmlspecialchars($productDetails['Description']); ?></span>
                    <br>
                    <span class="lead mb-4">Price: <?php echo number_format($productDetails['Price'], 2); ?>$</span>
                    <br>
                    <span class="lead mb-4">Year of production: <?php echo intval($productDetails['Production_year']); ?></span>
                    <br>
                    <span class="lead mb-4">Condition: <?php echo htmlspecialchars($productDetails['Condition']); ?></span>
                    <br>
                    <span class="lead mb-4">Company: <?php echo htmlspecialchars($productDetails['Company']); ?></span>

                    <div class="mb-4">
                        <span class="lead mb-4">Country: <?php echo htmlspecialchars($productDetails['Country']); ?>
                        <?php
                        $countryFlagPath = 'Flags/' . htmlspecialchars($productDetails['Country']) . '.jpg';
                        if (file_exists($countryFlagPath)) {
                            echo '<img class="img-fluid" src="' . $countryFlagPath . '" alt="' . htmlspecialchars($productDetails['Country']) . '" height="150" width="50">';
                        }
                        ?>
                        </span>
                    </div>

                    <div class="mb-4">
                    <div class="mb-4">
    <form action="Cart.php" method="GET">
        <input type="hidden" name="add_to_cart" value="<?php echo htmlspecialchars($productDetails['ID']); ?>">
        <input type="number" name="quantity" class="form-control" min="1" max="<?php echo htmlspecialchars($productDetails['Availability']); ?>" value="1" required style="width: 80px;">
        <br>
        <button type="submit" class="btn btn-primary">Add to Cart</button>
    </form>
</div>

    <?php
    // Set cookies to store product information when adding to cart
    if (isset($_GET['add_to_cart']) && isset($_GET['quantity'])) {
        $productID = $_GET['add_to_cart'];
        $quantity = $_GET['quantity'];

        $cartItems = [];

        if (isset($_COOKIE['cart']) && is_array(json_decode($_COOKIE['cart'], true))) {
            $cartItems = json_decode($_COOKIE['cart'], true);
        }

        $productFound = false;

        foreach ($cartItems as &$item) {
            if (isset($item['ID']) && $item['ID'] === $productID) {
                $item['Quantity'] += $quantity;
                $productFound = true;
                break;
            }
        }

        if (!$productFound) {
            $cartItems[] = array('ID' => $productID, 'Quantity' => $quantity);
        }

        setcookie('cart', json_encode($cartItems), time() + 900, "/");
    }
    ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
            } else {
                echo "Product not found.";
            }

            $stmt->close();
            $mysqli->close();
        } else {
            echo 'Error: Invalid product identifier.';
            exit;
        }
    ?>
</body>
</html>
