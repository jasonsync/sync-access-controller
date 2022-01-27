<?php
    header('Access-Control-Allow-Private-Network: true');
    header('Access-Control-Allow-Origin: *');

    // TODO: AUTHENTICATION from php session (after user has logged in)

    $ch = curl_init('http://localhost:8000/');
    # Setup request to send json via POST.
    $payload = json_encode(array( "customer"=> 'jason' ));
    // curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    # Return response instead of printing.
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    # Send request.
    $result = curl_exec($ch);
    curl_close($ch);
    print_r($result);
