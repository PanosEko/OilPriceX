<?php
include '../controllers/generate_xml.php'; // generate the XML file
libxml_use_internal_errors(true); // enable libxml errors
// create a new DOMDocument object
$xml = new DOMDocument();
$xml->load('../xml/companyFuelOffers.xml'); // load the XML file
$errors = libxml_get_errors(); // check for validation errors
if ($xml->validate()) { // if the XML file is valid against the DTD
    $xpath = new DOMXPath($xml); // create a new DOMXPath object
    // select all distinct fuel types
    $fuel_types = $xpath->query('/company_fuel_offers/fuel_offers/offer/fuel_type[not(. = preceding::fuel_type)]');

    echo "<h2> Ελάχιστες και μέγιστες τιμές προσφορών ανά τύπο καυσίμου: </h2>";
    // create a table with headers
    echo "<table><tr><th>Τύπος καυσίμων</th><th>Χαμηλότερη τιμή</th><th>Υψηλότερη τιμή</th></tr>";

    // iterate over each fuel type and find the minimum and maximum prices
    foreach ($fuel_types as $fuel_type) {
        $fuel_type_str = $fuel_type->nodeValue;
        $min_price = PHP_INT_MAX;
        $max_price = -1;
        $offers = $xpath->query("/company_fuel_offers/fuel_offers/offer[fuel_type='$fuel_type_str']");

        foreach ($offers as $offer) {
            $price = (float)$offer->getElementsByTagName('price')->item(0)->nodeValue;
            if ($price < $min_price) {
                $min_price = $price;
            }
            if ($price > $max_price) {
                $max_price = $price;
            }
        }

        // add a row for each fuel type and its minimum and maximum prices
        echo "<tr><td>$fuel_type_str</td><td>$min_price</td><td>$max_price</td></tr>";
    }
    echo "</table>";

    // display xml using xlt
    $xsl = new DOMDocument();
    $xsl->load('../xml/companyFuelOffers.xsl');
    $proc = new XSLTProcessor();
    $proc->importStyleSheet($xsl);
    echo $proc->transformToXML($xml);

    // download the xml file
    echo '<script src="../js/download_file.js"></script>'; // Include the JavaScript file
    echo '<script>
        // Call the downloadFile JS function to download the xml file
        downloadFile("../xml/companyFuelOffers.xml");
      </script>';

} else { // display the errors if the XML file is not valid
    echo '<div style="text-align: center;">
    <h2>Παρουσιάστηκε σφάλμα κατά την δημιουργία του εγγράφου.</h2>
    <h3>Παρακαλώ επικοινωνήστε με τον διαχειριστή.</h3>
    <h3>Αναλυτική περιγραφή σφάλματος:</h3>
    <ul style="list-style-type: none;">';
    foreach (libxml_get_errors() as $error) {
        echo '<li>' . $error->message . '</li>';
    }
    echo '</ul></div>';
}


