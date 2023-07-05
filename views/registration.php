<?php include('../includes/header.php'); // include the header
require_once '../model/DbClient.php'; // include the database client
$db_client = new Db_Client();
// get the municipalities and prefectures from the database
$municipalities = ($db_client)->getMunicipalities();
$prefectures = ($db_client)->getPrefectures();
// reset the form values if the user has already filled out the form and submitted it unsuccessfully
$company_name_value = isset($_SESSION['form_values']['name']) ? $_SESSION['form_values']['name'] : '';
$tax_id_value = isset($_SESSION['form_values']['vat']) ? $_SESSION['form_values']['vat'] : '';
$address_value = isset($_SESSION['form_values']['address']) ? $_SESSION['form_values']['address'] : '';
$email_value = isset($_SESSION['form_values']['email']) ? $_SESSION['form_values']['email'] : '';
$username_value = isset($_SESSION['form_values']['username']) ? $_SESSION['form_values']['username'] : '';
$user_pass_value = isset($_SESSION['form_values']['user_pass']) ? $_SESSION['form_values']['user_pass'] : '';
$municipality = isset($_SESSION['form_values']['municipality']) ? $_SESSION['form_values']['municipality'] : '';
$prefecture = isset($_SESSION['form_values']['prefecture']) ? $_SESSION['form_values']['prefecture'] : '';
if (isset($_SESSION['form_values'])) {
    // clear the form values from the session
    unset($_SESSION['form_values']);
}
?>

<main>
    <h1 class="text-center p-4">Εγγραφή Επιχείρησης</h1>
    <!-- user registration form that calls the submit_user.php
     controller after validation with javascript function -->
    <form id="new_user_form" method="POST" action="../controllers/submit_user.php"
          onSubmit="return validateRegistrationForm()" class="container w-75 px-5 p-3">
        <div class="d-flex flex-row justify-content-center mb-3">
            <div class="col-4">
                <label for="name" class="col-form-label">Επωνυμία Επιχείρησης:</label>
            </div>
            <div class="col-5">
                <input type="text" class="form-control" id="name" name="name"
                       value="<?php echo $company_name_value; ?>">
            </div>
        </div>
        <div class="d-flex flex-row justify-content-center mb-3">
            <div class="col-4"><label for="vat" class="col-form-label">ΑΦΜ:</label></div>
            <div class="col-5">
                <input type="text" class="form-control" id="vat" name="vat"
                       value="<?php echo $tax_id_value; ?>">
                <div id="vat_error_message" style="color: crimson; display: none;">Το ΑΦΜ πρέπει να αποτελείτε απο εννιά αριθμούς.</div>
            </div>
        </div>
        <div class="d-flex flex-row justify-content-center mb-3">
            <div class="col-4">
                <label for="address" class="col-form-label">Διεύθυνση:</label>
            </div>
            <div class="col-5">
                <input type="text" class="form-control" id="address" name="address"
                       value="<?php echo $address_value; ?>">
            </div>
        </div>
        <div class="d-flex flex-row justify-content-center mb-3">
            <div class="col-4">
                <label for="municipality" class="col-form-label">Δήμος:</label>
            </div>
            <div class="col-5">
                <select id="municipality" class="form-select"
                        aria-label="Default select example" name="municipality">
                    <option selected></option>
                    <?php // populate the municipality dropdown with the municipalities
                    foreach ($municipalities as $item): ?>
                        <option value="<?php echo $item['id']; ?>"
                            <?php if ($item['id'] == $municipality) echo 'selected'; ?>>
                            <?php echo $item['name']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="d-flex flex-row justify-content-center mb-3">
            <div class="col-4">
                <label for="prefecture" class="col-form-label">Νομός:</label>
            </div>
            <div class="col-5">
                <select id="prefecture" class="form-select"
                        aria-label="Default select example" name="prefecture">
                    <option selected></option>
                    <?php // populate the prefecture dropdown with the prefectures
                    foreach ($prefectures as $item): ?>
                        <option value="<?php echo $item['id']; ?>"
                            <?php if ($item['id'] == $prefecture) echo 'selected'; ?>>
                            <?php echo $item['name']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="d-flex flex-row justify-content-center mb-3">
            <div class="col-4">
                <label for="email" class="col-form-label">email:</label>
            </div>
            <div class="col-5">
                <input type="email" class="form-control" id="email" name="email" value="<?php echo $email_value; ?>">
                <div id="email_error_message" style="color: crimson; display: none;">Παρακαλώ πληκτρολογήστε έγκυρη
                    διεύθυνση ηλεκτρονικού ταχυδρομείου.
                </div>
            </div>
        </div>
        <div class="d-flex flex-row justify-content-center mb-3">
            <div class="col-4">
                <label for="username" class="col-form-label">Όνομα χρήστη:</label>
            </div>
            <div class="col-5">
                <input type="text" class="form-control" id="username" name="username"
                       value="<?php echo $username_value; ?>">
            </div>
        </div>
        <div class="d-flex flex-row justify-content-center mb-3">
            <div class="col-4">
                <label for="user_pass" class="col-form-label">Κωδικός:</label>
            </div>
            <div class="col-5">
                <input type="password" class="form-control" id="user_pass" name="user_pass"
                       value="<?php echo $user_pass_value; ?>">
                <div id="password_error_message" style="color: red; display: none;">Ο κωδικός θα πρέπει να αποτελείται
                    από τουλάχιστον 8 χαρακτήρες και να περιέχει τουλάχιστον έναν αριθμό, ένα κεφαλαίο κι ένα πεζό
                    γράμμα.
                </div>
            </div>
        </div>
        <div class="d-flex flex-row justify-content-center mb-3">
            <div class="col-4">
                <label for="password-verification" class="col-form-label">Επιβεβαίωση κωδικού:</label>
            </div>
            <div class="col-5">
                <input type="password" class="form-control" id="password-verification" name="password-verification">
                <div id="password-verification-error" style="color: crimson; display: none;">Οι κωδικοί δεν
                    ταιριάζουν.
                </div>
            </div>
        </div>
        <div class="d-flex flex-row justify-content-center mb-3 p-5">
            <div class="col-4">
                <p style="color:#2c81ba;">*όλα τα πεδία της φόρμας είναι υποχρεωτικά</p>
            </div>
            <div class="col-5">
                <button type="submit" class="btn btn-primary">Εγγραφή</button>
            </div>
        </div>
    </form>
    <!-- include the form validation error messages script -->
    <script src="../js/registration_form_validation.js"></script>
</main>

<?php include('../includes/footer.php'); ?>
