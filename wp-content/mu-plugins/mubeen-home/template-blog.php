<?php
/** Coded single-post template for Mubeen Migration insights. */
defined('ABSPATH') || exit;

$phone = MM_PHONE_URL;
$email = MM_CONTACT_EMAIL;
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class('mm-inner-page mm-blog-single'); ?>>
<?php wp_body_open(); ?>
<a class="mm-skip-link" href="#main-content">Skip to content</a>
<div class="mm-page">
    <?php mm_render_primary_header($phone, $email); ?>
    <main id="main-content">
        <?php while (have_posts()) : the_post(); ?>
            <header class="mm-blog-hero">
                <div class="mm-wrap mm-blog-hero-grid">
                    <div>
                        <div class="mm-breadcrumb"><a href="<?php echo esc_url(home_url('/')); ?>">Home</a><span>/</span><a href="<?php echo esc_url(home_url('/blogs/')); ?>">Blogs</a><span>/</span><span>Article</span></div>
                        <span class="mm-kicker">Immigration insight</span>
                        <h1><?php the_title(); ?></h1>
                        <p><?php echo esc_html(get_the_excerpt() ?: 'Practical guidance to support clearer visa and immigration preparation.'); ?></p>
                        <div class="mm-post-meta"><span><?php echo esc_html(get_the_date()); ?></span><span><?php echo esc_html((string) get_the_author()); ?></span><span><?php echo esc_html((string) ceil(str_word_count(wp_strip_all_tags((string) get_the_content())) / 220)); ?> min read</span></div>
                    </div>
                    <figure><?php if (has_post_thumbnail()) : the_post_thumbnail('large'); else : mm_render_page_image('mm-page-feature-image', get_the_title()); endif; ?></figure>
                </div>
            </header>
            <section class="mm-inner-section"><div class="mm-wrap mm-post-layout">
                <article class="mm-post-content">
                    <?php the_content(); ?>
                    <?php wp_link_pages(); ?>
                    <div class="mm-post-disclaimer"><strong>Important information</strong><p>This article provides general information and is not a guarantee of eligibility or outcome. Immigration rules can change; confirm current official requirements and obtain advice for your circumstances.</p></div>
                </article>
                <aside class="mm-side-card"><span>Discuss your plans</span><h2>Need case-focused direction?</h2><p>Book an online, phone or office consultation with Mubeen Migration.</p><a class="mm-button" href="<?php echo esc_url(home_url('/appointment/')); ?>">Book appointment</a><hr><b><?php echo esc_html(MM_PHONE_DISPLAY); ?></b><small><?php echo esc_html($email); ?></small></aside>
            </div></section>
            <section class="mm-inner-section mm-route-section"><div class="mm-wrap mm-post-navigation"><?php previous_post_link('<div><span>Previous article</span>%link</div>', '%title'); ?><?php next_post_link('<div><span>Next article</span>%link</div>', '%title'); ?></div></section>
        <?php endwhile; ?>
    </main>
    <?php mm_render_global_footer($phone, $email); ?>
    <a class="mm-mobile-call" href="<?php echo esc_url($phone); ?>">Call for consultation</a>
</div>
<?php wp_footer(); ?>
</body>
</html>
