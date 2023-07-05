<?php
/*
 * This file is called when the user submits the registration form.
 * It gets the user data from the form and calls the submitUser method of the Db_Client class.
 * If the user is successfully added to the database, the user is redirected to the login page
 *  with a success message. Otherwise, the user is redirected to the registration page with an error message.
 */

$company_name =  $_POST['name'];
$tax_id = $_POST['vat'];
$address =  $_POST['address'];
$municipality = $_POST['municipality'];
$prefecture =  $_POST['prefecture'];
$role = 1; // value 1 represents simple user
$username =  $_POST['username'];
$email = $_POST['email'];
$password = $_POST['user_pass'];

require_once '../model/DbClient.php';
$db_client = new Db_Client();
$result =$db_client->submitUser($company_name, $tax_id, $address, $municipality,
    $prefecture,$email, $role, $username, $password);

session_start();
if ($result=="success") {
    $_SESSION['message'] = "Η εγγραφή σας ολοκληρώθηκε με επιτυχία.
    Μπορείτε να συνδεθείτε με τα στοιχεία που δηλώσατε.";
    header("Location: ../views/login_page.php");
} else {
    $_SESSION['form_values'] = $_POST; // store form values in session
    if ($result == "tax_id_exists") {
        $_SESSION['message'] = "Η εγγραφή σας δεν ολοκληρώθηκε. 
        Βρέθηκε χρήστης με ίδιο Α.Φ.Μ. Επικοινωνήστε μαζί μας στο oilpricex@gmail.com.";
    } else if ($result == "username_exists") {
        $_SESSION['message'] = "Η εγγραφή σας δεν ολοκληρώθηκε.
         Το όνομα χρήστη χρησιμοποιείται ήδη. Παρακαλώ επιλέξτε άλλο όνομα χρήστη.";
    } else {
        $_SESSION['message'] = "Η εγγραφή σας δεν ολοκληρώθηκε. Παρακαλώ προσπαθήστε ξανά.";
    }
    header("Location: ../views/registration.php"); // redirect to registration page
}