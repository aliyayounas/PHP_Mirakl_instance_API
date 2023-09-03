<?php
$miraklBaseUrl = 'Mirakl_insatnce_URL'; // Replace with your Mirakl instance URL
$apiKey = 'YOUR_API_KEY'; // Replace with your API key

function makeApiRequest($method, $url, $data = []) {
    global $miraklBaseUrl, $apiKey;

    $headers = [
        'Authorization: Bearer ' . $apiKey,
        'Content-Type: application/json',
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $miraklBaseUrl . $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    }

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($response === false) {
        throw new Exception('Curl error: ' . curl_error($ch));
    }

    $responseData = json_decode($response, true);

    if ($httpCode < 200 || $httpCode >= 300) {
        if (isset($responseData['message'])) {
            throw new Exception('API error: ' . $responseData['message']);
        } else {
            throw new Exception('API error: Unexpected response.');
        }
    }

    return $responseData;
}

try {
    // Prepare your product data
    $productData = [
        "products" => [
            [
                "id" => "iphone14pro",
                "gtins" => [
                    ["value" => "0194252034279"]
                ],
                "titles" => [
                    ["value" => "iPhone 14 pro", "locale" => "en_US"]
                ],
                "images" => [
                    ["url" => "https://picsum.photos/200"]
                ],
                "standard_prices" => [
                    ["price" => ["amount" => 1329, "currency" => "EUR"]]
                ],
                "discount_prices" => [
                    ["price" => ["amount" => 1249.99, "currency" => "EUR"]]
                ],
                "quantities" => [
                    ["available_quantity" => 65]
                ]
            ],
            [
                "id" => "playStation5",
                "gtins" => [
                    ["value" => "711719521112"]
                ],
                "titles" => [
                    ["value" => "Playstation 5", "locale" => "en_US"]
                ],
                "images" => [
                    ["url" => "https://picsum.photos/200"]
                ],
                "standard_prices" => [
                    ["price" => ["amount" => 499, "currency" => "EUR"]]
                ],
                "discount_prices" => [
                    ["price" => ["amount" => 349.99, "currency" => "EUR"]]
                ],
                "quantities" => [
                    ["available_quantity" => 12]
                ]
            ]
        ]
    ];

    // Upsert products
    $upsertUrl = 'URL'; // Adjust the endpoint as per Mirakl API documentation
    $upsertResponse = makeApiRequest('POST', $upsertUrl, $productData);

    echo "Product upsert successful.";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
