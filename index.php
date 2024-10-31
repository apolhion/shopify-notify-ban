<?php

$lojas = [
    'CAPITÃO PATRIA' => 'https://lojax.store', // Apelido da loja (o que vai aparecer na notificação) e o link da loja shopify
    'MICHAEL JACKSON' => 'https://lojay.store',
//  'P.DIDDY' => 'https://lojab.shop',
    'TEST' => 'https://promoblack-friday.myshopify.com',
];

$statusFile = 'lojas_status.json';
$logFile = 'lojas_log.txt';
$retryLimit = 2;
$minInterval = 3600000;

function carregarStatus($statusFile)
{
    if (file_exists($statusFile)) {
        return json_decode(file_get_contents($statusFile), true);
    }
    return [];
}

function salvarStatus($statusFile, $status)
{
    file_put_contents($statusFile, json_encode($status));
}

function registrarLog($mensagem, $logFile)
{
    $data = date('Y-m-d H:i:s');
    file_put_contents($logFile, "[$data] $mensagem\n", FILE_APPEND);
}

function verificarConexao($url)
{
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => [
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.82 Safari/537.36',
            'Referer: ' . $url,
        ]
    ]);

    $response = curl_exec($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
    print_r($httpCode . '\n');
    return $httpCode === 200 && strpos($response, "This store is unavailable") === false;
}

function verificarLojas($lojas, &$status, $logFile, $minInterval)
{
    foreach ($lojas as $apelido => $url) {
        $conexaoValida = verificarConexao($url);

        if (!$conexaoValida) {
            if (deveEnviarNotificacao($apelido, $status, $minInterval)) {
                enviarNotificacao($apelido);
                $status[$apelido] = [
                    'notificado' => true,
                    'ultimaNotificacao' => time()
                ];
                registrarLog("Notificação enviada para $apelido", $logFile);
            } else {
                registrarLog("Notificação para $apelido ignorada (dentro do intervalo mínimo)", $logFile);
            }
        } else {
            $status[$apelido] = [
                'notificado' => false,
                'ultimaVerificacao' => time()
            ];
            registrarLog("Loja $apelido verificada e está online", $logFile);
        }
    }
}

function deveEnviarNotificacao($apelido, $status, $minInterval)
{
    return empty($status[$apelido]) || !$status[$apelido]['notificado'] ||
        (time() - $status[$apelido]['ultimaNotificacao'] >= $minInterval);
}

function enviarNotificacao($apelido)
{
    $url = "https://api.pushcut.io/1mvXgWZrPurH4}/notifications/LojaBanida";
    $data = [
        'text' => "Red Loja Caiu😤😤😤",
        'title' => "LOJA $apelido CAIU"
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    curl_exec($ch);
    curl_close($ch);
    // Caso queira adicionar notificação em outro dispositivo
    // $url = "https://api.pushcut.io/XSLFxy2gV7T/notifications/My%20First%20Notification";
    // $data = [
    //     'text' => "Red Loja Caiu😤😤😤",
    //     'title' => "LOJA $apelido CAIU"
    // ];

    // $ch = curl_init($url);
    // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    // curl_setopt($ch, CURLOPT_POST, true);
    // curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    // curl_exec($ch);
    // curl_close($ch);
}

$status = carregarStatus($statusFile);

verificarLojas($lojas, $status, $logFile, $minInterval);

salvarStatus($statusFile, $status);
