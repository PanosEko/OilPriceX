<?php
/*
 * This file is called when the user wants to search for offers.
 * It gets the fuel and prefecture ids from the form and calls the getOffersByFuelAndPrefecture function from the Db_Client class.
 * The offers that are found are stored in the session and the user is redirected to the search page
 * where the offers are displayed.
 */

$fuel_id =  $_POST['selected_fuel'];
$prefecture_id = $_POST['selected_prefecture'];

require_once '../model/DbClient.php';
$db_client = new Db_Client();
$offers = ($db_client)->getOffersByFuelAndPrefecture($fuel_id, $prefecture_id);
session_start();

$_SESSION['offers'] = $offers;
header("Location:  ../views/search.php");
