<?php
/**
 * Build the requested Destination -> Visa -> Visa List catalog.
 */
if (PHP_SAPI !== 'cli') {
    exit("CLI only.\n");
}

define('MUBEEN_BUILD_MODE', true);
defined('DB_HOST') || define('DB_HOST', '127.0.0.1:10005');
require dirname(__DIR__) . '/wp-load.php';

function mm_catalog_slug(string $text): string
{
    $text = str_replace(['&', '/', '–', '—', '(', ')', '.', ','], ['and', '-', '-', '-', '', '', '', ''], $text);
    return sanitize_title($text);
}

function mm_catalog_post(string $type, string $slug, string $title, string $content, string $excerpt, array $meta = [], int $menu_order = 0): int
{
    $existing = get_page_by_path($slug, OBJECT, $type);
    $data = [
        'post_type' => $type,
        'post_status' => 'publish',
        'post_name' => $slug,
        'post_title' => $title,
        'post_excerpt' => $excerpt,
        'post_content' => $content,
        'comment_status' => 'closed',
        'menu_order' => $menu_order,
    ];

    if ($existing instanceof WP_Post) {
        $data['ID'] = (int) $existing->ID;
        $id = wp_update_post($data, true);
    } else {
        $id = wp_insert_post($data, true);
    }
    if (is_wp_error($id)) {
        throw new RuntimeException($type . '/' . $slug . ': ' . $id->get_error_message());
    }

    foreach ($meta as $key => $value) {
        update_post_meta((int) $id, $key, $value);
    }
    update_post_meta((int) $id, '_mm_catalog_generated', '1');
    update_post_meta((int) $id, '_yoast_wpseo_title', $title . ' | Mubeen Migration');
    update_post_meta((int) $id, '_yoast_wpseo_metadesc', $excerpt);
    update_post_meta((int) $id, '_yoast_wpseo_focuskw', $title);
    return (int) $id;
}

function mm_catalog_service_content(string $title): string
{
    return '<p><strong>' . esc_html($title) . '</strong> is a main visa category. Use this page to compare related visa-list pages across destinations including the UK, United States, Australia and Canada.</p><p>Mubeen Migration provides international guidance on route selection, document planning, eligibility points and application-readiness review. Requirements change by country and individual circumstances, so every applicant should confirm the current official rules before submission.</p>';
}

function mm_catalog_visa_content(string $title, string $country, string $group): string
{
    return '<p><strong>' . esc_html($title) . '</strong> sits under the ' . esc_html($group) . ' category for ' . esc_html($country) . '. This guide explains the purpose of the route, the type of evidence usually considered and the preparation points applicants should review before moving forward.</p><h2>About this visa</h2><p>This visa route is generally used where the applicant’s purpose matches the category requirements, supporting evidence is available and the information across forms, history and documents is consistent. The exact eligibility test, allowed activities, length of stay and decision process depend on the destination authority.</p><h2>Preparation focus</h2><ul><li>Confirm that the route matches your real travel, study, work, family or immigration purpose.</li><li>Prepare identity, travel history and personal-background information accurately.</li><li>Organise financial, employment, education, sponsor or family evidence where relevant.</li><li>Review previous refusals, overstays, compliance issues or missing documents before applying.</li></ul><p>Mubeen Migration can support clients in Pakistan and internationally with case-focused consultation, document planning and final readiness review. No consultant can guarantee a visa outcome because decisions are made by the relevant authority.</p>';
}

$services = [
    'visit-visa' => 'Visit visas',
    'study-visa' => 'Study visas',
    'worker-visa' => 'Work & employment visas',
    'family-settlement-visas' => 'Family & settlement visas',
    'dependant-visas' => 'Dependant visas',
    'business-and-investment-visas' => 'Business & investment visas',
    'permanent-residence-pathways' => 'Permanent residence pathways',
    'citizenship-and-status' => 'Citizenship & status',
    'special-categories' => 'Special categories',
    'refusals-legal-remedies' => 'Refusals & legal remedies',
    'bridging-visas' => 'Bridging visas',
    'status-and-compliance' => 'Status & compliance',
    'rural-and-pilot-programs' => 'Rural & pilot programs',
    'quebec-immigration-programs' => 'Quebec immigration programs',
];

foreach ($services as $slug => $title) {
    mm_catalog_post('mm_service', $slug, $title, mm_catalog_service_content($title), 'Compare ' . $title . ' routes, eligibility points, documents and visa-list pages with Mubeen Migration.', ['_mm_service_slug' => $slug]);
}

$destinations = [
    'uk' => 'United Kingdom',
    'usa' => 'United States',
    'australia' => 'Australia',
    'canada' => 'Canada',
];

foreach ($destinations as $slug => $title) {
    mm_catalog_post('mm_destination', $slug, $title, '<p>Explore visa categories and individual visa-list pages for <strong>' . esc_html($title) . '</strong>. Mubeen Migration helps clients understand route options, document planning and application preparation from Pakistan and internationally.</p>', 'Explore ' . $title . ' visa categories, individual visa-list pages and immigration guidance from Mubeen Migration.', ['_mm_country' => $slug]);
}

$catalog = [
    'uk' => [
        'country' => 'United Kingdom',
        'groups' => [
            ['Visit Visas', 'visit-visa', ['Standard Visitor Visa', 'Tourism', 'Family & friends visit', 'Business meetings', 'Medical treatment', 'Marriage Visitor Visa', 'Permitted Paid Engagement (PPE) Visa', 'Transit Visa']],
            ['Study Visas', 'study-visa', ['Student Visa (formerly Tier 4)', 'Child Student Visa', 'Short-term Study Visa', 'English language courses (6–11 months)']],
            ['Work & Employment Visas', 'worker-visa', ['Skilled Worker Visa', 'Health and Care Worker Visa', 'Global Business Mobility Visas', 'Sole Representative Visa (Extensions)', 'Senior or Specialist Worker', 'Graduate Trainee', 'UK Expansion Worker', 'Service Supplier', 'Secondment Worker', 'Global Talent Visa', 'Scale-up Worker Visa', 'High Potential Individual (HPI) Visa', 'Temporary Worker Visas', 'Charity Worker', 'Creative Worker', 'Government Authorised Exchange', 'International Agreement', 'Religious Worker', 'Seasonal Worker']],
            ['Family & Settlement Visas', 'family-settlement-visas', ['Spouse / Partner Visa', 'Fiancé(e) / Proposed Civil Partner Visa', 'Child Dependant Visa', 'Parent Visa', 'Adult Dependant Relative Visa']],
            ['Dependant Visas', 'dependant-visas', ['Skilled Worker Dependant', 'Student Dependant', 'Global Talent Dependant', 'Health & Care Worker Dependant']],
            ['Business & Investment Visas', 'business-and-investment-visas', ['Innovator Founder Visa']],
            ['Settlement & Long Residence', 'permanent-residence-pathways', ['Indefinite Leave to Remain (ILR)', 'Long Residence (10-year route)']],
            ['UK Citizenship & Nationality', 'citizenship-and-status', ['British Citizenship (Naturalisation)', 'Registration as British Citizen', 'British Passport Applications']],
            ['Special Categories', 'special-categories', ['Returning Resident Visa', 'Ukraine Schemes']],
        ],
    ],
    'usa' => [
        'country' => 'United States',
        'groups' => [
            ['Visitor Visas (Non-Immigrant)', 'visit-visa', ['B-1 – Business Visitor', 'B-2 – Tourist / Family Visit / Medical Treatment', 'B-1/B-2 – Combined Business & Tourism']],
            ['Study & Exchange Visas', 'study-visa', ['F-1 – Academic Student', 'F-2 – Dependant of F-1', 'M-1 – Vocational / Technical Student', 'M-2 – Dependant of M-1', 'J-1 – Exchange Visitor', 'J-2 – Dependant of J-1']],
            ['Work & Employment Visas', 'worker-visa', ['H-1B – Specialty Occupation', 'H-2A – Temporary Agricultural Worker', 'H-2B – Temporary Non-Agricultural Worker', 'H-3 – Trainee', 'L-1A – Intra-Company Transfer Managers Executives', 'L-1B – Intra-Company Transfer Specialized Knowledge', 'O-1 – Extraordinary Ability', 'P-1 / P-2 / P-3 – Athletes, Artists & Entertainers', 'R-1 – Religious Worker']],
            ['Investor & Business Visas', 'business-and-investment-visas', ['E-1 – Treaty Trader', 'E-2 – Treaty Investor', 'EB-5 – Immigrant Investor Program']],
            ['Immediate Relative Visas', 'family-settlement-visas', ['IR-1 / CR-1 – Spouse of a U.S. Citizen', 'IR-2 / CR-2 – Unmarried Child under 21 of a U.S. Citizen', 'IR-5 – Parent of a U.S. Citizen']],
            ['Family Preference Visas', 'family-settlement-visas', ['F1 – Unmarried Sons and Daughters 21+ of U.S. Citizens', 'F2A – Spouses and Unmarried Children under 21 of Lawful Permanent Residents', 'F2B – Unmarried Sons and Daughters 21+ of Lawful Permanent Residents', 'F3 – Married Sons and Daughters of U.S. Citizens', 'F4 – Brothers and Sisters of U.S. Citizens']],
            ['Employment-Based Immigrant Visas', 'permanent-residence-pathways', ['EB-1 – Priority Workers', 'EB-2 – Professionals with Advanced Degrees', 'EB-3 – Skilled Workers & Professionals', 'EB-4 – Special Immigrants', 'EB-5 – Investors']],
            ['Special Purpose & Other Visas', 'special-categories', ['K-1 – Fiancé(e) of U.S. Citizen', 'K-3 – Spouse of U.S. Citizen']],
            ['U.S. Citizenship & Civil Services', 'citizenship-and-status', ['CRBA – Consular Report of Birth Abroad', 'U.S. Passport Services', 'Citizenship Certificate (N-600)', 'Naturalization (N-400)', 'Renunciation / Relinquishment of U.S. Citizenship']],
            ['Appeals & Legal Remedies', 'refusals-legal-remedies', ['Visa Refusal Review Guidance', 'Waiver of Ineligibility (601 / 212)', 'Administrative Processing (221g) Assistance', 'Re-application Strategy & Documentation Review']],
        ],
    ],
    'australia' => [
        'country' => 'Australia',
        'groups' => [
            ['Visitor & Short-Stay Visas', 'visit-visa', ['Visitor Visa (Subclass 600)']],
            ['Study & Training Visas', 'study-visa', ['Student Visa (Subclass 500)', 'Student Guardian Visa (Subclass 590)', 'Training Visa (Subclass 407)']],
            ['Points-Tested Skilled Visas', 'worker-visa', ['Skilled Independent Visa (Subclass 189)', 'Skilled Nominated Visa (Subclass 190)', 'Skilled Work Regional Provisional Visa (Subclass 491)']],
            ['Employer-Sponsored Visas', 'worker-visa', ['Temporary Skill Shortage TSS (Subclass 482)', 'Employer Nomination Scheme ENS (Subclass 186)', 'Regional Sponsored Migration Scheme RSMS (Subclass 187)']],
            ['Business & Investment Visas', 'business-and-investment-visas', ['Business Innovation & Investment Visa (Subclass 188)', 'Business Innovation & Investment Permanent Visa (Subclass 888)', 'Investor Visa (Subclass 891 / 892)']],
            ['Family & Partner Visas', 'family-settlement-visas', ['Partner Visa (Subclass 820 / 801) Onshore', 'Partner Visa (Subclass 309 / 100) Offshore', 'Parent Visas (Contributory & Non-Contributory)', 'Child Visa (Subclass 101 / 802)', 'Remaining Relative Visa', 'Carer Visa']],
            ['Bridging Visas', 'bridging-visas', ['Bridging Visa A (BVA) Subclass 010', 'Bridging Visa B (BVB) Subclass 020', 'Bridging Visa C (BVC) Subclass 030', 'Bridging Visa D (BVD) Subclass 041']],
            ['Other Visas', 'special-categories', ['Working Holiday Visa (Subclass 417)', 'Work and Holiday Visa (Subclass 462)', 'Medical Treatment Visa (Subclass 602)']],
            ['Permanent Residence (PR) Pathways', 'permanent-residence-pathways', ['Skilled Migration PR', 'Employer-Sponsored PR', 'Regional Migration PR', 'Partner & Family PR']],
            ['Citizenship', 'citizenship-and-status', ['Australian Citizenship by Conferral', 'Citizenship by Descent']],
            ['Appeals & Reviews', 'refusals-legal-remedies', ['AAT Administrative Appeals Tribunal Review', 'Visa Refusals & Cancellations', 'Ministerial Intervention Requests']],
        ],
    ],
    'canada' => [
        'country' => 'Canada',
        'groups' => [
            ['Visitor & Short-Term', 'visit-visa', ['Visitor Visa (Temporary Resident Visa TRV)', 'Super Visa (Parents & Grandparents)', 'Business Visitor Visa', 'Transit Visa']],
            ['Study', 'study-visa', ['Study Permit', 'Student Direct Stream (SDS)', 'Study Permit Extension', 'Post-Graduation Work Permit (PGWP)']],
            ['Work', 'worker-visa', ['Spousal Open Work Permit', 'LMIA-Based Work Permit', 'LMIA-Exempt Work Permits']],
            ['Family Sponsorship', 'family-settlement-visas', ['Spouse / Common-Law Partner Sponsorship', 'Dependent Child Sponsorship', 'Parents & Grandparents Program (PGP)']],
            ['Permanent Residence – Federal Government Programs', 'permanent-residence-pathways', ['Federal Skilled Worker Program (FSWP)', 'Federal Skilled Trades Program (FSTP)', 'Canadian Experience Class (CEC)']],
            ['Permanent Residence – Provincial Nominee Programs', 'permanent-residence-pathways', ['Ontario Immigrant Nominee Program (OINP)', 'British Columbia Provincial Nominee Program (BC PNP)', 'Alberta Advantage Immigration Program (AAIP)', 'Saskatchewan Immigrant Nominee Program (SINP)', 'Manitoba Provincial Nominee Program (MPNP)', 'Nova Scotia Nominee Program (NSNP)', 'New Brunswick PNP', 'Newfoundland & Labrador PNP', 'Prince Edward Island PNP', 'Yukon Nominee Program', 'Northwest Territories Nominee Program']],
            ['Quebec Immigration Programs', 'quebec-immigration-programs', ['Quebec Skilled Worker Program (QSWP)', 'Quebec Experience Program (PEQ)', 'Quebec Business Immigration Programs', 'Quebec Family Sponsorship']],
            ['Rural & Pilot Programs', 'rural-and-pilot-programs', ['Rural and Northern Immigration Pilot (RNIP)', 'Agri-Food Pilot', 'Caregiver Programs', 'Home Child Care Provider Pilot', 'Home Support Worker Pilot']],
            ['Citizenship & Status', 'citizenship-and-status', ['Permanent Resident Card (PR Card)', 'PR Card Renewal', 'Canadian Citizenship Application', 'Citizenship Certificate']],
            ['Refusals & Legal Remedies', 'refusals-legal-remedies', ['Administrative Review', 'Judicial Review Federal Court of Canada', 'Appeals before Immigration Appeal Division (IAD)', 'Reconsideration Requests', 'Procedural Fairness Letter (PFL) Responses']],
            ['Status & Compliance', 'status-and-compliance', ['Restoration of Status', 'Extension Applications', 'Misrepresentation Response', 'Inadmissibility Issues', 'Authorization to Return to Canada (ARC)']],
        ],
    ],
];

$created = 0;
foreach ($catalog as $country_key => $country_data) {
    $order = 0;
    foreach ($country_data['groups'] as [$group, $service_slug, $items]) {
        foreach ($items as $item) {
            $slug = mm_catalog_slug($country_key . '-' . $item);
            $excerpt = $item . ' guidance for ' . $country_data['country'] . ': eligibility direction, document planning and application-readiness support from Mubeen Migration.';
            mm_catalog_post(
                'mm_visa',
                $slug,
                $item,
                mm_catalog_visa_content($item, $country_data['country'], $group),
                $excerpt,
                [
                    '_mm_country' => $country_key,
                    '_mm_country_label' => $country_data['country'],
                    '_mm_group' => $group,
                    '_mm_service_slug' => $service_slug,
                    '_mm_service_title' => $services[$service_slug] ?? $group,
                ],
                ++$order
            );
            $created++;
        }
    }
}

flush_rewrite_rules(false);
echo "Visa catalog items processed: {$created}\n";
foreach (['mm_service', 'mm_visa', 'mm_destination'] as $type) {
    $counts = wp_count_posts($type);
    echo $type . ': ' . ($counts->publish ?? 0) . "\n";
}
