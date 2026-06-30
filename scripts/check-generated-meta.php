<?php
$mysqli = new mysqli('127.0.0.1', 'root', 'root', 'local', 10005);
if ($mysqli->connect_errno) {
    fwrite(STDERR, 'Database connection failed: ' . $mysqli->connect_error . PHP_EOL);
    exit(1);
}

$queries = [
    'yoast_meta_descriptions' => "SELECT COUNT(DISTINCT post_id) AS c FROM wp_postmeta WHERE meta_key = '_yoast_wpseo_metadesc' AND meta_value <> ''",
    'yoast_titles' => "SELECT COUNT(DISTINCT post_id) AS c FROM wp_postmeta WHERE meta_key = '_yoast_wpseo_title' AND meta_value <> ''",
    'published_target_posts' => "SELECT COUNT(*) AS c FROM wp_posts WHERE post_status = 'publish' AND post_type IN ('page','mm_service','mm_visa','mm_destination')",
];

$out = [];
foreach ($queries as $key => $sql) {
    $result = $mysqli->query($sql);
    if (!$result) {
        fwrite(STDERR, $mysqli->error . PHP_EOL);
        exit(1);
    }
    $out[$key] = (int) $result->fetch_object()->c;
}

echo json_encode($out, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
