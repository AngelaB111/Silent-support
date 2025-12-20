<?php
header("Content-Type: application/json");
$env = parse_ini_file('ai.env');
$apiKey = $env['API_KEY'] ?? '';

$content = trim($_POST['content'] ?? '');
if ($content === '') {
    echo json_encode(["success" => false, "error" => "No message provided"]);
    exit;
}

$localFlag = 'no';
$crisisWords = ['kill myself', 'suicide', 'end my life', 'hurt myself', 'self harm'];
foreach ($crisisWords as $word) {
    if (stripos($content, $word) !== false) {
        $localFlag = 'yes';
        break;
    }
}

$model = "gemini-2.5-flash";
$url = "https://generativelanguage.googleapis.com/v1/models/$model:generateContent?key=$apiKey";

$instruction = "You are a mental health triage assistant. Analyze this user message: \"$content\".
1. Categorize it into EXACTLY ONE of these: 'Anxiety/Worry', 'Depression/Sadness', 'Grief/Loss', 'Relationship Issues', 'Stress/Burnout', or 'other'.
2. Flag 'yes' if there is any intent of self-harm, suicidal ideation, or severe crisis. Otherwise 'no'.

Return ONLY valid JSON: {\"category\": \"...\", \"flagged\": \"yes/no\"}";

$data = [
    "contents" => [["parts" => [["text" => $instruction]]]],
    "safetySettings" => [
        ["category" => "HARM_CATEGORY_DANGEROUS_CONTENT", "threshold" => "BLOCK_NONE"],
        ["category" => "HARM_CATEGORY_HARASSMENT", "threshold" => "BLOCK_NONE"]
    ]
];

$ch = curl_init($url);
curl_setopt_array($ch, [
    CURLOPT_POST => true,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => ["Content-Type: application/json"],
    CURLOPT_POSTFIELDS => json_encode($data)
]);
$response = curl_exec($ch);
curl_close($ch);

$result = json_decode($response, true);
$category = 'other';
$aiFlagged = 'no';

if (isset($result["candidates"][0]["content"]["parts"][0]["text"])) {
    $raw = trim(str_replace(['```json', '```'], '', $result["candidates"][0]["content"]["parts"][0]["text"]));
    $parsed = json_decode($raw, true);

    if (is_array($parsed)) {
        $allowed = ['Anxiety/Worry', 'Depression/Sadness', 'Grief/Loss', 'Relationship Issues', 'Stress/Burnout', 'other'];
        $category = in_array($parsed['category'], $allowed) ? $parsed['category'] : 'other';
        $aiFlagged = ($parsed['flagged'] ?? 'no') === 'yes' ? 'yes' : 'no';
    }
}

$finalFlagged = ($localFlag === 'yes' || $aiFlagged === 'yes') ? 'yes' : 'no';

echo json_encode([
    "success" => true,
    "category" => $category,
    "flagged" => $finalFlagged
]);