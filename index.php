<?php
// فایل: index.php

$api_key = getenv("OPENAI_API_KEY"); // کلید را از متغیر محیطی بگیرد

if (!isset($_GET['text']) || empty($_GET['text'])) {
    echo json_encode(["error" => "پارامتر text لازم است."]);
    exit;
}

$user_message = $_GET['text'];

$data = [
    "model" => "gpt-4",
    "messages" => [
        [
            "role" => "system",
            "content" => "تو یک دستیار هوشمند به نام رایا جی‌پی‌تی هستی. لحن تو رسمی و مودب است. اگر کسی بپرسد 'تو کی هستی؟'، بگو: 'سلام، من رایا جی‌پی‌تی هستم. در خدمتم، سوالی داشتید بپرسید حتما جواب می‌دم!'"
        ],
        [
            "role" => "user",
            "content" => $user_message
        ]
    ]
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api.openai.com/v1/chat/completions");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Authorization: Bearer $api_key"
]);

$response = curl_exec($ch);
curl_close($ch);

$result = json_decode($response, true);

if (isset($result['choices'][0]['message']['content'])) {
    echo json_encode(["reply" => $result['choices'][0]['message']['content']]);
} else {
    echo json_encode(["error" => "پاسخی از OpenAI دریافت نشد."]);
}
?>
