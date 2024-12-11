<?php
include('connect.php');

// Query
$pupAirportQuery = "SELECT * FROM flightlogs";
$filters = [];
$orderBy = "";

// Filters
if (isset($_GET['airline']) && $_GET['airline'] !== '') {
  $airline = $_GET['airline'];
  $filters[] = "airlineName = '$airline'";
}

if (isset($_GET['aircraft']) && $_GET['aircraft'] !== '') {
  $aircraft = $_GET['aircraft'];
  $filters[] = "aircraftType = '$aircraft'";
}

if (isset($_GET['departureCode']) && $_GET['departureCode'] !== '') {
  $departureCode = $_GET['departureCode'];
  $filters[] = "departureAirportCode = '$departureCode'";
}

if (isset($_GET['arrivalCode']) && $_GET['arrivalCode'] !== '') {
  $arrivalCode = $_GET['arrivalCode'];
  $filters[] = "arrivalAirportCode = '$arrivalCode'";
}

if (isset($_GET['creditType']) && $_GET['creditType'] !== '') {
  $creditType = $_GET['creditType'];
  $filters[] = "creditCardType = '$creditType'";
}

if (!empty($filters)) {
  $pupAirportQuery .= " WHERE " . implode(" AND ", $filters);
}

// Order
$order = "ASC"; // Default order

if (isset($_GET['order']) && $_GET['order'] !== '') {
  $order = strtoupper($_GET['order']) === 'DESC' ? 'DESC' : 'ASC';
}

// Sorting
if (isset($_GET['sort']) && $_GET['sort'] !== '') {
  $sortColumn = $_GET['sort'];
  $orderBy = " ORDER BY $sortColumn $order";
}

// Selected with Order By Asc
$pupAirportQuery .= $orderBy;
$pupAirportResult = executeQuery($pupAirportQuery);
$airlines = executeQuery("SELECT DISTINCT airlineName FROM flightlogs ORDER BY airlineName");
$aircrafts = executeQuery("SELECT DISTINCT aircraftType FROM flightlogs ORDER BY aircraftType");
$departureCodes = executeQuery("SELECT DISTINCT departureAirportCode FROM flightlogs ORDER BY departureAirportCode");
$arrivalCodes = executeQuery("SELECT DISTINCT arrivalAirportCode FROM flightlogs ORDER BY arrivalAirportCode");
$creditTypes = executeQuery("SELECT DISTINCT creditCardType FROM flightlogs ORDER BY creditCardType");
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Flight Logs | PUP Airport</title>
  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <!-- Icon -->
  <link rel="icon" href="img/planeIcon.png">
  <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="styles.css">
</head>

<body>
  <!-- Nav -->
  <nav id="navBar" class="navbar navbar-expand-lg shadow">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">
        <img src="img/adminFlightLogs.png" alt="Admin Flight Logs" style="height: 80px;">
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="nav-link" href="#home">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#filters">Filters</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#table">Table</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#footer">Footer</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Moving plane -->
  <div class="container-fluid" style="margin-top: 15vh; margin-bottom: 10vh; text-align: center;" id="home">
    <img src="img/pupPlane.png" alt="Flight Logs" class="animated-image">
  </div>

  <!-- Filter and Sort Form -->
  <div class="gradient-card text-center my-4">
    <h1 class="fw-bold" id="filters" style="font-family: 'Instrument Serif', serif; text-shadow: 2px 2px 4px gray">
      Filters</h1>

    <!-- Filters Form Label and Control -->
    <form method="GET" class="mb-4">
      <div class="row row-cols-1 row-cols-md-2 row-cols-sm-1 g-4">

        <div class="col">
          <label for="airline" class="form-label">Airline</label>
          <select name="airline" id="airline" class="form-control">
            <option value="">Any</option>
            <?php while ($airlineRow = mysqli_fetch_assoc($airlines)) { ?>
              <option value="<?php echo $airlineRow['airlineName']; ?>" <?php echo (isset($_GET['airline']) && $_GET['airline'] == $airlineRow['airlineName']) ? 'selected' : ''; ?>>
                <?php echo $airlineRow['airlineName']; ?>
              </option>
            <?php } ?>
          </select>
        </div>

        <div class="col">
          <label for="aircraft" class="form-label">Aircraft</label>
          <select name="aircraft" id="aircraft" class="form-control">
            <option value=""> Any</option>
            <?php while ($aircraftRow = mysqli_fetch_assoc($aircrafts)) { ?>
              <option value="<?php echo $aircraftRow['aircraftType']; ?>" <?php echo (isset($_GET['aircraft']) && $_GET['aircraft'] == $aircraftRow['aircraftType']) ? 'selected' : ''; ?>>
                <?php echo $aircraftRow['aircraftType']; ?>
              </option>
            <?php } ?>
          </select>
        </div>

        <div class="col">
          <label for="departureCode" class="form-label">Departure Airport</label>
          <select name="departureCode" id="departureCode" class="form-control">
            <option value="">Any</option>
            <?php while ($departureRow = mysqli_fetch_assoc($departureCodes)) { ?>
              <option value="<?php echo $departureRow['departureAirportCode']; ?>" <?php echo (isset($_GET['departureCode']) && $_GET['departureCode'] == $departureRow['departureAirportCode']) ? 'selected' : ''; ?>>
                <?php echo $departureRow['departureAirportCode']; ?>
              </option>
            <?php } ?>
          </select>
        </div>

        <div class="col">
          <label for="arrivalCode" class="form-label">Arrival Airport</label>
          <select name="arrivalCode" id="arrivalCode" class="form-control">
            <option value="">Any</option>
            <?php while ($arrivalRow = mysqli_fetch_assoc($arrivalCodes)) { ?>
              <option value="<?php echo $arrivalRow['arrivalAirportCode']; ?>" <?php echo (isset($_GET['arrivalCode']) && $_GET['arrivalCode'] == $arrivalRow['arrivalAirportCode']) ? 'selected' : ''; ?>>
                <?php echo $arrivalRow['arrivalAirportCode']; ?>
              </option>
            <?php } ?>
          </select>
        </div>

        <div class="col">
          <label for="creditType" class="form-label">Credit Card Type</label>
          <select name="creditType" id="creditType" class="form-control">
            <option value="">Any</option>
            <?php while ($creditcardRow = mysqli_fetch_assoc($creditTypes)) { ?>
              <option value="<?php echo $creditcardRow['creditCardType']; ?>" <?php echo (isset($_GET['creditType']) && $_GET['creditType'] == $creditcardRow['creditCardType']) ? 'selected' : ''; ?>>
                <?php echo $creditcardRow['creditCardType']; ?>
              </option>
            <?php } ?>
          </select>
        </div>

        <!-- Sorting Section -->
        <div class="col">
          <label for="sort" class="form-label">Sort By</label>
          <select name="sort" id="sort" class="form-control">
            <option value="">None</option>
            <option value="flightNumber" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'flightNumber') ? 'selected' : ''; ?>>Flight Number</option>
            <option value="departureDatetime" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'departureDatetime') ? 'selected' : ''; ?>>Departure Date & Time</option>
            <option value="arrivalDatetime" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'arrivalDatetime') ? 'selected' : ''; ?>>Arrival Date & Time</option>
            <option value="flightDurationMinutes" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'flightDurationMinutes') ? 'selected' : ''; ?>>Flight Duration</option>
            <option value="passengerCount" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'passengerCount') ? 'selected' : ''; ?>>Passenger Count</option>
            <option value="ticketPrice" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'ticketPrice') ? 'selected' : ''; ?>>Ticket Price</option>
            <option value="creditCardNumber" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'creditCardNumber') ? 'selected' : ''; ?>>Credit Card Number</option>
            <option value="pilotName" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'pilotName') ? 'selected' : ''; ?>>Pilot Name</option>
          </select>
        </div>

        <!-- Order By Section -->
        <div class="col">
          <label for="order" class="form-label">Order By</label>
          <select name="order" id="order" class="form-control">
            <option value="ASC" <?php if (isset($order) && $order == "ASC")
              echo "selected"; ?>>Ascending</option>
            <option value="DESC" <?php if (isset($order) && $order == "DESC")
              echo "selected"; ?>>Descending</option>
          </select>
        </div>
      </div>

      <!-- Refresh & Enter Section -->
      <div class="mt-3 text-center">
        <a href="index.php" class="btn btn-secondary">Refresh</a>
        <button type="submit" class="btn btn-primary">Enter</button>
      </div>
    </form>
  </div>


  <!-- Flight Table -->
  <div class="table-responsive" id="table">
    <table class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>Flight Number</th>
          <th>Departure Airport Code</th>
          <th>Arrival Airport Code</th>
          <th>Departure Date & Time</th>
          <th>Arrival Date & Time</th>
          <th>Flight Duration Minutes</th>
          <th>Airline Name</th>
          <th>Aircraft Type</th>
          <th>Passenger Count</th>
          <th>Ticket Price</th>
          <th>Credit Card Number</th>
          <th>Credit Card Type</th>
          <th>Pilot Name</th>
        </tr>
      </thead>
      <tbody>
        <?php
        if (mysqli_num_rows($pupAirportResult) > 0) {
          while ($row = mysqli_fetch_assoc($pupAirportResult)) { ?>
            <tr>
              <td><?php echo $row['flightNumber']; ?></td>
              <td><?php echo $row['departureAirportCode']; ?></td>
              <td><?php echo $row['arrivalAirportCode']; ?></td>
              <td><?php echo $row['departureDatetime']; ?></td>
              <td><?php echo $row['arrivalDatetime']; ?></td>
              <td><?php echo $row['flightDurationMinutes']; ?></td>
              <td><?php echo $row['airlineName']; ?></td>
              <td><?php echo $row['aircraftType']; ?></td>
              <td><?php echo $row['passengerCount']; ?></td>
              <td><?php echo $row['ticketPrice']; ?></td>
              <td><?php echo $row['creditCardNumber']; ?></td>
              <td><?php echo $row['creditCardType']; ?></td>
              <td><?php echo $row['pilotName']; ?></td>
            </tr>
          <?php }
        } else {
          // If no records are found
          echo "<tr><td colspan='13' class='text-center'>No records found.</td></tr>";
        }
        ?>
      </tbody>
    </table>
  </div>
  </div>

  <footer id="footer" class="container-fluid text-center py-4 shadow">
    <div class="container">
      <p class="text-gold mb-2">&copy; Admin Flight Logs | PUP Airport</p>
      <p class="text-gold mb-3">All rights reserved</p>
      <p class="text-white font-italic">
        "Flying High with Excellence"
      </p>
    </div>
  </footer>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
</body>

</html>