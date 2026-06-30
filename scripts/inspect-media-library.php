<?php
$mysqli = new mysqli('127.0.0.1', 'root', 'root', 'local', 10005);
if ($mysqli->connect_errno) {
    fwrite(STDERR, 'Database connection failed: ' . $mysqli->connect_error . PHP_EOL);
    exit(1);
}

$rows = [];
$result = $mysqli->query(
    "SELECT p.ID, p.post_title, p.post_name, p.post_mime_type,
            pm.meta_value AS file,
            alt.meta_value AS alt
     FROM wp_posts p
     LEFT JOIN wp_postmeta pm
       ON pm.post_id = p.ID AND pm.meta_key = '_wp_attached_file'
     LEFT JOIN wp_postmeta alt
       ON alt.post_id = p.ID AND alt.meta_key = '_wp_attachment_image_alt'
     WHERE p.post_type = 'attachment'
       AND p.post_mime_type LIKE 'image/%'
     ORDER BY p.ID DESC"
);
if (!$result) {
    fwrite(STDERR, 'Media query failed: ' . $mysqli->error . PHP_EOL);
    exit(1);
}
while ($row = $result->fetch_object()) {
    $rows[] = $row;
}

$total = count($rows);
$with_alt = 0;
$with_title = 0;
$useful = [];

foreach ($rows as $row) {
    $title = trim((string) $row->post_title);
    $alt = trim((string) $row->alt);
    if ($title !== '') {
        $with_title++;
    }
    if ($alt !== '') {
        $with_alt++;
    }

    if ($alt !== '' || preg_match('/visa|migration|travel|student|work|family|canada|usa|uk|australia|europe|appointment|consult|passport|airport|logo/i', $title . ' ' . $row->file)) {
        $useful[] = [
            'id' => (int) $row->ID,
            'title' => $title,
            'alt' => $alt,
            'file' => $row->file,
            'mime' => $row->post_mime_type,
        ];
    }
}

echo json_encode([
    'total_images_in_media_library' => $total,
    'images_with_title' => $with_title,
    'images_with_alt' => $with_alt,
    'useful_or_tagged_sample_count' => count($useful),
    'useful_or_tagged_images' => array_slice($useful, 0, 120),
], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
