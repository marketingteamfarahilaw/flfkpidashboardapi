<?php

function call_activecampaign_v1_api($params = [])
{
    $apiKey = 'fd204dde85cc59c96adf03da3a8d2f56309ad04095cd21a8da7cb51bd629afb63bbc6a89';
    $apiUrl = 'https://farahilaw54527.api-us1.com/admin/api.php';

    $params['api_key'] = $apiKey;
    $params['api_output'] = 'json';

    $url = $apiUrl . '?' . http_build_query($params);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}