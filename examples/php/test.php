<?php

// Include the AvaTaxClient library
include_once __DIR__.'/lib/AvaTaxClient.php';

// Create a new client
$client = new Avalara\AvaTaxClient('phpTestApp', '1.0', 'localhost', 'sandbox');
$client->withSecurity('username', 'password');

// Fetch companies
$companies = $client->queryCompanies(null, null, 10, null, null);
echo json_encode($companies, JSON_PRETTY_PRINT);

// Construct a new transaction
$tb = new Avalara\TransactionBuilder($client, 'DEFAULT', Avalara\DocumentType::C_SALESINVOICE, 'ABC');
$t = $tb->withAddress('ShipFrom', '123 Main Street', null, null, 'Irvine', 'CA', '92615', 'US')
    ->withAddress('ShipTo', '100 Ravine Lane', null, null, 'Bainbridge Island', 'WA', '98110', 'US')
    ->withLine(100.0, 1, "P0000000")
    ->withLine(1234.56, 1, "P0000000")
    ->withExemptLine(50.0, "NT")
    ->withLine(2000.0, 1, "P0000000")
    ->withLineAddress(Avalara\TransactionAddressType::C_SHIPFROM, "123 Main Street", null, null, "Irvine", "CA", "92615", "US")
    ->withLineAddress(Avalara\TransactionAddressType::C_SHIPTO, "1500 Broadway", null, null, "New York", "NY", "10019", "US")
    ->withLine(50.0, 1, "FR010000")
    ->create();
echo json_encode($t, JSON_PRETTY_PRINT);

?>
