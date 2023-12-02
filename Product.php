
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Product Details</title>
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

if (isset($_GET['ID'])) {
    $productID = $_GET['ID'];

    // Pobierz informacje o produkcie na podstawie ID
    $sql = "SELECT * FROM Products WHERE ID = $productID";
    $result = $mysqli->query($sql);

    if ($result->num_rows > 0) {
        $productDetails = $result->fetch_assoc();
?>
<div class="container my-5 rounded bg-secondary" style=" padding: 20px;">
    <div class="row align-items-center">
        <h1 class="mb-4"><b><?php echo $productDetails['Name']; ?></b></h1>
        <div class="col-md-6">
            <div class="position-relative">
                <img src="Photos/<?php echo $productDetails['Product_Link']; ?>" class="product-img img-fluid rounded" alt="<?php echo $productDetails['Name']; ?>">
            </div>
        </div>
        <div class="col-md-6 ">
            <div class="product-description border bg-white p-2 rounded" style="border-width: 5px; border-radius: 0;">
                <h3 class="fw-bold"><?php echo $productDetails['Name']; ?></h3>
                <span class="lead mb-4">Description: <?php echo $productDetails['Description']; ?></span>
                <br>
                <span class="lead mb-4 ">Price: $<?php echo intval($productDetails['Price']); ?></span>
                <br>
                <span class="lead mb-4 ">Year of production:<?php echo intval($productDetails['Production_year']); ?></span>
                <br>
                <span class="lead mb-4 ">Condition:<?php echo $productDetails['Condition']; ?></span>
                <br>
                <span class="lead mb-4 ">Company:<?php echo $productDetails['Company']; ?></span>

                <div class="mb-4">
                    <span class="lead mb-4 ">Country: <?php echo $productDetails['Country']?>
                    <?php
                    $countryFlagPath = 'Flags/' . $productDetails['Country'] . '.jpg';
                    if (file_exists($countryFlagPath)) {
                        echo '<img class="img-fluid" src="' . $countryFlagPath . '" alt="' . $productDetails['Country'] . '" height="150" width="50">';
                    }
                    ?>
                </span>
                <div class="mb-4">
                    <form action="Cart.php" method="GET">
                        <input type="hidden" name="add_to_cart" value="<?php echo $productDetails['ID']; ?>">
                        <input type="number" name="Availability" class="form-control" min="1" max="<?php echo $productDetails['Availability']; ?>" value="1" required style="width: 80px;">
                        <br>
                        <button type="submit" class="btn btn-primary">Add to Cart</button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

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

    <?php
        } else {
            echo "Product not found.";
        }

        $mysqli->close();
    } else {
        echo 'Error: No product identifier provided.';
        exit;
    }
    ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>