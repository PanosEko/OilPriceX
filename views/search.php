<?php
require_once '../model/DbClient.php'; // include the Db_Client class
$db_client = new Db_Client();
// get the fuel offers and prefectures from the database
$fuels = ($db_client)->getFuel();
$prefectures = ($db_client)->getPrefectures();
?>

    <!--This is the header of the website. It is included in all the pages of the website.-->
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <!-- The title of the webpage -->
        <title>OilPriceX Login</title>
        <! -- favicon -->
        <link rel="icon" type="image/x-icon" href="../assets/images/oilfavicon.png">
        <!-- Link to Bootstrap css file -->
        <link rel="stylesheet" href="../css/bootstrap.min.css">
        <!-- Link to custom css file -->
        <link rel="stylesheet" href="../css/custom.css">
        <!-- Import the 'Ubuntu' font from Google Fonts library with weight of 300 and display swap-->
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Ubuntu:wght@300&display=swap');
        </style>
    </head>


<body class="d-flex flex-column min-vh-100">
<header>
    <!-- Navigation bar with Bootstrap styling -->
    <nav class="navbar navbar-expand navbar-dark bg-primary">
        <div class="container-fluid">
            <!-- Brand logo -->
            <a class="navbar-brand" href="../views/index.php">
                <img src="../assets/images/logo.png" alt="logo">
            </a>
            <div class="collapse navbar-collapse">
                <!-- Navbar links for main pages -->
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item "> <!-- Makes the current pages link active -->
                        <a class="nav-link <?php if (basename($_SERVER['PHP_SELF']) == 'index.php')
                        { echo 'active'; } ?>"
                           href="../views/index.php"><b>Αρχική</b></a>
                    <li class="nav-item ">
                        <a class="nav-link <?php if (basename($_SERVER['PHP_SELF']) == 'search.php')
                        { echo 'active'; } ?>"
                           href="../views/search.php"><b>Αναζήτηση</b></a>
                    </li>
                    <?php // if the user is logged in show the offer entry button
                    session_start();
                    if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item">
                            <a class="nav-link <?php if (basename($_SERVER['PHP_SELF']) == 'offer_entry.php')
                            { echo 'active'; } ?>"
                               href="../views/offer_entry.php"><b>Καταχώρηση</b></a>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link <?php if (basename($_SERVER['PHP_SELF']) == 'announcements.php')
                        { echo 'active'; } ?>"
                           href="../views/announcements.php"><b>Ανακοινώσεις</b></a>
                    </li>
                </ul>
            </div>
            <?php  // if the user is logged in show the logout button, else show the login button
            if (isset($_SESSION['user_id'])): ?>
                <form method="POST" action="../controllers/logout.php">
                    <button type="submit" class="btn btn-secondary float-right">Log Out</button>
                </form>
            <?php else: ?>
                <a type="button" href="../views/login_page.php" class="btn btn-secondary float-right">Log In</a>
            <?php endif; ?>
        </div>
    </nav>
</header>



<main>
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-2 d-flex flex-column" id="sidebar"">
                    <form id="search_form" method="POST" action="../controllers/search_offers.php"
                          onSubmit="return validateSearchForm()" class="container">

                        <div class="row">
                            <div class="col-auto mb-4"></div>
                            <b>Νομός:</b>
                            <div class="col-auto mb-4" >
                                    <select name="selected_prefecture">
                                        <option selected value=0></option>
                                        <?php foreach ($prefectures as $prefecture): ?>
                                            <option value=<?php echo $prefecture['id']; ?>>
                                                <?php echo $prefecture['name']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                            </div>
                            <i><b>Είδος καυσίμου:</b></i>
                            <div class="col-auto mb-4" >
                                <label>
                                    <select name="selected_fuel">
                                        <option selected value=0></option> <?php foreach ($fuels as $fuel): ?>
                                            <option value=<?php echo $fuel['id']; ?>>
                                                <?php echo $fuel['type']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </label>
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-primary">Αναζήτηση</button>
                            </div>
                        </div>
                    </form>
            </div>
            <!-- Main Content -->
            <div class="col-lg-9">
                <!-- container with table to display the results -->
                <div class="container p-3">
                    <?php // if there are offer results stored in the session, display them
                    $counter = 0; // counter for the table rows
                    if (isset($_SESSION['offers'])):?>
                    <div class="card">
                        <div class="card-body">
                            <div class="table-scroll">
                                <table class="table table-striped ">
                                    <thead>
                                    <tr class="table-dark">
                                        <th scope="col">A/A</th>
                                        <th scope="col">Επωνυμία</th>
                                        <th scope="col">Διεύθυνση</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Τύπος Καυσίμου</th>
                                        <th scope="col">Τιμή</th>
                                        <th scope="col">Ημερομηνία Λήξης</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($_SESSION['offers'] as $item): ?>
                                        <?php $counter++; ?>
                                        <tr>
                                            <td> <?php echo $counter; ?></td>
                                            <td><?php echo $item['company_name']; ?></td>
                                            <!-- link to google maps with the address as a query -->
                                            <td><a href="https://www.google.com/maps/search/?api=1&query=<?php echo urlencode($item['address']); ?>"
                                                   target="_blank"><?php echo $item['address']; ?></a></td>
                                            <td><?php echo $item['email']; ?></td>
                                            <td><?php echo $item['type']; ?></td>
                                            <td><?php echo $item['price']; ?></td>
                                            <td><?php echo $item['end_date']; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <?php unset($_SESSION['offers']); ?>
                                    <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <script src="../js/search_form_validation.js"></script>
</main>

<?php include('../includes/footer.php'); // include footer ?>