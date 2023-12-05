<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); 
}

include "Conn.php";

if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_GET['add_to_cart']) && isset($_GET['quantity'])) {
    $productID = $_GET['add_to_cart'];
    $quantity = $_GET['quantity'];

    if (!is_numeric($quantity) || $quantity <= 0) {
        echo "Invalid quantity.";
        exit;
    }

    $productFound = false;

    foreach ($_SESSION['cart'] as &$item) {
        if (isset($item['ID']) && $item['ID'] === $productID) {
            $item['Availability'] += $quantity;
            $productFound = true;
            break;
        }
    }

    if (!$productFound) {
        $_SESSION['cart'][] = array('ID' => $productID, 'Availability' => $quantity);
    }

    header("Location: Cart.php");
    exit();
}

if (isset($_GET['remove_from_cart'])) {
    $productID = $_GET['remove_from_cart'];

    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['ID'] === $productID) {
            unset($_SESSION['cart'][$key]);
            break;
        }
    }

    header("Location: Cart.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
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
        <li class="nav-item"><a class="nav-link" href="Cart.php"><b>cart</b></a></li>
        <li class="nav-item"><a class="nav-link" href="#"><b>profile</b></a></li>
        <li class="nav-item"><a class="nav-link" href="#"><b>settings</b></a></li>
      </ul>
    </div>
  </div>
</nav>
<br>
<!-- Content -->
<div class="container my-5 rounded bg-secondary" style="padding: 20px;">
    <div class="row">
        <div class="col">
            <h1><b>Cart</b></h1>
        </div>
        <div class="col d-flex justify-content-end">
            <a href="Main.php" class="btn btn-primary" style="height: 40px;">Go back</a>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <table class="table">
                <thead>
                    <tr >
                        <th >Name</th>
                        <th>Quantity</th>
                        <th>Condition</th>
                        <th>Unit Price</th>
                        <th>Total Price</th>
                        <th>Production Year</th>
                        <th>Remove</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!empty($_SESSION['cart'])) {
                        $totalPrice = 0;
                        foreach ($_SESSION['cart'] as $item) {
                            $stmt = $mysqli->prepare("SELECT Name, Price, `Condition`, Country, Production_year FROM Products WHERE ID = ?");
                            $stmt->bind_param("i", $item['ID']);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            $productDetails = $result->fetch_assoc();

                            echo '<tr>';
                            echo '<td>';
                            $countryFlagPath = 'Flags/' . $productDetails['Country'] . '.jpg';
                            if (file_exists($countryFlagPath)) {
                                echo '<img class="flag-icon" src="' . $countryFlagPath . '" alt="' . $productDetails['Country'] . '" style="margin-left: 5px;">';
                            }
                            echo $productDetails['Name'];
                            echo '</td>';
                            echo '<td style="padding-left: 25px;">' . (isset($item['Availability']) ? $item['Availability'] : 0) . '</td>';
                            echo '<td>' . $productDetails['Condition'] . '</td>';
                            echo '<td>' . number_format($productDetails['Price'], 2) . '$</td>';
                            $totalItemPrice = (isset($item['Availability']) ? $productDetails['Price'] * $item['Availability'] : 0);
                            echo '<td>' . number_format($totalItemPrice, 2) . '$</td>';
                            echo '<td>' . $productDetails['Production_year'] . '</td>'; // Production Year
                            echo '<td><a href="Cart.php?remove_from_cart=' . $item['ID'] . '"><img src="Icons/Remove.jpg" alt="Remove" width="20" height="20" style="margin-left: 15px;"></a></td>';
                            echo '</tr>';

                            $totalPrice += $totalItemPrice;
                        }

                        echo '
                        <tr>
                        <td><b>Total Price:</b></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>$' . number_format($totalPrice, 2) . '</td>
                        <td></td>
                        <td></td>
                    </tr>';
                    echo '       
                    <tr>
                        <td colspan="6"></td>
                        <td>
                            <form action="confirm_purchase.php" method="post">
                                <input type="submit" class="btn btn-success" value="Confirm Purchase">
                            </form>
                        </td>
                    </tr>
                    ';
                    } else {
                        echo '<tr><td colspan="7">No items in the cart</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<style>
    .card-img-top {
      height: 300px; 
      object-fit: cover;
    }
    .flag-icon {
      height: 20px;
      margin-right: 5px;
    }
    .custom-card {
      background-color: #979797;
      border-radius: 10px;
    }
    .card-title {
      font-weight: bold;
      font-size: 20px;
      color: #333;
    }
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
