<?php
$h = get_headers('https://sgrythboxdsnwegjjhtk.supabase.co', 1);
$region = isset($h['x-region']) ? $h['x-region'] : (isset($h['X-Region']) ? $h['X-Region'] : null);
file_put_contents('headers.json', json_encode($h, JSON_PRETTY_PRINT));
if ($region) {
    echo "REGION_FOUND:" . $region;
} else {
    echo "NO_REGION";
}
