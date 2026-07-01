<?php
/**
 * Mubeen Migration coded homepage.
 *
 * Content is intentionally kept in simple arrays at the top of this file so
 * future text, links, and cards can be changed without touching the layout.
 */

defined('ABSPATH') || exit;

$phone_display = MM_PHONE_DISPLAY;
$phone_url     = MM_PHONE_URL;
$email         = MM_CONTACT_EMAIL;

$services = [
    ['01', 'Visit visas', 'Tourism, family visits, business travel and other temporary visit purposes.', '/Visa/visit-visa/', 'passport'],
    ['02', 'Study visas', 'Study plans, financial evidence, application consistency and document preparation.', '/Visa/study-visa/', 'book'],
    ['03', 'Work visas', 'Route guidance for eligible professionals, skilled workers and employer-supported cases.', '/Visa/worker-visa/', 'briefcase'],
    ['04', 'Family routes', 'Structured preparation for partner, dependant, parent and eligible family pathways.', '/Visa/family-settlement-visas/', 'people'],
    ['05', 'Business migration', 'Initial direction and documentation planning for business and investment categories.', '/Visa/business-and-investment-visas/', 'chart'],
    ['06', 'Refusal guidance', 'Review refusal concerns and develop a clearer evidence strategy for your next step.', '/Visa/refusals-legal-remedies/', 'shield'],
];

$destinations = [
    ['UK', 'United Kingdom', 'Study · Visit · Work · Family', '/destination/uk/', '#d4a84b'],
    ['CA', 'Canada', 'Temporary and permanent routes', '/destination/canada/', '#bd9239'],
    ['AU', 'Australia', 'Visit · Study · Skilled routes', '/destination/australia/', '#e1be67'],
    ['NZ', 'New Zealand', 'Visit · Study · Work', '/destination/new-zealand/', '#a77b25'],
    ['US', 'United States', 'Visit · Study · Family · Business', '/destination/usa/', '#c99d43'],
];

$faqs = [
    ['Which countries does Mubeen Migration assist with?', 'We provide guidance for several major destinations, including the United Kingdom, Canada, Australia, New Zealand, the United States and selected European routes, depending on the service and your circumstances.'],
    ['Can you guarantee that my visa will be approved?', 'No consultancy can guarantee a visa decision. The relevant embassy or immigration authority makes the final decision. Our role is to help you understand the route and prepare a complete, consistent application.'],
    ['What should I bring to an initial consultation?', 'Bring your passport, relevant education or employment information, travel history, any previous refusal letters and a clear explanation of your intended destination and purpose.'],
    ['Can you review a previous visa refusal?', 'Yes. We can review the refusal concerns and available supporting information, then explain possible next steps where a further application or review may be appropriate.'],
    ['Do you work with clients outside Lahore?', 'Yes. We offer phone and online consultations for clients throughout Pakistan and internationally, alongside office consultations in Lahore.'],
    ['How can I book an appointment?', 'Call +92 321 167 1000, email enquiries@mubeenmigration.com or use our appointment form.'],
];

function mm_home_icon(string $name): string
{
    $icons = [
        'passport'  => '<path d="M7 3h10a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2Z"/><circle cx="12" cy="10" r="3"/><path d="M9.5 15h5M12 7v6M9.2 9h5.6"/>',
        'book'      => '<path d="M4 5.5A2.5 2.5 0 0 1 6.5 3H11v17H6.5A2.5 2.5 0 0 0 4 22V5.5ZM20 5.5A2.5 2.5 0 0 0 17.5 3H13v17h4.5A2.5 2.5 0 0 1 20 22V5.5Z"/>',
        'briefcase' => '<rect x="3" y="7" width="18" height="13" rx="2"/><path d="M8 7V5a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2M3 12h18M10 12v2h4v-2"/>',
        'people'    => '<circle cx="9" cy="8" r="3"/><path d="M3 20v-2a5 5 0 0 1 5-5h2a5 5 0 0 1 5 5v2M16 4.2a3 3 0 0 1 0 5.6M17 13a5 5 0 0 1 4 4.9V20"/>',
        'chart'     => '<path d="M4 20V10M10 20V4M16 20v-7M22 20H2"/>',
        'shield'    => '<path d="M12 22s8-3.5 8-10V5l-8-3-8 3v7c0 6.5 8 10 8 10Z"/><path d="m9 12 2 2 4-5"/>',
    ];
    return '<svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">' . ($icons[$name] ?? $icons['passport']) . '</svg>';
}
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="mm-skip-link" href="#main-content">Skip to content</a>

<div class="mm-page">
    <?php mm_render_primary_header($phone_url, $email); ?>

    <main id="main-content">
        <section class="mm-hero">
            <div class="mm-hero-grid mm-wrap">
                <div class="mm-hero-copy">
                    <span class="mm-kicker">International visa &amp; immigration consultants</span>
                    <h1>Global ambitions deserve a <em>clear direction.</em></h1>
                    <p>International guidance for visit, study, work, family, settlement and business visa routes—built around your circumstances, wherever you are based.</p>
                    <div class="mm-actions">
                        <a class="mm-button" href="<?php echo esc_url(home_url('/contact-us/#appointment-form')); ?>">Book a consultation <span aria-hidden="true">→</span></a>
                        <a class="mm-button mm-button-outline" href="#services">Explore services</a>
                    </div>
                    <div class="mm-proof">
                        <span><b>✓</b> Case-focused consultation</span>
                        <span><b>✓</b> Clear document guidance</span>
                        <span><b>✓</b> Online support worldwide</span>
                    </div>
                </div>
                <aside class="mm-consult-card" aria-label="Consultation steps">
                    <span class="mm-kicker">Start with clarity</span>
                    <h2>Plan your next step</h2>
                    <p>Share your destination, purpose and current circumstances. We will help identify the right direction for your case.</p>
                    <ol>
                        <li><span>01</span> Initial profile discussion</li>
                        <li><span>02</span> Visa route and evidence review</li>
                        <li><span>03</span> Clear preparation roadmap</li>
                    </ol>
                    <a href="<?php echo esc_url($phone_url); ?>">Call <?php echo esc_html($phone_display); ?> <span aria-hidden="true">→</span></a>
                    <div class="mm-card-badge"><strong>International reach</strong><small>Online and office consultations</small></div>
                </aside>
            </div>
        </section>

        <section class="mm-stat-strip" aria-label="Service principles">
            <div class="mm-wrap mm-stats">
                <div><strong>1-to-1</strong><span>Case-focused consultation</span></div>
                <div><strong>Clear</strong><span>Route and document guidance</span></div>
                <div><strong>Careful</strong><span>Consistency-focused review</span></div>
                <div><strong>Worldwide</strong><span>Remote consultation access</span></div>
            </div>
        </section>

        <section class="mm-section mm-section-soft" id="services">
            <div class="mm-wrap">
                <div class="mm-section-heading">
                    <div><span class="mm-kicker">How we can help</span><h2>Immigration services built around your circumstances</h2></div>
                    <p>Every destination and visa category has different requirements. We focus on relevant evidence, accurate information and a logical application structure.</p>
                </div>
                <div class="mm-service-grid">
                    <?php foreach ($services as [$number, $title, $description, $url, $icon]) : ?>
                        <article class="mm-service-card">
                            <?php $service_image = mm_page_image_data(trim($url, '/'), 'mm_service'); ?>
                            <!-- <img class="mm-service-media" src="<?php echo esc_url($service_image['url']); ?>" alt="<?php echo esc_attr($service_image['alt'] ?: $title); ?>" loading="lazy"> -->
                            <span class="mm-card-number"><?php echo esc_html($number); ?></span>
                            <div class="mm-service-icon"><?php echo mm_home_icon($icon); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
                            <h3><?php echo esc_html($title); ?></h3>
                            <p><?php echo esc_html($description); ?></p>
                            <a class="mm-text-link" href="<?php echo esc_url(home_url($url)); ?>">Explore service <span aria-hidden="true">→</span></a>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <section class="mm-section mm-section-dark" id="destinations">
            <div class="mm-wrap">
                <div class="mm-section-heading">
                    <div><span class="mm-kicker">Destinations</span><h2>Plan for the country that fits your goal</h2></div>
                    <p>Explore destination-specific routes for travel, education, work, family and longer-term migration.</p>
                </div>
                <div class="mm-destination-grid">
                    <?php foreach ($destinations as [$code, $country, $routes, $url, $color]) : ?>
                        <a class="mm-country" style="--country-accent:<?php echo esc_attr($color); ?>" href="<?php echo esc_url(home_url($url)); ?>">
                            <?php $destination_image = mm_page_image_data(trim($url, '/'), 'mm_destination'); ?>
                            <img src="<?php echo esc_url($destination_image['url']); ?>" alt="<?php echo esc_attr($destination_image['alt'] ?: $country); ?>" loading="lazy">
                            <span class="mm-country-code"><?php echo esc_html($code); ?></span>
                            <strong><?php echo esc_html($country); ?></strong>
                            <small><?php echo esc_html($routes); ?></small>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <section class="mm-section" id="about">
            <div class="mm-wrap mm-about-grid">
                <div class="mm-about-visual">
                    <?php $about_image = mm_page_image_data('about-us consultation consultancy', 'page'); ?>
                    <img class="mm-about-photo" src="<?php echo esc_url($about_image['url']); ?>" alt="<?php echo esc_attr($about_image['alt'] ?: 'Mubeen Migration consultation'); ?>" loading="lazy">
                    <div class="mm-visual-label"><span>Mubeen Migration</span><span>Pakistan · Worldwide</span></div>
                    <div class="mm-compass" aria-hidden="true"><span>N</span><i></i></div>
                    <blockquote>“A strong application starts with a clear route, consistent information and evidence that is easy to understand.”</blockquote>
                </div>
                <div class="mm-about-copy">
                    <span class="mm-kicker">A more considered approach</span>
                    <h2>Professional guidance without unnecessary confusion</h2>
                    <p>Immigration applications cross borders, but good preparation remains personal. We support clients in Pakistan and overseas by explaining the process, organising relevant documents and presenting circumstances consistently.</p>
                    <div class="mm-feature-grid">
                        <div><strong>Route assessment</strong><span>Identify a suitable category for your objective.</span></div>
                        <div><strong>Evidence planning</strong><span>Build an organised document checklist.</span></div>
                        <div><strong>Consistency review</strong><span>Check dates, history and supporting details.</span></div>
                        <div><strong>Submission readiness</strong><span>Complete a final preparation review.</span></div>
                    </div>
                    <a class="mm-button mm-button-dark" href="<?php echo esc_url($phone_url); ?>">Speak with our team</a>
                </div>
            </div>
        </section>

        <section class="mm-section mm-home-company" aria-labelledby="mm-home-company-title">
            <div class="mm-wrap">
                <div class="mm-home-company-intro">
                    <span class="mm-kicker">Who we are</span>
                    <h2 id="mm-home-company-title">Mubeen Migration: professional guidance for important international decisions</h2>
                    <p>Mubeen Migration provides professional visa and immigration consultancy services for individuals, families, students, and skilled professionals in Pakistan. Our experienced team offers clear guidance for study visas, visit visas, work permits, family visas, and immigration pathways for the UK, Canada, Australia, USA, Europe, and New Zealand. At Mubeen Migration, we focus on accurate documentation, transparent advice, and step by step support to help clients prepare strong applications with confidence. Whether you need student visa assistance, business visa guidance, or reliable immigration consultants in Lahore, Mubeen Migration delivers trusted support designed to make your visa journey smooth, simple, and successful.</p>
                </div>
                <div class="mm-home-company-grid">
                    <article>
                        <span>About Mubeen Migration</span>
                        <h3>Clear direction from the first conversation</h3>
                        <p>Mubeen Migration is an immigration consultancy supporting clients in Lahore, throughout Pakistan and internationally through office, telephone and online consultations. We begin by understanding the destination, purpose, personal background and timing before discussing a suitable route.</p>
                        <p>Our work covers visa-category guidance, eligibility direction, document planning, application preparation and consistency reviews. We explain what information is relevant, what requires further evidence and where official rules or individual circumstances may affect the next step.</p>
                    </article>
                    <article class="mm-home-company-focus">
                        <span>What clients can expect</span>
                        <ul>
                            <li><b>Personal route review</b><small>Guidance shaped around your destination and genuine objective.</small></li>
                            <li><b>Organised documentation</b><small>A focused evidence plan that connects documents to application requirements.</small></li>
                            <li><b>Transparent communication</b><small>Clear explanations about preparation, limitations and practical next steps.</small></li>
                            <li><b>International access</b><small>Consultations for clients in Pakistan and overseas.</small></li>
                        </ul>
                        <a class="mm-button mm-button-dark" href="<?php echo esc_url(home_url('/about-us/')); ?>">Learn more about us</a>
                    </article>
                </div>
            </div>
        </section>

        <section class="mm-section mm-section-dark" id="process">
            <div class="mm-wrap">
                <div class="mm-section-heading">
                    <div><span class="mm-kicker">Our process</span><h2>From first conversation to a prepared application</h2></div>
                    <p>A structured process keeps important details visible and reduces avoidable confusion.</p>
                </div>
                <div class="mm-process-grid">
                    <article><b>STEP 01</b><h3>Initial consultation</h3><p>We discuss your destination, purpose, background and immediate questions.</p></article>
                    <article><b>STEP 02</b><h3>Route review</h3><p>Potential pathways and key eligibility considerations are explained clearly.</p></article>
                    <article><b>STEP 03</b><h3>Document planning</h3><p>You receive a focused evidence checklist and preparation direction.</p></article>
                    <article><b>STEP 04</b><h3>Final review</h3><p>Forms and documents are checked for completeness and consistency.</p></article>
                </div>
            </div>
        </section>

        <section class="mm-section mm-section-soft">
            <div class="mm-wrap">
                <div class="mm-section-heading">
                    <div><span class="mm-kicker">Our commitment</span><h2>Clear communication at every stage</h2></div>
                    <p>Good preparation depends on accurate information, realistic expectations and responsive support.</p>
                </div>
                <div class="mm-value-grid">
                    <article><span>01</span><h3>Straightforward advice</h3><p>We explain routes, requirements and limitations in plain language.</p></article>
                    <article><span>02</span><h3>Relevant preparation</h3><p>Your checklist focuses on evidence that matters for your circumstances.</p></article>
                    <article><span>03</span><h3>Responsible guidance</h3><p>We do not promise outcomes controlled by immigration authorities.</p></article>
                </div>
            </div>
        </section>

        <section class="mm-section" id="faq">
            <div class="mm-wrap mm-faq-grid">
                <div class="mm-faq-intro">
                    <span class="mm-kicker">Common questions</span>
                    <h2>Before you book a consultation</h2>
                    <p>These answers provide general information for local and international clients. Requirements and outcomes depend on the destination, category and individual circumstances.</p>
                    <a class="mm-text-link" href="<?php echo esc_url($phone_url); ?>">Have another question? Call us <span aria-hidden="true">→</span></a>
                </div>
                <div class="mm-accordion">
                    <?php foreach ($faqs as $index => [$question, $answer]) : ?>
                        <details<?php echo 0 === $index ? ' open' : ''; ?>>
                            <summary><?php echo esc_html($question); ?></summary>
                            <p><?php echo esc_html($answer); ?></p>
                        </details>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <section class="mm-cta">
            <div class="mm-wrap">
                <div class="mm-cta-box">
                    <div><span class="mm-kicker">Ready when you are</span><h2>Start with a clear conversation about your plans.</h2><p>Tell us where you want to go and what you want to achieve.</p></div>
                    <a class="mm-button mm-button-dark" href="<?php echo esc_url($phone_url); ?>">Call <?php echo esc_html($phone_display); ?></a>
                </div>
            </div>
        </section>
    </main>

    <?php mm_render_global_footer($phone_url, $email); ?>
    <?php if (false) : // Legacy footer retained temporarily; global footer is used above. ?>
    <footer class="mm-footer">
        <div class="mm-wrap">
            <div class="mm-footer-grid">
                <div class="mm-footer-about">
                    <a class="mm-brand" href="<?php echo esc_url(home_url('/')); ?>"><?php echo mm_brand_logo(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
                    <p>International visa and immigration guidance focused on clear routes, relevant documentation and consistent preparation.</p>
                    <?php mm_render_social_links(); ?>
                </div>
                <div><h3>Services</h3><a href="<?php echo esc_url(home_url('/Visa/visit-visa/')); ?>">Visit visas</a><a href="<?php echo esc_url(home_url('/Visa/study-visa/')); ?>">Study visas</a><a href="<?php echo esc_url(home_url('/Visa/worker-visa/')); ?>">Work visas</a><a href="<?php echo esc_url(home_url('/Visa/family-settlement-visas/')); ?>">Family routes</a></div>
                <div><h3>Company</h3><a href="<?php echo esc_url(home_url('/about-us/')); ?>">About us</a><a href="<?php echo esc_url(home_url('/work-process/')); ?>">Our process</a><a href="<?php echo esc_url(home_url('/faqs/')); ?>">FAQs</a><a href="<?php echo esc_url(home_url('/contact-us/')); ?>">Contact us</a></div>
                <div><h3>Contact</h3><a href="<?php echo esc_url($phone_url); ?>"><?php echo esc_html($phone_display); ?></a><a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a><p>Online consultations worldwide<br>Office: 4th Floor, 51 CCA, DHA Phase 5, Lahore</p></div>
            </div>
            <div class="mm-footer-bottom"><span>© <?php echo esc_html(wp_date('Y')); ?> Mubeen Migration. All rights reserved.</span><span>Visa decisions are made by the relevant authorities.</span></div>
        </div>
    </footer>
    <?php endif; ?>
    <a class="mm-mobile-call" href="<?php echo esc_url($phone_url); ?>">Call for consultation</a>
</div>

<script type="application/ld+json"><?php echo wp_json_encode([
    '@context' => 'https://schema.org', '@type' => 'LocalBusiness', '@id' => home_url('/#localbusiness'),
    'name' => 'Mubeen Migration', 'url' => home_url('/'), 'email' => $email, 'telephone' => '+92-321-1671000',
    'address' => ['@type' => 'PostalAddress', 'streetAddress' => '4th Floor, 51 CCA, DHA Phase 5', 'addressLocality' => 'Lahore', 'postalCode' => '54000', 'addressCountry' => 'PK'],
    'areaServed' => 'Worldwide', 'sameAs' => array_values(mm_social_links()),
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></script>
<?php wp_footer(); ?>
</body>
</html>
