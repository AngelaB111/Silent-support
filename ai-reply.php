<?php
header("Content-Type: application/json");

$env = parse_ini_file('ai.env');
$apiKey = $env['API_KEY'] ?? '';

$content = $_POST['content'] ?? '';
if (!$content) {
    echo json_encode(["success" => false, "error" => "No message provided."]);
    exit;
}

$validModel = 'gemini-2.5-flash';

$url = "https://generativelanguage.googleapis.com/v1/models/$validModel:generateContent?key=" . $apiKey;


$data = [
    "contents" => [
        [
            "role" => "user",
            "parts" => [
                ["text" => "You are a supportive, non-diagnostic mental health therapist. Your reply must be gentle, empathetic, and validating. Keep your response concise (normal length, not too long). Include actionable suggestion if needed or coping strategy, and you may include brief suggestion of next step to take ."],
                ["text" => "User: $content"]
            ]
        ]
    ]
];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

$response = curl_exec($ch);
$error = curl_error($ch);
curl_close($ch);

file_put_contents("debug_ai.txt", "CURL ERROR:\n$error\n\nRESPONSE:\n$response");

$result = json_decode($response, true);

if (isset($result["candidates"][0]["content"]["parts"][0]["text"])) {
    echo json_encode([
        "success" => true,
        "reply" => $result["candidates"][0]["content"]["parts"][0]["text"]
    ]);
} else {
    $apiError = $result['error']['message'] ?? "Unknown error.";

    echo json_encode([
        "success" => false,
        "error" => "AI returned no text. API Message: " . $apiError,
        "raw" => $result
    ]);
}
?>