<?php
$regions = [
    'us-east-1', 'us-west-1', 'us-east-2', 'us-west-2', 
    'eu-west-1', 'eu-west-2', 'eu-west-3', 'eu-central-1', 
    'ap-southeast-1', 'ap-southeast-2', 'ap-northeast-1', 'ap-northeast-2',
    'sa-east-1', 'ca-central-1', 'ap-south-1'
];
$found = false;
foreach ($regions as $r) {
    try {
        $host = "aws-0-$r.pooler.supabase.com";
        $dsn = "pgsql:host=$host;port=6543;dbname=postgres";
        $pdo = new PDO($dsn, 'postgres.sgrythboxdsnwegjjhtk', 'Dantesantiaxel21', [PDO::ATTR_TIMEOUT => 5]);
        $pdo->query("SELECT 1")->fetch(); // Test query
        echo json_encode(['success' => true, 'region' => $r, 'host' => $host]);
        $found = true;
        break;
    } catch (Exception $e) {
        // Ignore timeouts or errors
    }
}
if (!$found) {
    echo json_encode(['success' => false]);
}
