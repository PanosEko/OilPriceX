
// This function is called when the user clicks the submit button of the search form
function validateSearchForm() {
    let prefectureSelect = document.getElementById("search_form").elements.namedItem("selected_prefecture");
    let fuelSelect = document.getElementById("search_form").elements.namedItem("selected_fuel");
    console.log(prefectureSelect.selectedIndex);
    console.log(fuelSelect.selectedIndex);
    // If any of the select elements are not selected, display an error message and return false
    if (prefectureSelect.selectedIndex == 0 || fuelSelect.selectedIndex == 0) {
        alert("Παρακαλώ συμπληρώστε όλα τα πεδία.");
        return false;
    }
    // continue with form submission
    return true;
}