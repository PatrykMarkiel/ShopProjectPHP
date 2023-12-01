<?php
include "Conn.php";

// Sprawdź czy zostało przekazane ID produktu w parametrze URL
if (isset($_GET['id'])) {
    // Pobierz ID produktu z parametru URL
    $productId = $_GET['id'];

    // Zapytanie SQL do pobrania informacji o produkcie na podstawie ID
    $sql = "SELECT * FROM Products WHERE ID = $productId";

    // Wykonaj zapytanie do bazy danych
    $result = $mysqli->query($sql);

    // Sprawdź czy zapytanie zwróciło wyniki
    if ($result->num_rows > 0) {
        // Pobierz dane o produkcie jako tablicę asocjacyjną
        $productDetails = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Product Details</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
/* css */
    .product-container {
      margin-top: 50px;
    }
    .product-image {
      width:1200px
      object-fit: cover;
    }
  </style>
</head>
<body>
<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
  <div class="container">
    <a class="navbar-brand" href="#">
      <img src="Icons/icon.png" alt="Link 2" class="d-inline-block align-text-top me-2" id="brand" style="max-height: 25px;">
      <b>Warland</b>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item"><a class="nav-link" href="#"></a></li>
        <li class="nav-item"><a class="nav-link" href="#"><b>cart</b></a></li>
        <li class="nav-item"><a class="nav-link" href="#"><b>profile</b></a></li>
        <li class="nav-item"><a class="nav-link" href="#"><b>settings</b></a></li>
      </ul>
    </div>
  </div>
</nav>
<!-- Content -->
<div class="container my-5">
    <div class="container product-container my-5">
        <div class="row">
            <div class="col-md-6">
                <img src="Photos/<?php echo $productDetails['Product_Link']; ?>" class="img-fluid rounded product-image" alt="<?php echo $productDetails['Name']; ?>">
            </div>
            <div class="col-md-6">
                <h1 class="mb-4"><?php echo $productDetails['Name']; ?></h1>
                <p class="lead mb-4">Description: <?php echo $productDetails['Description']; ?></p>
                <p class="lead mb-4">Price: $<?php echo $productDetails['Price']; ?></p>
                <div class="mb-4">
                    <h5>Country:</h5>
                    <?php
                    $countryFlagPath = 'Flags/' . $productDetails['Country'] . '.jpg';
                    if (file_exists($countryFlagPath)) {
                        echo '<img class="img-fluid" src="' . $countryFlagPath . '" alt="' . $productDetails['Country'] . '">';
                    }
                    ?>
                </div>
                <div class="mb-4">
                    <a href="Cart.php?id=<?php echo $productDetails['ID']; ?>" class="btn btn-primary me-3">Add to Cart</a>
                    <button class="btn btn-success">Buy Now</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
    } else {
        echo "Product not found.";
    }

    // Zamknij połączenie z bazą danych
    $mysqli->close();
} else {
    echo 'Error: No product identifier provided.';
    exit;
}
?>
