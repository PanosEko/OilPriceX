// Downloads a file from a given location
function downloadFile(fileLocation) {
    fetch(fileLocation) // Call the fetch function passing the locatin as a parameter
        .then(response => response.blob()) // Transform the data into blob
        .then(blob => {
            const link = document.createElement('a'); // Create a link element
            link.href = URL.createObjectURL(blob); // Set the href of the link to the Blob URL
            // Set the download attribute to the given file name
            link.download = fileLocation.substring(fileLocation.lastIndexOf('/') + 1);
            link.click(); // Invoke the click function on the link
            URL.revokeObjectURL(link.href); // Remove the Blob URL to free up memory
        })
        .catch(error => {
            console.error('Error:', error); // Catch any error
        });
}

