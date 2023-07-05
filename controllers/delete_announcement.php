<?php
/*
 * This file is called when the user wants to delete an announcement. It gets the id of the announcement to be
 * deleted from the form and calls the deleteAnnouncement function from the Db_Client class.
 * If the announcement is deleted successfully, the user is redirected to the announcements page with a success message.
 * Otherwise, the user is redirected to the announcements page with an error message.
 */
$id = $_POST['id']; // get the id of the announcement to be deleted from the form
require_once '../model/DbClient.php';
$db_client = new Db_Client();
$result =$db_client->deleteAnnouncement($id);
session_start();
if ($result=="success") {
    $_SESSION['message'] = "Η ανακοίνωση διαγράφηκε με επιτυχία.";
} else {
    $_SESSION['message'] = "Η ανακοίνωση δεν διαγράφηκε. Παρακαλώ προσπαθήστε ξανά.";
}
header("Location:  ../views/announcements.php");