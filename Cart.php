<?php
include "Conn.php";

if (isset($_COOKIE['cart']) && is_array(json_decode($_COOKIE['cart'], true))) {
    $cartItems = json_decode($_COOKIE['cart'], true);
} else {
    $cartItems = [];
}

if (isset($_GET['add_to_cart']) && isset($_GET['quantity'])) {
    $productID = $_GET['add_to_cart'];
    $quantity = $_GET['quantity'];

    // Sprawdzenie czy ilość jest liczbą dodatnią
    if (!is_numeric($quantity) || $quantity <= 0) {
        echo "Invalid quantity.";
        exit;
    }

    $productFound = false;

    foreach ($cartItems as &$item) {
        if (isset($item['ID']) && $item['ID'] === $productID) {
            $item['Availability'] += $quantity;
            $productFound = true;
            break;
        }
    }

    if (!$productFound) {
        $cartItems[] = array('ID' => $productID, 'Availability' => $quantity);
    }

    setcookie('cart', json_encode($cartItems), time() + 3600, "/");
}

if (isset($_GET['remove_from_cart'])) {
    $productID = $_GET['remove_from_cart'];

    foreach ($cartItems as $key => $item) {
        if ($item['ID'] === $productID) {
            unset($cartItems[$key]);
            break;
        }
    }

    $cartItems = array_values($cartItems);

    setcookie('cart', json_encode($cartItems), time() + 3600, "/");
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
    <h1>Cart</h1>
    <div class="row">
        <div class="col">
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Total Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!empty($cartItems)) {
                        $totalPrice = 0;
                        // Pętla wyświetlająca produkty w koszyku
                        foreach ($cartItems as $item) {
                            // Pobranie szczegółów produktu z bazy danych na podstawie $item['ID']
                            $stmt = $mysqli->prepare("SELECT Name, Price FROM Products WHERE ID = ?");
                            $stmt->bind_param("i", $item['ID']);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            $productDetails = $result->fetch_assoc();

                            echo '<tr>';
                            echo '<td>' . $productDetails['Name'] . '</td>';
                            echo '<td>' . (isset($item['Availability']) ? $item['Availability'] : 0) . '</td>';
                            echo '<td>$' . number_format($productDetails['Price'], 2) . '</td>';
                            $totalItemPrice = (isset($item['Availability']) ? $productDetails['Price'] * $item['Availability'] : 0);
                            echo '<td>$' . number_format($totalItemPrice, 2) . '</td>';
                            echo '</tr>';

                            $totalPrice += $totalItemPrice;
                        }
                        echo '
                        <tr>
                            <td><b>Total Price:</b></td>
                            <td></td>
                            <td></td>
                            <td>$' . number_format($totalPrice, 2) . '</td>
                        </tr>';
                    } else {
                        echo '<tr><td colspan="4">No items in the cart</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
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
</body>
</html>
