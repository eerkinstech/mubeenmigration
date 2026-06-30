<?php
/**
 * Idempotently create every URL represented by the three supplied sitemaps.
 * Run from the WordPress root with wp-load.php already available.
 */

if (PHP_SAPI !== 'cli') {
    exit("CLI only.\n");
}

define('MUBEEN_BUILD_MODE', true);
defined('DB_HOST') || define('DB_HOST', '127.0.0.1:10005');
require dirname(__DIR__) . '/wp-load.php';

function mm_build_title(string $slug): string
{
    $title = ucwords(str_replace(['-', '_'], ' ', $slug));
    $replacements = [
        'Uk' => 'UK', 'Usa' => 'USA', 'U S' => 'U.S.', 'Pr' => 'PR', 'Pnp' => 'PNP',
        'Visa' => 'Visa', 'Lmia' => 'LMIA', 'Ilr' => 'ILR', 'Hpi' => 'HPI', 'Trv' => 'TRV',
        'Aat' => 'AAT', 'Iad' => 'IAD', 'Arc' => 'ARC', 'Crba' => 'CRBA',
    ];
    return strtr($title, $replacements);
}

function mm_build_content(string $title, string $type): string
{
    if ('mm_destination' === $type) {
        return '<p>Explore visit, study, work, family and longer-term immigration options for <strong>' . esc_html($title) . '</strong>. The suitable route depends on your purpose, background, current rules and supporting evidence.</p><p>Mubeen Migration works with clients in Pakistan and internationally through office, phone and online consultations.</p>';
    }
    if ('mm_service' === $type) {
        return '<p>This service guide introduces <strong>' . esc_html($title) . '</strong>, including route selection, supporting evidence and application preparation. Requirements depend on the destination and individual circumstances.</p><p>Our role is to help you understand the route, prepare relevant documents and reduce avoidable inconsistency before the next step.</p>';
    }
    if ('mm_visa' === $type) {
        return '<p>Use this guide as a starting point for <strong>' . esc_html($title) . '</strong>. Our team can help identify relevant eligibility points, organise documents and review application consistency.</p><p>Every case depends on the applicant profile, destination rules, travel history and evidence quality. Book an appointment for case-specific guidance.</p>';
    }
    return '<p>Mubeen Migration provides clear, responsible visa and immigration guidance with a focus on relevant documents, consistent information and practical next steps.</p>';
}

function mm_build_meta_description(string $title, string $type): string
{
    if ('mm_destination' === $type) {
        return 'Explore visa and immigration pathways for ' . $title . ' with international, case-focused guidance from Mubeen Migration.';
    }
    if (in_array($type, ['mm_service', 'mm_visa'], true)) {
        return 'Understand ' . $title . ' requirements, documents and application stages with international guidance from Mubeen Migration.';
    }
    return 'Explore ' . $title . ' with international visa guidance and case-focused support from Mubeen Migration.';
}

function mm_update_seo_meta(int $id, string $title, string $type): void
{
    $seo_title = $title . ' | Mubeen Migration';
    $description = mm_build_meta_description($title, $type);

    update_post_meta($id, '_yoast_wpseo_title', $seo_title);
    update_post_meta($id, '_yoast_wpseo_metadesc', $description);
    update_post_meta($id, '_yoast_wpseo_focuskw', $title);
    update_post_meta($id, '_aioseo_title', $seo_title);
    update_post_meta($id, '_aioseo_description', $description);
}

function mm_upsert(string $type, string $slug, string $title): int
{
    $existing = get_page_by_path($slug, OBJECT, $type);
    if ($existing instanceof WP_Post) {
        $updates = [
            'ID' => (int) $existing->ID,
            'post_excerpt' => 'Clear international guidance on requirements, documents and practical next steps for ' . $title . '.',
        ];
        if ('1' === (string) get_post_meta((int) $existing->ID, '_mm_sitemap_generated', true)) {
            $updates['post_content'] = mm_build_content($title, $type);
        }
        wp_update_post($updates, true);
        mm_update_seo_meta((int) $existing->ID, $title, $type);
        return (int) $existing->ID;
    }
    $id = wp_insert_post([
        'post_type' => $type,
        'post_status' => 'publish',
        'post_name' => $slug,
        'post_title' => $title,
        'post_excerpt' => 'Clear international guidance on requirements, documents and practical next steps for ' . $title . '.',
        'post_content' => mm_build_content($title, $type),
        'comment_status' => 'closed',
    ], true);
    if (is_wp_error($id)) {
        throw new RuntimeException($type . '/' . $slug . ': ' . $id->get_error_message());
    }
    update_post_meta((int) $id, '_mm_sitemap_generated', '1');
    mm_update_seo_meta((int) $id, $title, $type);
    return (int) $id;
}

$map = ['Visa' => 'mm_service', 'visa-list' => 'mm_visa', 'destination' => 'mm_destination'];
$urls = [];
foreach (['XML Sitemap.xml', 'Visa XML Sitemap.xml', 'Country Sitemap.xml'] as $file) {
    $xml = file_get_contents(dirname(__DIR__) . '/' . $file);
    preg_match_all('~<loc>\s*([^<]+)\s*</loc>~i', (string) $xml, $matches);
    $urls = array_merge($urls, $matches[1] ?? []);
}

$company_pages = [
    'about-us' => 'About Us',
    'contact-us' => 'Contact Us',
    'faqs' => 'FAQs',
    'work-process' => 'Work Process',
    'privacy-policy' => 'Privacy Policy',
];

$created_before = wp_count_posts('page')->publish;
$processed = 0;
foreach (array_unique($urls) as $url) {
    $path = trim((string) parse_url(html_entity_decode($url), PHP_URL_PATH), '/');
    if ('' === $path) {
        continue;
    }
    $parts = explode('/', $path);
    if (isset($map[$parts[0]])) {
        if (count($parts) < 2) {
            continue; // Post type archive.
        }
        $slug = end($parts);
        mm_upsert($map[$parts[0]], $slug, mm_build_title($slug));
        $processed++;
        continue;
    }
    if (1 === count($parts)) {
        mm_upsert('page', $parts[0], mm_build_title($parts[0]));
        $processed++;
    }
}

foreach ($company_pages as $slug => $title) {
    mm_upsert('page', $slug, $title);
}

update_option('permalink_structure', '/%postname%/');
flush_rewrite_rules(false);

echo 'Sitemap records processed: ' . $processed . PHP_EOL;
echo 'Published pages: ' . wp_count_posts('page')->publish . ' (was ' . $created_before . ')' . PHP_EOL;
foreach (['mm_service', 'mm_visa', 'mm_destination'] as $type) {
    $counts = wp_count_posts($type);
    echo $type . ': ' . ($counts->publish ?? 0) . PHP_EOL;
}
