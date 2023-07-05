/*
 * This file contains the javascript code that validates the registration
 * form and displays/hides the error messages
 */
// Event listener that runs after html has finished loading
document.addEventListener('DOMContentLoaded', function() {
    const vatInputElement = document.querySelector('#vat');
    const vatErrorMessage = document.querySelector('#vat_error_message');
    // Event listener that runs when the user leaves the vat input field
    vatInputElement.addEventListener('blur', function(event) {
        const inputValue = event.target.value;
        const vatRegex = /^\d{9}$/; // regex for 9 digits
        // If the vat is not in the correct format, display the error message
        if (!vatRegex.test(inputValue)) {
            vatErrorMessage.style.display = 'block';
        } else {
            vatErrorMessage.style.display = 'none';
        }
    });

    const emailnputElement = document.querySelector('#email');
    const emailErrorMessage = document.querySelector('#email_error_message');
    // Event listener that runs when the user leaves the email input field
    emailnputElement.addEventListener('blur', function(event) {
        const inputValue = event.target.value;
        const emailRegex = /\S+@\S+\.\S+/; // regex for email
        // If the email is not in the correct format, display the error message
        if (!emailRegex.test(inputValue)) {
            emailErrorMessage.style.display = 'block';
        } else {
            emailErrorMessage.style.display = 'none';
        }
    });

    const passwordlnputElement = document.querySelector('#user_pass');
    const passwordErrorMessage = document.querySelector('#password_error_message');
    // Event listener that runs when the user leaves the password input field
    passwordlnputElement.addEventListener('blur', function(event) {
        const inputValue = event.target.value;
        // regex for password that must have at least 8 characters, at least one uppercase letter at least one
        // lowercase letter, at least one number and no special characters. Greek letters are also allowed.
        const passwordRegex = /^(?=.*[a-zα-ω])(?=.*[A-ZΑ-Ω])(?=.*\d)[a-zA-Zα-ωΑ-Ω\d]{8,}$/;
        // If the password is not in the correct format, display the error message
        if (!passwordRegex.test(inputValue)) {
            passwordErrorMessage.style.display = 'block';
        } else {
            passwordErrorMessage.style.display = 'none';
        }
    });

    const passwordVerificationlnputElement = document.querySelector('#password-verification');
    const passwordVerificationErrorMessage = document.querySelector('#password-verification-error');
    // Event listener that runs when the user leaves the password verification input field
    passwordVerificationlnputElement.addEventListener('blur', function(event) {
        const passwordVerificationInputValue = event.target.value;
        var passwordInput = document.getElementById("user_pass");
        var passwordInputValue = passwordInput.value;
        // If the password verification is not the same as the password, display the error message
        if (passwordInputValue === passwordVerificationInputValue) {
            passwordVerificationErrorMessage.style.display = 'none';
        } else {
            passwordVerificationErrorMessage.style.display = 'block';
        }
    });
});

/*
 * This function validates the registration form data and is called when the user
 * submits the registration form.
 * @returns {boolean} true if the form is valid, false otherwise.
 */
function validateRegistrationForm() {
    const form = document.getElementById("new_user_form");

    const vatInputValue = document.getElementById("vat").value;
    const emailnputValue = document.getElementById("email").value;
    const passwordlnputValue= document.getElementById("user_pass").value;
    const passwordVerificationInputValue = document.getElementById("password-verification").value;
    const prefectureInputValue = document.getElementById("prefecture").value;
    const municipalityInputValue = document.getElementById("municipality").value;
    let inputError = false;
    // if any of the input fields is empty set inputError to true
    let inputs = document.getElementsByClassName("form-control");
    for (let i = 0; i < inputs.length; i++) {
        if (inputs[i].value === "") {
            inputError = true;
        }
    }
    // if any of the select inputs is empty set inputError to true
    if(prefectureInputValue === "" || municipalityInputValue === "") {
        inputError = true;
    }
    // display alert if any of the input fields is empty and return false
    if (inputError) {
        alert("Παρακαλώ συμπληρώστε όλα τα πεδία.");
        return false;}
    // chek if the vat, email, password and password verification are in the correct format
    // (same as in the event listeners)
    if (!vatRegex.test(vatInputValue)) {
        inputError =  true;}
    if (!emailRegex.test(emailnputValue)) {
        inputError =  true;}
    if (!passwordRegex.test(passwordlnputValue)) {
        inputError =  true;}
    if (passwordlnputValue !== passwordVerificationInputValue) {
        inputError =  true;}
    if (inputError) {
        return false;
    } else {
        // continue with form submission
        return true;
    }
}