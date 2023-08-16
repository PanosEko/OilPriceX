<?php include('../includes/header.php'); // include the header?>
<!--     include the form validation script -->
<script src="../js/login_form_validation.js"></script>

<main>
    <div class="wrapper">
        <form class="login-form" id="login_form" method="POST" action="../controllers/login.php" onSubmit="return validateLoginForm()">
            <h2 class="login-form-heading">Είσοδος Χρήστη</h2>
            <input type="text" class="form-control" id="username" name="username" placeholder="Όνομα Χρήστη" autofocus="" />
            <input type="password" class="form-control" id="user_pass" name="user_pass" placeholder="Κωδικός" />
            <div id="password_error_message" style="color: red; display: none;">Ο κωδικός θα πρέπει να αποτελείται
                από τουλάχιστον 8 χαρακτήρες και να περιέχει τουλάχιστον έναν αριθμό, ένα κεφαλαίο κι ένα πεζό
                γράμμα.
            </div>
            <button class="btn btn-lg btn-primary btn-block" type="submit">Σύνδεση</button>
        </form>
    </div>
    <div style="display:flex; justify-content:center;">
        <!-- link to the registration page -->
        <a href="registration.php">Εγγραφή νέου χρήστη</a>
    </div>
</main>

<?php include('../includes/footer.php'); // include the footer?>

