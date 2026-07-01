<?php
/**
 * Coded homepage loader.
 *
 * Edit template-home.php for content/markup and assets/home.css for design.
 */

defined('ABSPATH') || exit;

const MM_CONTACT_EMAIL = 'enquiries@mubeenmigration.com';
const MM_PHONE_URL = 'tel:+923211671000';
const MM_PHONE_DISPLAY = '+92 321 167 1000';

function mm_brand_logo(): string
{
    return sprintf(
        '<img class="mm-brand-logo" src="%s" width="300" height="101" alt="Mubeen Migration">',
        esc_url(content_url('/uploads/2026/06/cropped-Logo-300x101.jpg'))
    );
}

function mm_social_links(): array
{
    return [
        'Facebook'  => 'https://www.facebook.com/mubeenmigration.official/',
        'Instagram' => 'https://www.instagram.com/mubeenmigration.official/',
        'LinkedIn'  => 'https://www.linkedin.com/company/mubeenmigration',
        'YouTube'   => 'https://www.youtube.com/@mubeenmigration',
        'TikTok'    => 'https://www.tiktok.com/@mubeenmigration',
        'X'         => 'https://x.com/MubeenMigration',
    ];
}

function mm_render_social_links(): void
{
    echo '<nav class="mm-social-links" aria-label="Social media">';
    foreach (mm_social_links() as $label => $url) {
        printf(
            '<a href="%s" target="_blank" rel="noopener noreferrer" aria-label="Mubeen Migration on %s" title="%s"><span class="mm-social-icon" aria-hidden="true">%s</span><span class="mm-social-label">%s</span></a>',
            esc_url($url),
            esc_attr($label),
            esc_attr($label),
            mm_social_icon_svg($label), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            esc_html($label)
        );
    }
    echo '</nav>';
}

function mm_social_icon_svg(string $label): string
{
    $paths = [
        'Facebook' => '<path d="M14 8.5h3V5h-3c-3.1 0-5 1.9-5 5v2H6v3.5h3V23h4v-7.5h3.2l.8-3.5h-4v-1.7c0-1.2.4-1.8 1-1.8Z"/>',
        'Instagram' => '<rect x="3" y="3" width="18" height="18" rx="5" fill="none" stroke="currentColor" stroke-width="2"/><circle cx="12" cy="12" r="4" fill="none" stroke="currentColor" stroke-width="2"/><circle cx="17.5" cy="6.5" r="1.2"/>',
        'LinkedIn' => '<path d="M5 8.5h4V21H5V8.5Zm2-5A2.3 2.3 0 1 1 7 8a2.3 2.3 0 0 1 0-4.5ZM11 8.5h3.8v1.7h.1c.6-1 1.9-2.2 4-2.2 4.2 0 5 2.7 5 6.4V21h-4v-5.8c0-1.4 0-3.2-2.1-3.2s-2.4 1.5-2.4 3.1V21h-4V8.5Z" transform="scale(.88)"/>',
        'YouTube' => '<path d="M22 8.1a3 3 0 0 0-2.1-2.2C18 5.4 12 5.4 12 5.4s-6 0-7.9.5A3 3 0 0 0 2 8.1 31 31 0 0 0 1.5 12 31 31 0 0 0 2 15.9a3 3 0 0 0 2.1 2.2c1.9.5 7.9.5 7.9.5s6 0 7.9-.5a3 3 0 0 0 2.1-2.2 31 31 0 0 0 .5-3.9 31 31 0 0 0-.5-3.9ZM10 15.5v-7l6 3.5-6 3.5Z"/>',
        'TikTok' => '<path d="M15 3c.4 2.5 1.8 4 4.5 4.2v3.4a9 9 0 0 1-4.5-1.3v6.4a6.4 6.4 0 1 1-5.5-6.3v3.5a3 3 0 1 0 2 2.8V3H15Z"/>',
        'X' => '<path d="M4 4h4.7l4.1 5.5L17.6 4H20l-6.1 7.1L21 20h-4.7l-4.5-5.9L6.7 20H4.2l6.5-7.6L4 4Zm3.5 2 10.1 12h1L8.5 6h-1Z"/>',
    ];
    $path = $paths[$label] ?? '<circle cx="12" cy="12" r="8"/>';
    return '<svg viewBox="0 0 24 24" focusable="false" aria-hidden="true" fill="currentColor">' . $path . '</svg>';
}

function mm_url(string $path): string
{
    return home_url($path);
}

function mm_media_url(string $file): string
{
    return content_url('/uploads/2026/06/' . ltrim($file, '/'));
}

function mm_media_by_filename(string $filename, string $fallback_alt = ''): array
{
    $filename = wp_basename($filename);
    foreach (mm_media_candidates() as $image) {
        if (wp_basename((string) $image['file']) === $filename) {
            return $image;
        }
    }

    $matches = get_posts([
        'post_type' => 'attachment',
        'post_status' => 'inherit',
        'post_mime_type' => 'image',
        'posts_per_page' => 1,
        'fields' => 'ids',
        'no_found_rows' => true,
        'meta_query' => [[
            'key' => '_wp_attached_file',
            'value' => $filename,
            'compare' => 'LIKE',
        ]],
    ]);
    if ($matches) {
        $id = (int) $matches[0];
        $url = wp_get_attachment_image_url($id, 'large') ?: wp_get_attachment_url($id);
        if ($url) {
            return [
                'id' => $id,
                'url' => $url,
                'title' => get_the_title($id),
                'alt' => (string) get_post_meta($id, '_wp_attachment_image_alt', true) ?: $fallback_alt,
                'file' => (string) get_post_meta($id, '_wp_attached_file', true),
                'search' => mm_normalize_text($filename . ' ' . $fallback_alt),
            ];
        }
    }

    $uploads = wp_upload_dir();
    return [
        'id' => 0,
        'url' => trailingslashit((string) $uploads['baseurl']) . rawurlencode($filename),
        'title' => $fallback_alt,
        'alt' => $fallback_alt,
        'file' => $filename,
        'search' => mm_normalize_text($filename . ' ' . $fallback_alt),
    ];
}

function mm_destination_image_data(string $slug, string $label = ''): array
{
    $slug = sanitize_title(wp_basename(trim($slug, '/')));
    $aliases = [
        'united-kingdom' => 'uk',
        'united-states' => 'usa',
        'us' => 'usa',
    ];
    $slug = $aliases[$slug] ?? $slug;
    $files = [
        'uk' => 'UK.jpg',
        'canada' => 'Canada.webp',
        'australia' => 'Australia.jpg',
        'new-zealand' => 'New-Zealand-scaled.avif',
        'usa' => 'USA.webp',
    ];
    if (!isset($files[$slug])) {
        return [];
    }

    $country = $label ?: ucwords(str_replace('-', ' ', $slug));
    return mm_media_by_filename($files[$slug], $country . ' visa and immigration guidance');
}

function mm_render_frontend_site_icon(): void
{
    $icon_id = (int) get_option('site_icon');
    $version = $icon_id > 0 ? (string) get_post_modified_time('U', true, $icon_id) : '1';
    $fallback = null;
    $icon_url = static function (int $size) use ($icon_id, $version, &$fallback): string {
        $url = $icon_id > 0 ? get_site_icon_url($size, '') : '';
        if (!$url) {
            $fallback ??= mm_media_by_filename('2-e1782887403428.png', 'Mubeen Migration');
            $url = (string) ($fallback['url'] ?? '');
        }
        return $url ? add_query_arg('mm-icon', $version, $url) : '';
    };

    $icon_32 = $icon_url(32);
    $icon_192 = $icon_url(192);
    $icon_180 = $icon_url(180);
    $icon_270 = $icon_url(270);
    if (!$icon_32) {
        return;
    }

    printf("\n<link rel=\"icon\" href=\"%s\" sizes=\"32x32\">", esc_url($icon_32));
    printf("\n<link rel=\"shortcut icon\" href=\"%s\">", esc_url($icon_32));
    printf("\n<link rel=\"icon\" href=\"%s\" sizes=\"192x192\">", esc_url($icon_192));
    printf("\n<link rel=\"apple-touch-icon\" href=\"%s\">", esc_url($icon_180));
    printf("\n<meta name=\"msapplication-TileImage\" content=\"%s\">\n", esc_url($icon_270));
}

add_action('wp', static function (): void {
    remove_action('wp_head', 'wp_site_icon', 99);
});
add_action('wp_head', 'mm_render_frontend_site_icon', 1);

function mm_page_content_catalog(): array
{
    static $catalog = null;
    if (null !== $catalog) {
        return $catalog;
    }

    $file = __DIR__ . '/content/pages.json';
    $decoded = is_readable($file) ? json_decode((string) file_get_contents($file), true) : [];
    $catalog = is_array($decoded) ? $decoded : [];
    return $catalog;
}

function mm_page_content(string $slug = ''): array
{
    $slug = $slug ?: (string) get_post_field('post_name');
    $catalog = mm_page_content_catalog();
    return isset($catalog[$slug]) && is_array($catalog[$slug]) ? $catalog[$slug] : [];
}

add_action('init', static function (): void {
    $version = '2026-07-01-2';
    if (get_option('mm_structured_pages_version') === $version) {
        return;
    }

    foreach (mm_page_content_catalog() as $slug => $page) {
        $existing = get_page_by_path($slug, OBJECT, 'page');
        $title = sanitize_text_field((string) ($page['title'] ?? ucwords(str_replace('-', ' ', $slug))));
        $excerpt = sanitize_text_field((string) ($page['meta_description'] ?? $page['intro'] ?? ''));
        if ($existing instanceof WP_Post) {
            $id = (int) $existing->ID;
            wp_update_post(['ID' => $id, 'post_title' => $title, 'post_excerpt' => $excerpt]);
        } else {
            $id = (int) wp_insert_post([
                'post_type' => 'page',
                'post_status' => 'publish',
                'post_name' => sanitize_title($slug),
                'post_title' => $title,
                'post_excerpt' => $excerpt,
                'post_content' => '',
                'comment_status' => 'closed',
            ]);
        }
        if ($id > 0) {
            update_post_meta($id, '_yoast_wpseo_title', sanitize_text_field((string) ($page['meta_title'] ?? '')));
            update_post_meta($id, '_yoast_wpseo_metadesc', $excerpt);
        }
    }
    update_option('mm_structured_pages_version', $version, false);
    flush_rewrite_rules(false);
}, 30);

function mm_normalize_text(string $text): string
{
    return strtolower(trim(preg_replace('/[^a-z0-9]+/i', ' ', $text) ?: ''));
}

function mm_media_candidates(): array
{
    static $images = null;
    if (null !== $images) {
        return $images;
    }

    $images = [];
    $attachments = get_posts([
        'post_type' => 'attachment',
        'post_status' => 'inherit',
        'post_mime_type' => 'image',
        'posts_per_page' => 1500,
        'orderby' => 'ID',
        'order' => 'DESC',
        'fields' => 'ids',
        'no_found_rows' => true,
        'update_post_meta_cache' => true,
        'update_post_term_cache' => false,
    ]);

    foreach ($attachments as $id) {
        $url = wp_get_attachment_image_url((int) $id, 'large') ?: wp_get_attachment_url((int) $id);
        if (!$url) {
            continue;
        }
        $title = get_the_title((int) $id);
        $alt = (string) get_post_meta((int) $id, '_wp_attachment_image_alt', true);
        $file = (string) get_post_meta((int) $id, '_wp_attached_file', true);
        $search = mm_normalize_text($title . ' ' . $alt . ' ' . $file);
        $images[] = [
            'id' => (int) $id,
            'url' => $url,
            'title' => $title,
            'alt' => $alt ?: $title,
            'file' => $file,
            'search' => $search,
        ];
    }

    return $images;
}

function mm_page_image_terms(string $slug = '', string $post_type = ''): array
{
    $title = wp_strip_all_tags(get_the_title());
    $haystack = mm_normalize_text($slug . ' ' . $title . ' ' . $post_type);

    $rules = [
        ['terms' => ['logo'], 'when' => ['logo']],
        ['terms' => ['uk', 'united kingdom', 'uk visa'], 'when' => ['uk', 'united kingdom', 'british', 'kingdom']],
        ['terms' => ['canada', 'canada immigration'], 'when' => ['canada', 'quebec', 'lmia', 'express entry', 'pnp', 'super visa', 'pgwp']],
        ['terms' => ['australia', 'australia immigration'], 'when' => ['australia', 'subclass', 'aat']],
        ['terms' => ['new zealand'], 'when' => ['new zealand', 'zealand']],
        ['terms' => ['usa immigration', 'usa', 'united states'], 'when' => ['usa', 'united states', 'u s', 'f 1', 'b 1', 'b 2', 'h 1b', 'j 1', 'e 2', 'eb 5', 'cr 1', 'ir 1']],
        ['terms' => ['europe'], 'when' => ['europe', 'schengen']],
        ['terms' => ['study visa', 'student visa'], 'when' => ['study', 'student', 'academic', 'exchange', 'education']],
        ['terms' => ['work visa', 'skilled migration visa', 'employement based visa'], 'when' => ['work', 'worker', 'skilled', 'employment', 'employer', 'occupation', 'tss', 'ens']],
        ['terms' => ['family and partner visa', 'family sponsorship visa', 'dependent visa', 'family based visa'], 'when' => ['family', 'partner', 'spouse', 'dependent', 'dependant', 'parent', 'sponsorship']],
        ['terms' => ['settlement and long residence', 'permanent residence pathways', 'permanent residence'], 'when' => ['settlement', 'long residence', 'permanent residence', 'pr', 'ilr']],
        ['terms' => ['business visa', 'investment', 'employement based visa'], 'when' => ['business', 'investment', 'investor', 'entrepreneur', 'founder']],
        ['terms' => ['appeals and legal remedies', 'refusal and legal remedies', 'appeals and reviews'], 'when' => ['refusal', 'appeal', 'review', 'remedies', '221g', 'administrative processing']],
        ['terms' => ['visit visa', 'visa approved'], 'when' => ['visit', 'visitor', 'tourism', 'tourist', 'temporary']],
        ['terms' => ['citizenship', 'citizenship and status'], 'when' => ['citizenship', 'nationality']],
        ['terms' => ['consultancy', 'immigration consultant', 'mubeen migration consultancy'], 'when' => ['about', 'contact', 'consultation', 'consultancy', 'office']],
        ['terms' => ['destinations mubeen migration', 'destinations', 'destination'], 'when' => ['destination', 'destinations']],
    ];

    $terms = [];
    foreach ($rules as $rule) {
        foreach ($rule['when'] as $needle) {
            if (str_contains($haystack, $needle)) {
                $terms = array_merge($terms, $rule['terms']);
                break;
            }
        }
    }

    if (!$terms) {
        if ('mm_destination' === $post_type) {
            $terms = ['destinations', 'destination'];
        } elseif (in_array($post_type, ['mm_service', 'mm_visa'], true)) {
            $terms = ['visa approved', 'immigration consultant', 'consultancy'];
        } else {
            $terms = ['mubeen migration consultancy', 'immigration office', 'consultancy'];
        }
    }

    return array_values(array_unique($terms));
}

function mm_page_image_data(string $slug = '', string $post_type = ''): array
{
    if ('mm_destination' === $post_type) {
        $destination_image = mm_destination_image_data($slug);
        if ($destination_image) {
            return $destination_image;
        }
    }

    $terms = mm_page_image_terms($slug, $post_type);
    $best = null;
    $best_score = 0;

    foreach (mm_media_candidates() as $image) {
        if (!str_contains($image['search'], 'logo')) {
            $score = 0;
            foreach ($terms as $index => $term) {
                $needle = mm_normalize_text($term);
                if ($needle && str_contains($image['search'], $needle)) {
                    $score += 100 - ($index * 4);
                } else {
                    foreach (explode(' ', $needle) as $part) {
                        if (strlen($part) > 3 && str_contains($image['search'], $part)) {
                            $score += 12;
                        }
                    }
                }
            }
            if (str_contains($image['search'], '1024x') || str_contains($image['search'], '1125x') || str_contains($image['search'], '1300x')) {
                $score += 4;
            }
            if ($score > $best_score) {
                $best_score = $score;
                $best = $image;
            }
        }
    }

    if ($best && $best_score > 0) {
        return $best;
    }

    return [
        'id' => 0,
        'url' => mm_media_url('ChatGPT-Image-Dec-15-2025-12_02_38-PM-1.webp'),
        'title' => 'Mubeen Migration consultancy',
        'alt' => 'Mubeen Migration consultancy',
        'file' => '',
        'search' => '',
    ];
}

function mm_page_image_url(string $slug = '', string $post_type = ''): string
{
    $image = mm_page_image_data($slug, $post_type);
    return $image['url'];
}

function mm_render_page_image(string $class = 'mm-page-image', string $alt = ''): void
{
    $slug = is_post_type_archive() ? (string) get_query_var('post_type') : (string) get_post_field('post_name');
    $post_type = is_post_type_archive() ? (string) get_query_var('post_type') : (string) get_post_type();
    $image = mm_page_image_data($slug, $post_type);
    printf(
        '<img class="%s" src="%s" alt="%s" loading="lazy">',
        esc_attr($class),
        esc_url($image['url']),
        esc_attr($alt ?: $image['alt'] ?: wp_strip_all_tags(get_the_title() ?: 'Mubeen Migration visa guidance'))
    );
}

function mm_nav_service_links(): array
{
    return [
        ['Visit visas', '/Visa/visit-visa/', 'Tourism, family visits, medical travel, transit and short stays'],
        ['Study visas', '/Visa/study-visa/', 'Student, child student, exchange and language-study routes'],
        ['Work & employment', '/Visa/worker-visa/', 'Skilled, sponsored, temporary and specialist worker routes'],
        ['Family & settlement', '/Visa/family-settlement-visas/', 'Spouse, partner, parent, child and family pathways'],
        ['Dependant visas', '/Visa/dependant-visas/', 'Dependant routes for workers, students and eligible sponsors'],
        ['Business & investment', '/Visa/business-and-investment-visas/', 'Founder, investor, trader and business routes'],
        ['Permanent residence', '/Visa/permanent-residence-pathways/', 'PR, ILR, green card and long-residence pathways'],
        ['Citizenship & status', '/Visa/citizenship-and-status/', 'Citizenship, passport, PR card and civil-status services'],
        ['Appeals & remedies', '/Visa/refusals-legal-remedies/', 'Refusals, reviews, waivers, appeals and re-application strategy'],
    ];
}

function mm_service_cards(): array
{
    return mm_nav_service_links();
}

function mm_catalog_visa_query(array $args = []): WP_Query
{
    $meta_query = [];
    if (!empty($args['country'])) {
        $meta_query[] = ['key' => '_mm_country', 'value' => sanitize_key($args['country'])];
    }
    if (!empty($args['service'])) {
        $meta_query[] = ['key' => '_mm_service_slug', 'value' => sanitize_title($args['service'])];
    }
    if (!empty($args['group'])) {
        $meta_query[] = ['key' => '_mm_group', 'value' => sanitize_text_field($args['group'])];
    }

    return new WP_Query([
        'post_type' => 'mm_visa',
        'post_status' => 'publish',
        'posts_per_page' => $args['limit'] ?? -1,
        'orderby' => 'menu_order title',
        'order' => 'ASC',
        'meta_query' => $meta_query,
        'no_found_rows' => true,
    ]);
}

function mm_catalog_countries(): array
{
    return [
        'uk' => 'United Kingdom',
        'usa' => 'United States',
        'australia' => 'Australia',
        'canada' => 'Canada',
    ];
}

function mm_catalog_country_from_destination_slug(string $slug): string
{
    $map = ['uk' => 'uk', 'usa' => 'usa', 'united-states' => 'usa', 'australia' => 'australia', 'canada' => 'canada'];
    return $map[$slug] ?? $slug;
}

function mm_catalog_country_label(string $country): string
{
    $countries = mm_catalog_countries();
    return $countries[$country] ?? ucwords(str_replace('-', ' ', $country));
}

function mm_catalog_service_title(string $service_slug): string
{
    foreach (mm_service_cards() as [$title, $url]) {
        if (trim((string) parse_url($url, PHP_URL_PATH), '/') === 'Visa/' . $service_slug) {
            return $title;
        }
    }
    return ucwords(str_replace('-', ' ', $service_slug));
}

function mm_nav_visa_groups(): array
{
    return [
        'UK visas' => [
            ['UK Standard Visitor', '/visa-list/uk-standard-visitor-visa/'],
            ['UK Tourism Visitor', '/visa-list/uk-tourism-visitor-visa/'],
            ['UK Business Visitor', '/visa-list/uk-business-meetings-visitor-visa/'],
            ['UK Student Visa', '/visa-list/uk-student-visa-formerly-tier-4/'],
            ['UK Child Student', '/visa-list/uk-child-student-visa/'],
            ['Short-term Study', '/visa-list/uk-short-term-study-visa/'],
            ['Skilled Worker', '/visa-list/skilled-worker-visa/'],
            ['Health and Care Worker', '/visa-list/health-and-care-worker-visa/'],
            ['Partner / Spouse routes', '/visa-list/spouse-partner-visa/'],
            ['Adult Dependant Relative', '/visa-list/adult-dependant-relative/'],
            ['Indefinite Leave to Remain', '/visa-list/indefinite-leave-to-remain-ilr/'],
        ],
        'Australia visas' => [
            ['Visitor Visa 600', '/visa-list/visitor-visa-subclass-600-tourism-family-visit-business-visitor/'],
            ['Student Visa 500', '/visa-list/student-visa-subclass-500/'],
            ['Training Visa 407', '/visa-list/training-visa-subclass-407/'],
            ['TSS Work Visa 482', '/visa-list/temporary-skill-shortage-tss-visa-subclass-482/'],
            ['ENS Visa 186', '/visa-list/employer-nomination-scheme-ens-visa-subclass-186/'],
            ['Partner Visa 309/100', '/visa-list/partner-visa-subclass-309-100-offshore/'],
            ['Partner Visa 820/801', '/visa-list/partner-visa-subclass-820-801-onshore/'],
            ['Parent Visas', '/visa-list/parent-visas-contributory-non-contributory/'],
            ['Australia Business 188', '/visa-list/business-innovation-investment-visa-subclass-188/'],
            ['Bridging Visa A', '/visa-list/bridging-visa-a-bva-subclass-010/'],
        ],
        'Canada visas' => [
            ['Visitor Visa TRV', '/visa-list/visitor-visa-trv/'],
            ['Super Visa', '/visa-list/super-visa-parents-grandparents/'],
            ['Study Permit', '/visa-list/study-permit/'],
            ['Study Permit Extension', '/visa-list/study-permit-extension/'],
            ['LMIA Work Permit', '/visa-list/lmia-based-work-permit/'],
            ['LMIA-exempt Work Permit', '/visa-list/lmia-exempt-work-permits/'],
            ['Post-Graduation Work Permit', '/visa-list/post-graduation-work-permit-pgwp/'],
            ['Federal Skilled Worker', '/visa-list/federal-skilled-worker-program-fswp/'],
            ['Canadian Experience Class', '/visa-list/canadian-experience-class-cec/'],
            ['Family Sponsorship', '/Visa/family-sponsorship/'],
        ],
        'US visas' => [
            ['B-1 Business Visitor', '/visa-list/b-1-business-visitor-visa/'],
            ['B-1/B-2 Visitor', '/visa-list/b-1-b-2-combined-business-tourism-visitor-visa/'],
            ['F-1 Student Visa', '/visa-list/f-1-academic-student-visa/'],
            ['F-2 Dependant', '/visa-list/f-2-dependant-of-f-1/'],
            ['J-1 Exchange Visitor', '/visa-list/j-1-exchange-visitor-visa/'],
            ['H-1B Specialty Occupation', '/visa-list/h-1b-specialty-occupation/'],
            ['E-2 Treaty Investor', '/visa-list/e-2-treaty-investor-visa/'],
            ['EB-5 Investor', '/visa-list/eb-5-immigrant-investor-program/'],
            ['IR-1 / CR-1 Spouse', '/visa-list/ir-1-cr-1-spouse-of-a-u-s-citizen/'],
            ['221(g) Processing Help', '/visa-list/administrative-processing-221g-assistance/'],
        ],
    ];
}

function mm_nav_destination_links(): array
{
    return [
        ['United Kingdom', '/destination/uk/', 'Visit, study, work and settlement'],
        ['Canada', '/destination/canada/', 'Temporary and permanent pathways'],
        ['Australia', '/destination/australia/', 'Study, visitor and skilled routes'],
        ['New Zealand', '/destination/new-zealand/', 'Visit, study and work guidance'],
        ['United States', '/destination/usa/', 'Visit, study, family and business'],
        ['Europe', '/destination/europe/', 'Selected European routes'],
    ];
}

function mm_render_primary_header(string $phone = MM_PHONE_URL, string $email = MM_CONTACT_EMAIL): void
{
    ?>
    <div class="mm-topbar">
        <div class="mm-wrap mm-topbar-inner">
            <p>Book international visa guidance online or in office</p>
            <div class="mm-topbar-links">
                <a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a>
                <a href="<?php echo esc_url($phone); ?>"><?php echo esc_html(MM_PHONE_DISPLAY); ?></a>
            </div>
        </div>
    </div>
    <header class="mm-header">
        <div class="mm-wrap mm-header-inner">
            <a class="mm-brand" href="<?php echo esc_url(home_url('/')); ?>" aria-label="Mubeen Migration home">
                <?php echo mm_brand_logo(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            </a>
            <button class="mm-menu-toggle" type="button" aria-expanded="false" aria-controls="mm-primary-menu" data-menu-toggle>
                <span></span><span></span><span></span><span class="screen-reader-text">Open menu</span>
            </button>
            <nav id="mm-primary-menu" class="mm-nav" aria-label="Primary navigation" data-site-menu>
                <div class="mm-nav-main">
                    <a href="<?php echo esc_url(home_url('/')); ?>">Home</a>
                    <div class="mm-nav-item mm-has-mega">
                        <a href="<?php echo esc_url(home_url('/Visa/')); ?>" class="mm-nav-link">Visa</a>
                        <button class="mm-submenu-toggle" type="button" aria-expanded="false" aria-controls="mm-visa-submenu" data-submenu-toggle><span aria-hidden="true"></span><span class="screen-reader-text">Toggle Visa submenu</span></button>
                        <div id="mm-visa-submenu" class="mm-mega-menu" aria-label="Services mega menu" data-submenu>
                            <div class="mm-mega-feature">
                                <span>Visa categories</span>
                                <h3>Choose the right visa category first.</h3>
                                <p>Open a main Visa category to see all relevant sub visa-list pages by destination.</p>
                                <a class="mm-button mm-button-small" href="<?php echo esc_url(home_url('/Visa/')); ?>">All Visa categories</a>
                            </div>
                            <div class="mm-mega-services">
                                <h4>Main Visa categories</h4>
                                <?php foreach (mm_nav_service_links() as [$label, $url, $description]) : ?>
                                    <a href="<?php echo esc_url(home_url($url)); ?>"><strong><?php echo esc_html($label); ?></strong><small><?php echo esc_html($description); ?></small></a>
                                <?php endforeach; ?>
                            </div>
                            <div class="mm-mega-visas">
                                <div class="mm-mega-title"><h4>Destinations</h4><a href="<?php echo esc_url(home_url('/destination/')); ?>">View destinations</a></div>
                                <div class="mm-mega-visa-grid">
                                    <?php foreach (mm_nav_destination_links() as [$group, $url, $description]) : ?>
                                        <div>
                                            <strong><?php echo esc_html($group); ?></strong>
                                            <a href="<?php echo esc_url(home_url($url)); ?>"><?php echo esc_html($description); ?></a>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mm-nav-item mm-has-menu">
                        <a href="<?php echo esc_url(home_url('/destination/')); ?>" class="mm-nav-link">Destinations</a>
                        <button class="mm-submenu-toggle" type="button" aria-expanded="false" aria-controls="mm-destination-submenu" data-submenu-toggle><span aria-hidden="true"></span><span class="screen-reader-text">Toggle Destinations submenu</span></button>
                        <div id="mm-destination-submenu" class="mm-dropdown-menu" aria-label="Destinations menu" data-submenu>
                            <?php foreach (mm_nav_destination_links() as [$label, $url, $description]) : ?>
                                <a href="<?php echo esc_url(home_url($url)); ?>"><strong><?php echo esc_html($label); ?></strong><small><?php echo esc_html($description); ?></small></a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="mm-nav-item mm-has-menu">
                        <a href="<?php echo esc_url(home_url('/about-us/')); ?>" class="mm-nav-link">Company</a>
                        <button class="mm-submenu-toggle" type="button" aria-expanded="false" aria-controls="mm-company-submenu" data-submenu-toggle><span aria-hidden="true"></span><span class="screen-reader-text">Toggle Company submenu</span></button>
                        <div id="mm-company-submenu" class="mm-dropdown-menu" aria-label="Company menu" data-submenu>
                            <a href="<?php echo esc_url(home_url('/about-us/')); ?>"><strong>About us</strong><small>Our approach and leadership</small></a>
                            <a href="<?php echo esc_url(home_url('/our-team/')); ?>"><strong>Our team</strong><small>Meet the people behind the work</small></a>
                            <a href="<?php echo esc_url(home_url('/immigration/')); ?>"><strong>Immigration</strong><small>International services and pathways</small></a>
                            <a href="<?php echo esc_url(home_url('/work-process/')); ?>"><strong>Work process</strong><small>From consultation to readiness</small></a>
                            <a href="<?php echo esc_url(home_url('/blogs/')); ?>"><strong>Blogs</strong><small>Visa guides and immigration insights</small></a>
                        </div>
                    </div>
                    <a href="<?php echo esc_url(home_url('/faqs/')); ?>">FAQs</a>
                    <a href="<?php echo esc_url(home_url('/contact-us/')); ?>">Contact</a>
                </div>
                <div class="mm-nav-actions"><a class="mm-button mm-button-small" href="<?php echo esc_url($phone); ?>">Call now</a></div>
            </nav>
        </div>
    </header>
    <?php
}

function mm_render_global_footer(string $phone = MM_PHONE_URL, string $email = MM_CONTACT_EMAIL): void
{
    $footer_logo = mm_media_by_filename('2-e1782887403428.png', 'Mubeen Migration');
    ?>
    <section class="mm-inner-cta"><div class="mm-wrap"><div><span>Speak with our team</span><h2>Call for clear guidance on your immigration plans.</h2></div><a class="mm-button mm-button-dark" href="<?php echo esc_url($phone); ?>">Call <?php echo esc_html(MM_PHONE_DISPLAY); ?></a></div></section>
    <footer class="mm-footer">
        <div class="mm-wrap">
            <div class="mm-footer-grid">
                <div class="mm-footer-about">
                    <a class="mm-footer-logo" href="<?php echo esc_url(home_url('/')); ?>" aria-label="Mubeen Migration home"><img src="<?php echo esc_url($footer_logo['url']); ?>" alt="<?php echo esc_attr($footer_logo['alt'] ?: 'Mubeen Migration'); ?>" loading="lazy"></a>
                    <p>International visa and immigration guidance built around route clarity, relevant evidence and responsible preparation.</p>
                    <?php mm_render_social_links(); ?>
                </div>
                <div><h3>Immigration</h3><a href="<?php echo esc_url(home_url('/immigration/')); ?>">Immigration services</a><a href="<?php echo esc_url(home_url('/Visa/visit-visa/')); ?>">Visit visas</a><a href="<?php echo esc_url(home_url('/Visa/study-visa/')); ?>">Study visas</a><a href="<?php echo esc_url(home_url('/Visa/worker-visa/')); ?>">Work visas</a><a href="<?php echo esc_url(home_url('/Visa/family-settlement-visas/')); ?>">Family visas</a><a href="<?php echo esc_url(home_url('/Visa/')); ?>">All visa categories</a></div>
                <div><h3>Company</h3><a href="<?php echo esc_url(home_url('/about-us/')); ?>">About us</a><a href="<?php echo esc_url(home_url('/our-team/')); ?>">Our team</a><a href="<?php echo esc_url(home_url('/work-process/')); ?>">Work process</a><a href="<?php echo esc_url(home_url('/blogs/')); ?>">Blogs</a><a href="<?php echo esc_url(home_url('/contact-us/')); ?>">Contact us</a></div>
                <div><h3>Explore & help</h3><a href="<?php echo esc_url(home_url('/destination/')); ?>">All destinations</a><a href="<?php echo esc_url(home_url('/faqs/')); ?>">FAQs</a><a href="<?php echo esc_url(home_url('/appointment/')); ?>">Appointment</a><a href="<?php echo esc_url(home_url('/privacy-policy/')); ?>">Privacy policy</a><a href="<?php echo esc_url(home_url('/return-refund/')); ?>">Return & refund</a></div>
                <div class="mm-footer-contact"><h3>Contact</h3><a href="<?php echo esc_url($phone); ?>"><?php echo esc_html(MM_PHONE_DISPLAY); ?></a><a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a><p>Online consultations worldwide<br>4th Floor, 51 CCA, DHA Phase 5, Lahore</p></div>
            </div>
            <div class="mm-footer-bottom"><span>&copy; <?php echo esc_html(wp_date('Y')); ?> Mubeen Migration. All rights reserved.</span><span>Visa decisions are made by the relevant authorities.</span></div>
        </div>
    </footer>
    <?php
}

function mm_is_coded_page(): bool
{
    return is_front_page() || is_page() || is_singular(['post', 'mm_service', 'mm_visa', 'mm_destination']) ||
        is_post_type_archive(['mm_service', 'mm_visa', 'mm_destination']);
}

function mm_meta_description(): string
{
    if (is_front_page()) {
        return 'International visa and immigration guidance for visit, study, work, family and business routes. Speak with Mubeen Migration about your plans.';
    }

    if (is_page()) {
        $content = mm_page_content();
        if (!empty($content['meta_description'])) {
            return sanitize_text_field((string) $content['meta_description']);
        }
    }

    if (is_singular('post')) {
        $description = get_the_excerpt() ?: wp_trim_words(wp_strip_all_tags((string) get_post_field('post_content')), 28, '');
        return $description ?: 'Read practical visa and immigration guidance from Mubeen Migration.';
    }

    $title = wp_strip_all_tags(is_post_type_archive() ? post_type_archive_title('', false) : get_the_title());
    $post_type = is_post_type_archive() ? get_query_var('post_type') : get_post_type();
    if ('mm_destination' === $post_type) {
        return sprintf('Explore visa and immigration pathways for %s with international, case-focused guidance from Mubeen Migration.', $title);
    }
    if (in_array($post_type, ['mm_service', 'mm_visa'], true)) {
        return sprintf('Understand %s requirements, documents and application stages with international guidance from Mubeen Migration.', $title);
    }
    return sprintf('Explore %s with international visa guidance and case-focused support from Mubeen Migration in Pakistan and worldwide.', $title ?: 'visa and immigration options');
}

add_filter('pre_get_document_title', static function (string $title): string {
    if (!mm_is_coded_page()) {
        return $title;
    }
    if (is_front_page()) {
        return 'Mubeen Migration | International Visa & Immigration Guidance';
    }
    if (is_page()) {
        $content = mm_page_content();
        if (!empty($content['meta_title'])) {
            return sanitize_text_field((string) $content['meta_title']);
        }
    }
    $page_title = is_post_type_archive() ? post_type_archive_title('', false) : get_the_title();
    return ($page_title ?: 'Visa & Immigration Services') . ' | Mubeen Migration';
}, 20);

function mm_meta_title(): string
{
    if (is_front_page()) {
        return 'Mubeen Migration | International Visa & Immigration Guidance';
    }
    if (is_page()) {
        $content = mm_page_content();
        if (!empty($content['meta_title'])) {
            return sanitize_text_field((string) $content['meta_title']);
        }
    }
    $page_title = is_post_type_archive() ? post_type_archive_title('', false) : get_the_title();
    return ($page_title ?: 'Visa & Immigration Services') . ' | Mubeen Migration';
}

add_filter('wpseo_title', static function (string $title): string {
    return mm_is_coded_page() ? mm_meta_title() : $title;
}, 20);
add_filter('wpseo_metadesc', static function (string $description): string {
    return mm_is_coded_page() ? mm_meta_description() : $description;
}, 20);
add_filter('wpseo_opengraph_title', static function (string $title): string {
    return mm_is_coded_page() ? mm_meta_title() : $title;
}, 20);
add_filter('wpseo_opengraph_desc', static function (string $description): string {
    return mm_is_coded_page() ? mm_meta_description() : $description;
}, 20);
add_filter('wpseo_schema_website', static function (array $data): array {
    if (mm_is_coded_page()) {
        $data['description'] = 'International visa and immigration guidance for clients in Pakistan and worldwide.';
    }
    return $data;
});
add_filter('wpseo_schema_webpage', static function (array $data): array {
    if (mm_is_coded_page()) {
        $data['name'] = mm_meta_title();
        $data['description'] = mm_meta_description();
    }
    return $data;
});
add_filter('wpseo_schema_organization', static function (array $data): array {
    if (mm_is_coded_page()) {
        $data['alternateName'] = 'Mubeen Migration International';
        $data['sameAs'] = array_values(mm_social_links());
        $data['email'] = MM_CONTACT_EMAIL;
        $data['telephone'] = '+92-321-1671000';
        $data['areaServed'] = 'Worldwide';
        $data['address'] = [
            '@type' => 'PostalAddress',
            'streetAddress' => '4th Floor, 51 CCA, DHA Phase 5',
            'addressLocality' => 'Lahore',
            'postalCode' => '54000',
            'addressCountry' => 'PK',
        ];
    }
    return $data;
});

// Yoast supplies the canonical description. Prevent Hello Elementor from
// printing a second, stale description sourced from the old page excerpt.
add_action('after_setup_theme', static function (): void {
    remove_action('wp_head', 'hello_elementor_add_description_meta_tag');
}, 100);

add_action('wp_head', static function (): void {
    if (!mm_is_coded_page() || defined('WPSEO_VERSION')) {
        return;
    }
    $description = mm_meta_description();
    $canonical = is_front_page() ? home_url('/') : (is_singular() ? get_permalink() : get_post_type_archive_link(get_query_var('post_type')));
    $title = wp_get_document_title();
    printf("\n<meta name=\"description\" content=\"%s\">", esc_attr($description));
    printf("\n<link rel=\"canonical\" href=\"%s\">", esc_url((string) $canonical));
    printf("\n<meta property=\"og:title\" content=\"%s\">", esc_attr($title));
    printf("\n<meta property=\"og:description\" content=\"%s\">", esc_attr($description));
    printf("\n<meta property=\"og:url\" content=\"%s\">", esc_url((string) $canonical));
    printf("\n<meta property=\"og:type\" content=\"%s\">\n", is_singular('post') ? 'article' : 'website');
}, 2);

add_action('wp_head', static function (): void {
    if (!is_singular('post') || defined('WPSEO_VERSION')) {
        return;
    }
    $image = get_the_post_thumbnail_url(get_queried_object_id(), 'full') ?: mm_page_image_url((string) get_post_field('post_name'), 'post');
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'Article',
        'headline' => get_the_title(),
        'description' => mm_meta_description(),
        'datePublished' => get_the_date(DATE_W3C),
        'dateModified' => get_the_modified_date(DATE_W3C),
        'image' => [$image],
        'author' => ['@type' => 'Organization', 'name' => 'Mubeen Migration'],
        'publisher' => ['@type' => 'Organization', 'name' => 'Mubeen Migration', 'url' => home_url('/')],
        'mainEntityOfPage' => get_permalink(),
    ];
    echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>';
}, 8);

function mm_handle_appointment(): void
{
    $redirect = wp_get_referer() ?: home_url('/contact-us/');
    $redirect = remove_query_arg(['appointment'], $redirect);

    if (!isset($_POST['mm_appointment_nonce']) ||
        !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['mm_appointment_nonce'])), 'mm_appointment')) {
        wp_safe_redirect(add_query_arg('appointment', 'invalid', $redirect) . '#appointment-form');
        exit;
    }

    if (!empty($_POST['website'])) {
        wp_safe_redirect(add_query_arg('appointment', 'sent', $redirect) . '#appointment-form');
        exit;
    }

    $name = sanitize_text_field(wp_unslash($_POST['name'] ?? ''));
    $email = sanitize_email(wp_unslash($_POST['email'] ?? ''));
    $phone = sanitize_text_field(wp_unslash($_POST['phone'] ?? ''));
    $country = sanitize_text_field(wp_unslash($_POST['country'] ?? ''));
    $service = sanitize_text_field(wp_unslash($_POST['service'] ?? ''));
    $message = sanitize_textarea_field(wp_unslash($_POST['message'] ?? ''));

    if (!$name || !is_email($email) || !$phone || !$country || !$service) {
        wp_safe_redirect(add_query_arg('appointment', 'missing', $redirect) . '#appointment-form');
        exit;
    }

    $subject = sprintf('Appointment enquiry: %s — %s', $service, $name);
    $body = "New website appointment enquiry\n\n" .
        "Name: {$name}\nEmail: {$email}\nPhone: {$phone}\nCountry/destination: {$country}\n" .
        "Service: {$service}\n\nMessage:\n{$message}\n";
    $sent = wp_mail(MM_CONTACT_EMAIL, $subject, $body, ['Reply-To: ' . $name . ' <' . $email . '>']);
    wp_safe_redirect(add_query_arg('appointment', $sent ? 'sent' : 'failed', $redirect) . '#appointment-form');
    exit;
}

add_action('admin_post_nopriv_mm_appointment', 'mm_handle_appointment');
add_action('admin_post_mm_appointment', 'mm_handle_appointment');

function mm_render_appointment_form(string $default_service = ''): void
{
    $status = sanitize_key(wp_unslash($_GET['appointment'] ?? ''));
    $messages = [
        'sent' => ['success', 'Thank you. Your appointment request has been emailed to our team.'],
        'missing' => ['error', 'Please complete all required fields and enter a valid email address.'],
        'invalid' => ['error', 'The form session expired. Please refresh the page and try again.'],
        'failed' => ['error', 'Your request could not be emailed. Please call +92 321 167 1000 or email us directly.'],
    ];
    if (isset($messages[$status])) {
        printf('<div class="mm-form-notice mm-form-%s" role="status">%s</div>', esc_attr($messages[$status][0]), esc_html($messages[$status][1]));
    }
    ?>
    <form id="appointment-form" class="mm-appointment-form" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post">
        <input type="hidden" name="action" value="mm_appointment">
        <?php wp_nonce_field('mm_appointment', 'mm_appointment_nonce'); ?>
        <div class="mm-honeypot" aria-hidden="true"><label>Website<input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>
        <div class="mm-form-grid">
            <label>Full name <span>*</span><input type="text" name="name" autocomplete="name" required></label>
            <label>Email address <span>*</span><input type="email" name="email" autocomplete="email" required></label>
            <label>Phone / WhatsApp <span>*</span><input type="tel" name="phone" autocomplete="tel" required></label>
            <label>Destination country <span>*</span><input type="text" name="country" required></label>
            <label class="mm-form-wide">Visa or service <span>*</span><input type="text" name="service" value="<?php echo esc_attr($default_service); ?>" required></label>
            <label class="mm-form-wide">Tell us about your plans<textarea name="message" rows="5"></textarea></label>
        </div>
        <button class="mm-button mm-button-dark" type="submit">Request an appointment</button>
        <p class="mm-form-note">Submitted securely to <a href="mailto:<?php echo esc_attr(MM_CONTACT_EMAIL); ?>"><?php echo esc_html(MM_CONTACT_EMAIL); ?></a>. We use your details only to respond to this enquiry.</p>
    </form>
    <?php
}

add_filter('template_include', static function (string $template): string {
    if (is_front_page()) {
        $home_template = __DIR__ . '/template-home.php';
        return is_readable($home_template) ? $home_template : $template;
    }

    if (is_page() || is_singular(['mm_service', 'mm_visa', 'mm_destination']) ||
        is_post_type_archive(['mm_service', 'mm_visa', 'mm_destination'])) {
        $site_template = __DIR__ . '/template-site.php';
        return is_readable($site_template) ? $site_template : $template;
    }

    if (is_singular('post')) {
        $blog_template = __DIR__ . '/template-blog.php';
        return is_readable($blog_template) ? $blog_template : $template;
    }

    return $template;
}, 99);

add_action('wp_enqueue_scripts', static function (): void {
    if (!(is_front_page() || is_page() || is_singular(['post', 'mm_service', 'mm_visa', 'mm_destination']) ||
        is_post_type_archive(['mm_service', 'mm_visa', 'mm_destination']))) {
        return;
    }

    $base_url = content_url('/mu-plugins/mubeen-home/assets');
    $css_file = __DIR__ . '/assets/home.css';
    $js_file  = __DIR__ . '/assets/home.js';

    wp_enqueue_style(
        'mubeen-home-fonts',
        'https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700&family=Montserrat:wght@600;700;800&display=swap',
        [],
        null
    );
    wp_enqueue_style('mubeen-home', $base_url . '/home.css', [], (string) filemtime($css_file));
    if (!is_front_page()) {
        $site_css = __DIR__ . '/assets/site.css';
        wp_enqueue_style('mubeen-site-pages', $base_url . '/site.css', ['mubeen-home'], (string) filemtime($site_css));
    }
    wp_enqueue_script('mubeen-home', $base_url . '/home.js', [], (string) filemtime($js_file), true);
}, 20);

add_filter('body_class', static function (array $classes): array {
    if (is_front_page() || is_page() || is_singular(['post', 'mm_service', 'mm_visa', 'mm_destination']) ||
        is_post_type_archive(['mm_service', 'mm_visa', 'mm_destination'])) {
        $classes[] = 'mm-coded-home';
    }
    return $classes;
});
