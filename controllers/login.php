
<?php
/*
 * This file is called when the user wants to log in. It gets the username and password from
 * the form and calls the login function from the Db_Client class. If the login is successful,
 * the user is redirected to the index page with a success message.
 * Otherwise, the user is redirected to the login page with an error message.
 */
$username =  $_POST['username'];
$password = $_POST['user_pass'];

require_once '../model/DbClient.php';
$db_client = new Db_Client();
$result =$db_client->login($username, $password);

session_start();

if ($result['result']=="success") {
    // store user id, role and company name in session
    $_SESSION['user_id'] = $result['user_id'];
    $_SESSION['role'] = $result['role'];
    $_SESSION['company_name'] = $result['company_name'];

    $_SESSION['message'] = "Η σύνδεση σας ολοκληρώθηκε με επιτυχία.
     Πλέον μπορείτε να καταχωρήσετε τιμές καυσίμων επιλέγοντας τον σύνδεσμο Καταχώρηση.";
    header("Location:  ../index.php");
} else {
    $_SESSION['message'] = "Λανθασμένο όνομα χρήστη ή κωδικός πρόσβασης. Παρακαλώ προσπαθήστε 
    ξανά ή επικοινωνήστε μαζί μας για την ανάκτηση των κωδικών σας στο email: oilpricex@gmai.com";
    header("Location: ../views/login_page.php"); // redirect to registration page
}