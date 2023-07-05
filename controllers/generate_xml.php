<?php
require_once '../model/DbClient.php'; // include the Db_Client class
$db_client = new Db_Client();
session_start();
// get the fuel offers and announcements from the database
$offers = ($db_client)->getOffersByUser($_SESSION['user_id']);
$user_data = ($db_client)->getUserDataById($_SESSION['user_id']);
// create a new DOMDocument object
$xml = new DOMDocument();
$xml->encoding = 'UTF-8'; // set the encoding so that Greek characters are displayed correctly

// create the DOCTYPE declaration
$implementation = new DOMImplementation();
$doctype = $implementation->createDocumentType('company_fuel_offers', '', '../xml/companyFuelOffers.dtd');
$xml->appendChild($doctype);

// add root element and other elements to the XML file
$root = $xml->createElement('company_fuel_offers');
$xml->appendChild($root);

// add the company info
$company = $xml->createElement('company_info');
$root->appendChild($company);
// add the company name
$company_name = $xml->createElement('name', $user_data['company_name']);
$company->appendChild($company_name);
// add the company tax id
$company_tax_id = $xml->createElement('tax_id', $user_data['tax_id']);
$company->appendChild($company_tax_id);
// add the company address
$company_address = $xml->createElement('address');
$company->appendChild($company_address);
// add the street of the address
$street = $xml->createElement('street', $user_data['address']);
$company_address->appendChild($street);
// add the municipality
$municipality = $xml->createElement('municipality', $user_data['municipality_name']);
$company_address->appendChild($municipality);
// add the prefecture
$prefecture = $xml->createElement('prefecture', $user_data['prefecture_name']);
$company_address->appendChild($prefecture);

// add the fuel offers
$fuel_offers = $xml->createElement('fuel_offers');
$root->appendChild($fuel_offers);
foreach ($offers as $offer) {
    // add the fuel offer
    $offer_element = $xml->createElement('offer');
    $fuel_offers->appendChild($offer_element);
    // add the fuel type
    $fuel_type = $xml->createElement('fuel_type', $offer['type']);
    $offer_element->appendChild($fuel_type);
    // add the offer end date
    $end_date = $xml->createElement('end_date', $offer['end_date']);
    $offer_element->appendChild($end_date);
    // add the fuel price
    $price = $xml->createElement('price', $offer['price']);
    $offer_element->appendChild($price);
    // add the fuel offer status (True for active or False for inactive)
    $current_timestamp = time();
    $end_date_timestamp = strtotime($offer['end_date']);
    $active = $xml->createElement('active', $current_timestamp <= $end_date_timestamp ? 'True' : 'False');
    $offer_element->appendChild($active);

}
$xml->save('../xml/companyFuelOffers.xml'); // save the XML file