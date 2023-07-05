<?php
/*
 * This file is called when the user submits a new offer.
 * It gets the offer data from the form and calls the newOffer method of the Db_Client class.
 * If the offer is successfully added to the database, the user is redirected to the offer_entry page with a success message.
 * Otherwise, the user is redirected to the offer_entry page with an error message.
 */
$fuel_type = $_POST['fuel_type'];
$price = $_POST['price'];
$end_date = $_POST['end_date'];

require_once '../model/DbClient.php';
$db_client = new Db_Client();
session_start();

$result =$db_client->newOffer($_SESSION['user_id'], $fuel_type, $end_date, $price);

if ($result=="success") {
    $_SESSION['message'] = "Η προσφορά υποβλήθηκε με επιτυχία.";
} else {
    $_SESSION['message'] = "Η προσφορά δεν υποβλήθηκε. Παρακαλώ προσπαθήστε ξανά.";
}
header("Location:  ../views/offer_entry.php");