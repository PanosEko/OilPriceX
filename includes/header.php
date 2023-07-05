
<!--This is the header of the website. It is included in all the pages of the website.-->
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <!-- The title of the webpage -->
    <title>OilPriceX Login</title>
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

<!-- Displayed when a message is set in the session -->
<div class="container mt-4">
    <div id="message" class="alert alert-primary rounded text-center" role="alert"
         style="display:none;">
        <?php // if a message is set in the session display it
        session_start();
        if (isset($_SESSION['message'])) {
            // display the message div
            echo '<script>document.getElementById("message").style.display = "block";</script>';
            // replace newlines with html line breaks so the message is displayed properly and display the message
            echo '<script>document.getElementById("message").innerHTML = "' . str_replace(array("\r", "\n"),
                    array('\r', '\n'), $_SESSION['message']) . '";</script>';
            unset($_SESSION['message']); // clear the message from the session
        }
        ?>
    </div>
</div>