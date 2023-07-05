/*
 * This file contains the javascript code that validates the offer form and displays/hides the error messages
 */

// Event listener that runs after html has finished loading
document.addEventListener('DOMContentLoaded', function() {
    const dateInputElement = document.querySelector('#end_date');
    const dateFormatErrorMessage = document.querySelector('#date_format_error_message');
    const dateCurrentErrorMessage = document.querySelector('#date_current_error_message');

    // Event listener that runs when the user leaves the date input field
    dateInputElement.addEventListener('blur', function(event) {
        const inputValue = event.target.value;
        const dateRegex = /^(\d{2})\/(\d{2})\/(\d{4})$/; // regex for 9 digits
        // If the date is not in the correct format, display the error message
        if (!dateRegex.test(inputValue)) {
            dateFormatErrorMessage.style.display = 'block';
        } else {
            dateFormatErrorMessage.style.display = 'none';
        }
        const today = new Date();
        today.setHours(0,0,0,0);
        const date_parts = inputValue.split("/");
        const date = new Date(date_parts[2], date_parts[1] - 1, date_parts[0]);
        // If the date is in the past, display the error message
        if (date.getTime() < today.getTime()) {
            dateCurrentErrorMessage.style.display = 'block';
        }
        else {
            dateCurrentErrorMessage.style.display = 'none';
        }

    });

    const priceInputElement = document.querySelector('#price');
    const priceErrorMessage = document.querySelector('#price_error_message');
    // Event listener that runs when the user leaves the price input field
    priceInputElement.addEventListener('blur', function(event) {
        const inputValue = event.target.value;
        const priceRegex = /^\d+\.\d{2}$/; // regex for 9 digits
        // If the price is not in the correct format, display the error message
        if (!priceRegex.test(inputValue)) {
            priceErrorMessage.style.display = 'block';
        } else {
            priceErrorMessage.style.display = 'none';
        }
    });
});

/*
 * This function validates the offer form when the user clicks the submit button
 */
function validateOfferForm() {

    const price = document.getElementById("price").value;
    const end_date = document.getElementById("end_date").value;
    const fuelType = document.getElementById("fuel_type").value;
    let inputError = false;
    // Check if all the inputs are filled
    let inputs = document.getElementsByClassName("form-control");
    for (let i = 0; i < inputs.length; i++) {
        if (inputs[i].value === "") {
            inputError = true;
        }
    }
    // Check if the fuel type is selected
    if(fuelType === "") {
        inputError = true;
    }
    // If any of the inputs are empty, display an error message and return false
    if (inputError) {
        alert("Παρακαλώ συμπληρώστε όλα τα πεδία.");
        return false;
    }

    const today = new Date();
    today.setHours(0,0,0,0);
    const date_parts = end_date.split("/");
    const date = new Date(date_parts[2], date_parts[1] - 1, date_parts[0]);
    // If the date is in the past, display the error message
    if (date.getTime() < today.getTime()) {
        inputError = true;
    }

    const dateRegex = /^(\d{2})\/(\d{2})\/(\d{4})$/; // regex for 9 digits
    // If the date is not in the correct format, display the error message
    if (!dateRegex.test(end_date)) {
        inputError =  true;
    }
    const priceRegex = /^\d+\.\d{2}$/; // regex for 9 digits
    // If the price is not in the correct format, display the error message
    if (!priceRegex.test(price)) {
        inputError = true;
    }
    if (inputError) {
        return false;
    } else {
        return true; // continue with form submission
    }
}