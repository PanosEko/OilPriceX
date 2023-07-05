/*
 * This file contains the javascript code that displays/hides the announcement form and validates the form inputs
 */
function displayPopupForm() {
    document.getElementById("form_popup").style.display = "block";
}

function hidePopupForm() {
    event.preventDefault(); // prevent the default form submission behavior
    document.getElementById("form_popup").style.display = "none";
}

// Validates that the form inputs fields are filled
function validateAnnouncementForm() {
    console.log("Validating form inputs...");
    let inputs = document.querySelectorAll("#form_popup input, #form_popup textarea");
    for (let i = 0; i < inputs.length; i++) {
        console.log(inputs[i].value);
        if (inputs[i].value === "") {
            alert("Παρακαλώ συμπληρώστε όλα τα πεδία.");
            return false;
        }
    }
    return true;
}