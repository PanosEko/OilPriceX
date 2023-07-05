<?php include('../includes/header.php'); // include the header
require_once '../model/DbClient.php';  // include the database client
$db_client = new Db_Client();
$announcements = ($db_client)->getAnnouncements(); // get all the announcements from the database
?>

<main>
    <script src="../js/announcement_form_management.js"></script>

    <div class="container mt-4">
        <!-- message div to display messages to the user -->
        <div id="message" class="alert alert-success rounded text-center" role="alert"
             style="display:none;">
            <?php // if there is a message in the session, display it
            if (isset($_SESSION['message'])) {
                // display the message div
                echo '<script>document.getElementById("message").style.display = "block";</script>';
                // replace newlines with html line breaks so the message is displayed properly and display the message
                echo '<script>document.getElementById("message").innerHTML = "' . str_replace(array("\r", "\n"),
                        array('\r', '\n'), $_SESSION['message']) . '";</script>';
                unset($_SESSION['message']); // clear the message from the session
            }
            ?>
        </div>
    </div>
    <?php // if the user is an admin, display the new announcement button
    if (isset($_SESSION['role'])){
        if ($_SESSION['role']==0){
            echo '<div style="text-align: center; margin-top: 10px;">
                  <button class="btn btn-primary" onclick="displayPopupForm()">Νέα ανακοίνωση</button>
                  </div>';
        }
    }
    ?>
    <!-- popup that contains a form to create a new announcement -->
    <div class="form-popup" id="form_popup" style="display:none;" >
        <form method="POST" action="../controllers/submit_announcement.php"
              onSubmit="return validateAnnouncementForm()" class="form-container">
            <button class="close" onclick="hidePopupForm(event)">&times;</button>
            <label for="title"><b>Τίτλος</b></label>
            <input type="text" placeholder="Εισάγετε τίτλο" name="title" id="title">
            <label for="article_body"><b>Κείμενο</b></label>
            <textarea style="height: 300px;" placeholder="Εισάγετε κείμενο" name="article_body" id="article_body" ></textarea>
            <div style="text-align: center; margin-top: 10px;">
                <button type="submit" class="btn btn-primary">Υποβολή</button>
            </div>
        </form>
    </div>

    <h1 class="text-center p-4">Ανακοινώσεις</h1>
    <!--div of class d-flex to center scrollable menu-->
    <div class="d-flex justify-content-center">
        <div class="scrollable-menu">
            <ul>
                <?php // display all the announcements
                foreach ($announcements as $announcement): ?>
                    <h3><?php echo $announcement['formatted_date']; ?></h3>
                    <?php // if the user is an admin, display the delete button
                    if (isset($_SESSION['role'])){
                        if ($_SESSION['role']==0){
                            echo '<form method="POST" action="../controllers/delete_announcement.php">
                                  <input type="hidden" name="id" value="'.$announcement['id'].'">
                                  <button type="submit" class="btn btn-danger">Διαγραφή</button>
                                  </form>';
                        }
                    }
                    ?>
                    <h2 id=<?php echo $announcement['id']; ?>><?php echo $announcement['title']; ?></h2>
                    <p class="p-border"><?php echo $announcement['content']; ?></p>

                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</main>

<?php include('../includes/footer.php'); // include footer ?>

