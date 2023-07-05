<?php
/*
 * This file is called when the user wants to submit an announcement. It gets the title and the content of the announcement
 * from the form and calls the submitAnnouncement function from the Db_Client class.
 * If the announcement is submitted successfully, the user is redirected to the announcements page with a success message.
 * Otherwise, the user is redirected to the announcements page with an error message.
 */
$title = $_POST['title'];
$content = $_POST['article_body'];

require_once '../model/DbClient.php';
$db_client = new Db_Client();
session_start();

$result =$db_client->submitAnnouncement($title, $content);

if ($result=="success") {
    $_SESSION['message'] = "Η ανακοίνωση υποβλήθηκε.";
} else {
    $_SESSION['message'] = "Η ανακοίνωση δεν υποβλήθηκε. Παρακαλώ προσπαθήστε ξανά.";
}
header("Location:  ../views/announcements.php");