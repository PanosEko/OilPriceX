// This file contains the validation function for the login form.
function validateLoginForm() {
    const nameElement = document.querySelector('#username');
    const userPassElement = document.querySelector('#user_pass');
    if(nameElement.value === "" || userPassElement.value === ""){
        alert("Παρακαλώ συμπληρώστε όλα τα πεδία.");
        return false;
    }
    return true;
}