<?php
    header('Access-Control-Allow-Private-Network: true');
    header('Access-Control-Allow-Origin: localhost');
    header('Content-Type: application/json; charset=utf-8');

    $json = file_get_contents('php://input');
    $data_json = json_decode($json, true);


    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (strpos($_SERVER["CONTENT_TYPE"], "application/x-www-form-urlencoded") !== false) {
            $data = $_POST;
        } elseif (strpos($_SERVER["CONTENT_TYPE"], "application/json") !== false) {
            $data = $data_json;
        } else {
            die("Request failed. Expected HTTP POST 'CONTENT_TYPE' to be 'application/x-www-form-urlencoded' OR 'application/json'.");
        }
    } elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $data = $_GET;
    } else {
        die("Request failed. Expected 'REQUEST_METHOD' to be 'POST' or 'GET'.");
    }

        if (!isset($data['email'])) {
            echo(json_encode(array('error' => true, 'msg'=>'missing email')));
            exit;
        }
        if (!isset($data['password'])) {
            echo(json_encode(array('error' => true, 'msg'=>'missing password')));
            exit;
        }
        if (!isset($data['region'])) {
            echo(json_encode(array('error' => true, 'msg'=>'missing region')));
            exit;
        }
        if (!isset($data['deviceId'])) {
            echo(json_encode(array('error' => true, 'msg'=>'missing deviceId')));
            exit;
        }
        if (!isset($data['action'])) {
            echo(json_encode(array('error' => true, 'msg'=>'missing action')));
            exit;
        }

        $email = $data['email'];
        $password = $data['password'];
        $region = $data['region'];
        $deviceId = $data['deviceId'];
        $action = $data['action'];

        // TODO: AUTHENTICATION from php session (after user has logged in)

        $ch = curl_init('http://localhost:9000/');
        # Setup request to send json via POST.
        $payload = json_encode(array( "email"=> $email,
      "password"=>$password,
      "region"=>$region,
      "deviceId"=>$deviceId,
      "action"=>$action
    ));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        # Return response instead of printing.
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        # Send request.
        $result = curl_exec($ch);
        curl_close($ch);
        // print_r($result);
        echo json_encode($result);
        // echo json_encode(array('test'=>'teeets'));
        exit;
