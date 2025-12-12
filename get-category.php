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
    "contents" => array_merge(
        [
            [
                    "role" => "user",
                    "parts" => [
                        ["text" => "You are an expert message classifier. Your sole task is to read the user's message and output only the single, best-fit category name from the list: 'Anxiety/Worry', 'Depression/Sadness', 'Grief/Loss', 'Relationship Issues', 'Stress/Burnout', or 'General Check-in'. Do not include any other text, explanation, or conversational filler."],
                    ]
                ],
                [
                    "role" => "user",
                    "parts" => [
                        ["text" => "I feel empty and nothing excites me anymore."]
                    ]
                ],
                [
                    "role" => "model",
                    "parts" => [
                        ["text" => "Depression/Sadness"]
                    ]
                ],
                [
                    "role" => "user",
                    "parts" => [
                        ["text" => "I don’t want to get out of bed most days."]
                    ]
                ],
                [
                    "role" => "model",
                    "parts" => [
                        ["text" => "Depression/Sadness"]
                    ]
                ],
                [
                    "role" => "user",
                    "parts" => [
                        ["text" => "My mind won’t stop racing with worries."]
                    ]
                ],
                [
                    "role" => "model",
                    "parts" => [
                        ["text" => "Anxiety/Worry"]
                    ]
                ],
                [
                    "role" => "user",
                    "parts" => [
                        ["text" => "I panic when thinking about the future."]
                    ]
                ],
                [
                    "role" => "model",
                    "parts" => [
                        ["text" => "Anxiety/Worry"]
                    ]
                ],
                [
                    "role" => "user",
                    "parts" => [
                        ["text" => "I feel completely overwhelmed by everything I have to do."]
                    ]
                ],
                [
                    "role" => "model",
                    "parts" => [
                        ["text" => "Stress/Burnout"]
                    ]
                ],
                [
                    "role" => "user",
                    "parts" => [
                        ["text" => "I keep arguing with my partner and it’s exhausting."]
                    ]
                ],
                [
                    "role" => "model",
                    "parts" => [
                        ["text" => "Relationship Issues"]
                    ]
                ],
                [
                    "role" => "user",
                    "parts" => [
                        ["text" => "I lost someone I love and I can’t stop crying."]
                    ]
                ],
                [
                    "role" => "model",
                    "parts" => [
                        ["text" => "Grief/Loss"]
                    ]
                ],
                [
                    "role" => "user",
                    "parts" => [
                        ["text" => "I can’t fall asleep at night because of racing thoughts."]
                    ]
                ],
                [
                    "role" => "model",
                    "parts" => [
                        ["text" => "Anxiety/Worry"]
                    ]
                ],
                [
                    "role" => "user",
                    "parts" => [
                        ["text" => "I always feel like I’m not good enough."]
                    ]
                ],
                [
                    "role" => "model",
                    "parts" => [
                        ["text" => "Depression/Sadness"]
                    ]
                ],
                [
                    "role" => "user",
                    "parts" => [
                        ["text" => "You are an expert message classifier. Your sole task is to read the user's message and output only the single, best-fit category name from the list: 'Anxiety/Worry', 'Depression/Sadness', 'Grief/Loss', 'Relationship Issues', 'Stress/Burnout', or 'General Check-in'. Do not include any other text, explanation, or conversational filler."],
                    ]
                ],
                [
                    "role" => "user",
                    "parts" => [
                        ["text" => "I haven't been able to get out of bed for three days. Nothing feels fun anymore."]
                    ]
                ],
                [
                    "role" => "model",
                    "parts" => [
                        ["text" => "Depression/Sadness"]
                    ]
                ],
                [
                    "role" => "user",
                    "parts" => [
                        ["text" => "I have a big presentation tomorrow and my heart is racing. I keep thinking of all the ways I could mess up and it's making me feel sick."]
                    ]
                ],
                [
                    "role" => "model",
                    "parts" => [
                        ["text" => "Anxiety/Worry"]
                    ]
                ],
                [
                    "role" => "user",
                    "parts" => [
                        ["text" => $content]
                    ]
                ]
                        ]
    )
];
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

$response = curl_exec($ch);
curl_close($ch);

$result = json_decode($response, true);

if (isset($result["candidates"][0]["content"]["parts"][0]["text"])) {
    $category = trim($result["candidates"][0]["content"]["parts"][0]["text"]);

    $validCategories = ['Anxiety/Worry', 'Depression/Sadness', 'Grief/Loss', 'Relationship Issues', 'Stress/Burnout', 'Other'];

    if (in_array($category, $validCategories)) {
        echo json_encode(["success" => true, "category" => $category]);
    } else {
        echo json_encode(["success" => true, "category" => 'Other']);
    }
} else {
    $apiError = $result['error']['message'] ?? "Unknown error.";
    echo json_encode(["success" => false, "error" => "AI failed to categorize the message. API Message: " . $apiError]);
}
?>