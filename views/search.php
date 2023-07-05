<?php include('../includes/header.php'); // include the header
require_once '../model/DbClient.php'; // include the Db_Client class
$db_client = new Db_Client();
// get the fuel offers and prefectures from the database
$fuels = ($db_client)->getFuel();
$prefectures = ($db_client)->getPrefectures();
?>

<main>
    <h1 class="text-center p-2">Φίλτρα</h1>
    <div class="d-flex justify-content-center">
        <form id="search_form" method="POST" action="../controllers/search_offers.php"
              onSubmit="return validateSearchForm()" class="container w-75 px-5 p-3">
            <div class="row gx-4">
                <div class="col-auto">
                    <label>
                        <i><b>Νομός:</b></i>
                        <select name="selected_prefecture">
                            <option selected value=0></option>
                            <?php foreach ($prefectures as $prefecture): ?>
                                <option value=<?php echo $prefecture['id']; ?>>
                                    <?php echo $prefecture['name']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                </div>
                <div class="col-auto">
                    <i><b>Είδος καυσίμου:</b></i>
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
    <!-- container with table to display the results -->
    <div class="container p-3">
        <h1 class="text-center ">Αποτελέσματα</h1>
        <?php // if there are offer results stored in the session, display them
        $counter = 0; // counter for the table rows
        if (isset($_SESSION['offers'])):?>
        <div class="table-scroll">
            <table class="table table-striped table-bordered">
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
    <script src="../js/search_form_validation.js"></script>
</main>

<?php include('../includes/footer.php'); // include footer ?>