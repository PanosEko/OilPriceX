<?php include('../includes/header.php');
$current_date = date("Y-m-d"); // get the current date
require_once '../model/DbClient.php'; // include the Db_Client class
$db_client = new Db_Client(); // Initialize the Db_Client
// get the fuel offers and announcements from the database
$offers = ($db_client)->getFuelOffersByDate($current_date);
$announcements = ($db_client)->getAnnouncements();
?>

<main>
    <!-- flex container to center the content horizontally -->
    <div class="d-flex justify-content-center p-5">
        <div style="width: 80vw;">
            <h1 class="text-center">Ημερήσια Σύνοψη τιμών</h1>
            <h3 class="text-center"><?php echo date('d-m-Y'); ?> </h3>
            <div class="container">
                <div class="card">
                    <div class="card-body">
                        <table class="table  bordered">
                            <thead class="thead-light">
                            <tr>
                                <th scope="col">Τύπος Καυσίμου</th>
                                <th scope="col">Μέγιστη Τιμή</th>
                                <th scope="col">Ελάχιστη Τιμή</th>
                                <th scope="col">Μέση Τιμή</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($offers as $item): ?>
                                <tr>
                                    <td><?php echo $item['type']; ?></td>
                                    <td><?php echo $item['max_price']; ?></td>
                                    <td><?php echo $item['min_price']; ?></td>
                                    <td><?php echo $item['avg_price']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-center p-3"
    ">
    <div style="width: 80vw;">
        <h1 class="text-center">Τελευταίες Ανακοινώσεις</h1>
        <ul class="hide-dots-center-text">
            <?php // display the 3 most recent announcements
            foreach (array_slice($announcements, 0, 3)  as $announcement): ?>
            <li class="li-border"><h3><?php echo $announcement['formatted_date']; ?></h3>
                <h2>
                    <a href="../views/announcements.php#<?php echo $announcement['id']; ?>"><?php echo $announcement['title']; ?></a>
                </h2>
                <?php endforeach; ?>
        </ul>
    </div>
</main>

<?php include('../includes/footer.php'); ?>
