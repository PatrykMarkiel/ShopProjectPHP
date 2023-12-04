
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
<body style="overflow-y: auto; overflow-x: hidden;">

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

<!-- Sidebar -->
<div class="col-md-4 position-fixed top-5 end-0">
  <div style="background-color: #f8f9fa; padding: 20px; border: 2px solid #000; border-radius: 10px; margin: 40px; overflow-y: auto;">
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
      <div class="mb-3">
        <label for="category" class="form-label">Filter by Category</label>
        <select class="form-select" name="category" id="category">
          <option value="">Select a category</option>
          <?php
          try {
            $result = $mysqli->query("SELECT DISTINCT Category FROM Products");

            while ($row = $result->fetch_assoc()) {
              echo '<option value="' . $row['Category'] . '">' . $row['Category'] . '</option>';
            }
          } catch (Exception $e) {
            echo "Query failed: " . $e->getMessage();
          }
          ?>
        </select>
      </div>
      <div class="mb-3">
        <label for="price_min" class="form-label">Minimum Price</label>
        <input type="text" class="form-control" name="price_min" id="price_min" placeholder="Enter minimum price">
      </div>
      <div class="mb-3">
        <label for="price_max" class="form-label">Maximum Price</label>
        <input type="text" class="form-control" name="price_max" id="price_max" placeholder="Enter maximum price">
      </div>
        <div class="mb-3">
        <label for="start_year" class="form-label">Start Year</label>
        <input type="number" class="form-control" name="start_year" id="start_year" placeholder="Enter start year">
      </div>
      <div class="mb-3">
        <label for="end_year" class="form-label">End Year</label>
        <input type="number" class="form-control" name="end_year" id="end_year" placeholder="Enter end year">
      </div>

      <!-- Sortowanie wg daty -->
      <div class="mb-3">
        <label for="sort_date" class="form-label">Sort by Date</label>
        <select class="form-select" name="sort_date" id="sort_date">
          <option value="asc">Oldest First</option>
          <option value="desc">Newest First</option>
        </select>
      </div>

      <button type="submit" class="btn btn-primary">Filter</button>
    </form>
  </div>
</div>


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
        $category = $_POST['category'] ?? '';
        $price_min = !empty($_POST['price_min']) ? (float)$_POST['price_min'] : null;
        $price_max = !empty($_POST['price_max']) ? (float)$_POST['price_max'] : null;
        $start_year = $_POST['start_year'] ?? '';
        $end_year = $_POST['end_year'] ?? '';
        $sort_date = $_POST['sort_date'] ?? '';

        $sql = "SELECT * FROM Products WHERE 1=1";

        if (!empty($search)) {
          $sql .= " AND Name LIKE '%$search%'";
        }
        if (!empty($country)) {
          $sql .= " AND Country = '$country'";
        }
        if (!empty($category)) {
          $sql .= " AND Category = '$category'";
        }
        if (!empty($price_min) && empty($price_max)) {
          $sql .= " AND Price >= $price_min";
        } elseif (empty($price_min) && !empty($price_max)) {
          $sql .= " AND Price <= $price_max";
        } elseif (!empty($price_min) && !empty($price_max)) {
          $sql .= " AND Price BETWEEN $price_min AND $price_max";
        }
        if (!empty($start_year) && !empty($end_year)) {
          $sql .= " AND Production_year BETWEEN $start_year AND $end_year";
        }

        if (!empty($sort) && $sort_date === 'asc') {
          $sql .= " ORDER BY Production_year ASC";
        } elseif (!empty($sort) && $sort_date === 'desc') {
          $sql .= " ORDER BY Production_year DESC";
        } else {
          if (!empty($sort)) {
            $sql .= " ORDER BY Price $sort";
          }
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
              echo '<img class="flag-icon" src="' . $countryFlagPath . '" alt="' . $product['Country'] . '"><br>';
            }

            echo '<b class="card-text">Price: ' . intval($product['Price']) . '$</b><br>';
            echo '<b class="card-text">Production year: ' . intval($product['Production_year']) . '</b><br>';
            echo '<input type="hidden" name="productId" value="' . $product['ID'] . '">';
            echo '<a href="Product.php?ID=' . $product['ID'] . '"><button type="button" class="btn btn-primary mx-1">Add to Cart</button></a>';
            echo '<button class="btn btn-success mx-1">Buy Now</button>';

            echo '</div></div></div>';
          }
        } catch (Exception $e) {
          echo "Query failed: " . $e->getMessage();
        }
        ?>
      </div>
    </div>
  </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
