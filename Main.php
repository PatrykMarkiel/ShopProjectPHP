<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Main Page</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
  </style>
</head>
<body style="overflow-y: auto; overflow-x: hidden;">

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

<!-- Main Content -->
<div class="container mt-5">
  <div class="row">
    <div class="col-md-8">
      <div class="row mt-4">
        <?php
        include "conn.php";

        $search = $_POST['search'] ?? '';
        $sort = $_POST['sort'] ?? '';
        $country = $_POST['country'] ?? '';

        $filter = [];
        if (!empty($search)) {
            $filter[] = "Name LIKE '%$search%'";
        }
        if (!empty($country)) {
            $filter[] = "Country = '$country'";
        }

        $sql = "SELECT * FROM Products";
        if (!empty($filter)) {
            $sql .= " WHERE " . implode(' AND ', $filter);
        }
        if (!empty($sort)) {
            $sql .= " ORDER BY Price $sort";
        }

        try {
            $result = $mysqli->query($sql);

            while ($product = $result->fetch_assoc()) {
                echo '<div class="col-md-6 mb-4">';
                echo '<div class="card custom-card">';
                echo '<img src="Photos/' . $product['Product_Link'] . '" class="card-img-top img-fluid" alt="' . $product['Name'] . '">';
                echo '<div class="card-body">';
                echo '<b class="card-title">' . $product['Name'] . ' </b>';

                $countryFlagPath = 'Flags/' . $product['Country'] . '.jpg'; 
                if (file_exists($countryFlagPath)) {
                    echo '<img class="flag-icon" src="' . $countryFlagPath . '" alt="' . $product['Country'] . '">';
                }

                echo '<p class="card-text">Price: ' . $product['Price'] . '$</p>';
                echo '<input type="hidden" name="productId" value="' . $product['ID'] . '">';
                echo '<a href="Cart.php?id=' . $product['ID'] . '"><button type="button" class="btn btn-primary mx-1">Add to Cart</button></a>';
                echo '<button class="btn btn-success mx-1">Buy Now</button>';

                echo '</div></div></div>';
            }
        } catch (Exception $e) {
            echo "Query failed: " . $e->getMessage();
        }
        ?>
      </div>
    </div>

  <!-- Sidebar -->
  <div class="col-md-4 position-fixed top-5 end-0">
    <div style="background-color: #f8f9fa; padding: 20px; height: 420px; border: 2px solid #000; border-radius: 10px; margin:40px; overflow-y: auto;">
      <h2 class="mb-4">Sidebar</h2>
      <form action="Main.php" method="post">
        <div class="mb-3">
          <label for="search" class="form-label">Search</label>
          <input type="text" class="form-control" name="search" id="search" placeholder="Search for a vehicle...">
        </div>
        <div class="mb-3">
          <label for="sort" class="form-label">Sort by</label>
          <select class="form-select" name="sort" id="sort">
            <option value="asc">Lowest Price</option>
            <option value="desc">Highest Price</option>
          </select>
        </div>
        <div class="mb-3">
          <label for="country" class="form-label">Filter by Country</label>
          <select class="form-select" name="country" id="country">
            <option value="">Select a country</option>
            <?php
            include "conn.php";

            try {
              $result = $mysqli->query("SELECT DISTINCT Country FROM Products");

              while ($row = $result->fetch_assoc()) {
                echo '<option value="' . $row['Country'] . '">' . $row['Country'] . '</option>';
              }
            } catch (Exception $e) {
              echo "Query failed: " . $e->getMessage();
            }
            ?>
          </select>
        </div>
        <button type="submit" class="btn btn-primary">Filter</button>
      </form>
    </div>
  </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
