// This file contains the validation function for the login form.
function validateLoginForm() {
    const nameElement = document.querySelector('#username');
    const userPassElement = document.querySelector('#user_pass');
    console.log(nameElement.value);
    console.log(userPassElement.value)
    if(nameElement.value === "" || userPassElement.value === ""){
        alert("Παρακαλώ συμπληρώστε όλα τα πεδία.");
        return false;
    }
    return true;
}