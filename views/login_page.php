<?php include('../includes/header.php'); // include the header?>

<main>
    <div class="py-5">
        <h1 class="text-center">Είσοδος Χρήστη</h1>
        <!-- login form that sends the data to the login controller -->
        <form id="login_form" method="POST" action="../controllers/login.php" onSubmit="return validateLoginForm()"
              class="container w-75 px-5 p-3">
            <div class="d-flex flex-row justify-content-center mb-3">
                <div class="col-4">
                    <label for="username" class="col-form-label">Όνομα χρήστη:</label>
                </div>
                <div class="col-5">
                    <input type="text" class="form-control" id="username" name="username">
                </div>
            </div>
            <div class="d-flex flex-row justify-content-center mb-3">
                <div class="col-4">
                    <label for="user_pass" class="col-form-label">Κωδικός:</label>
                </div>
                <div class="col-5">
                    <input type="password" class="form-control" id="user_pass" name="user_pass">
                    <div id="password_error_message" style="color: red; display: none;">Ο κωδικός θα πρέπει να αποτελείται
                        από τουλάχιστον 8 χαρακτήρες και να περιέχει τουλάχιστον έναν αριθμό, ένα κεφαλαίο κι ένα πεζό
                        γράμμα.
                    </div>
                </div>
            </div>
            <div class="d-flex flex-row justify-content-center mb-3 p-5">
                <div class="col-4">
                    <p style="color:#2c81ba;">*όλα τα πεδία της φόρμας είναι υποχρεωτικά</p>
                </div>
                <div class="col-5">
                    <button type="submit" class="btn btn-primary">Είσοδος</button>
                </div>
            </div>
        </form>
        <div style="display:flex; justify-content:center;">
            <!-- link to the registration page -->
            <a href="registration.php">Εγγραφή νέας επιχείρησης</a>
        </div>
    </div>
    <!-- include the form validation script -->
    <script src="../js/login_form_validation.js"></script>
</main>

<?php include('../includes/footer.php'); // include the footer?>

