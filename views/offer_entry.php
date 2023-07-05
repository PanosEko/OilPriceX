<?php include('../includes/header.php'); // include the header
require_once '../model/DbClient.php'; // include the Db_Client class
$db_client = new Db_Client();
// get the fuel offers and announcements from the database
$fuel = ($db_client)->getFuel();
$company_name = ($db_client)->getCompanyName($_SESSION['user_id']);
?>

<script src="../js/offer_form_validation.js"></script>

<main>
    <!-- button that downloads the offers from the database and displays them in a table -->
    <div class="text-center">
        <a href="../views/display_offers.php" target="_blank" class="btn btn-primary">Λήψη καταχωρημένων προσφορών</a>
    </div>
    <h1 class="text-center p-5">Καταχώρηση Προσφοράς</h1>
    <!-- offer form that sends the data to the submit_offer.php controller after validation with javascript -->
    <form id="new_offer_form" method="POST" action="../controllers/submit_offer.php"
          onSubmit="return validateOfferForm()" class="container w-75 px-5 p-3">
        <div class="d-flex flex-row justify-content-center mb-3">
            <div class="col-4">
                <label for="Name" class="col-form-label">Επωνυμία Επιχείρησης:</label>
            </div>
            <div class="col-5">
                <input type="text" class="form-control" id="Name" value="<?php echo $company_name ?>"
                       readonly>
            </div>
        </div>
        <div class="d-flex flex-row justify-content-center mb-3">
            <div class="col-4">
                <label for="fuel_type" class="col-form-label">Είδος καυσίμου:</label>
            </div>
            <div class="col-5">
                <select id="fuel_type" name="fuel_type" class="form-select" aria-label="Default select example">
                    <option selected></option>
                    <?php foreach ($fuel as $item): ?>
                        <option value=<?php echo $item['id']; ?>>
                            <?php echo $item['type']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="d-flex flex-row justify-content-center mb-3">
            <div class="col-4">
                <label for="price" class="col-form-label">Τιμή:</label>
            </div>
            <div class="col-5">
                <input type="text" class="form-control" id="price" name="price">
                <div id="price_error_message" style="color: red; display: none;">Πρέπει να εισάγετε αριθμό με δύο δεκαδικά ψηφία (π.χ. 1.89) </div>
            </div>
        </div>
        <div class="d-flex flex-row justify-content-center mb-3">
            <div class="col-4">
                <label for="end_date" class="col-form-label">Ημερομηνία λήξης προσφοράς:</label>
            </div>
            <div class="col-5">
                <input type="text" class="form-control" id="end_date" name="end_date">
                <div id="date_format_error_message" style="color: red; display: none;">
                    Η ημερομηνία πρέπει είναι της μορφής ΗΗ/ΜΜ/ΕΕΕΕ (π.χ. 03/11/2023)</div>
                <div id="date_current_error_message" style="color: red; display: none;">
                    Η ημερομηνία που εισάγατε έχει παρέλθει.</div>
            </div>
        </div>
        <div class="d-flex flex-row justify-content-center mb-3 p-5">
            <div class="col-4">
                <p style="color:#2c81ba;">*όλα τα πεδία της φόρμας είναι υποχρεωτικά</p>
            </div>
            <div class="col-5">
                <button type="submit" class="btn btn-primary">Καταχώρηση</button>
            </div>
        </div>
    </form>
</main>

<?php include('../includes/footer.php'); // include footer ?>

