<?php
/**
 * @package WordPress
 * @subpackage HTML5-Reset-WordPress-Theme
 * @since HTML5 Reset 2.0
 */
 get_header(); ?>

    <?php
    $featured_term_id = get_category_by_slug('featured')->term_id;
    $lead_term_id = get_category_by_slug('lead')->term_id;

    // Get all posts that are promoted to front page
    $featured_posts = get_posts(array(
        'post__in' => get_option('sticky_posts')
    ));

    // The hero post
    $sticky_posts = get_posts(array(
        'cat' => $lead_term_id,
        'ignore_sticky_posts' => 1
    ));
    $post = $sticky_posts[0];
    ?>

    <div class="row">
        <div class="medium-12 columns hero-wrapper">
            <a class="hero-image" href="<?php echo the_permalink(); ?>" style="background: url('<?php echo get_post_thumbnail_url('homepage-hero'); ?>') 0 0/cover no-repeat"></a>
            <div class="hero-post">
                <?php
                $categories = get_post_categories($post, array('featured', 'sponsored', 'lead'));
                foreach ($categories as $category) { ?>
                    <div class="topics-tag-normal <?php echo $category->slug; ?>">
                        <a href="#"><?php echo $category->name; ?></a>
                    </div>
                <?php
                } ?>

                <div class="hero-headline-container">
                    <div class="hero-headline">
                        <a href="<?php the_permalink(); ?>"><h1><?php echo one_of(simple_fields_fieldgroup('short_title'), get_the_title()); ?></h1></a>
                    </div>
                    <div class="lower" data-equalizer="">
                        <script>window.shareUrl = '<?php the_permalink(); ?>';</script>
                        <?php TheOpenStandardSocial::share_links(); ?>

                        <div class="hero-headline-description" data-equalizer-watch="">
                            <?php the_excerpt(); ?>
                            <ul class="inline-list">
                                <?php
                                $tags = get_the_tags();
                                foreach ($tags as $tag) { ?>
                                    <li class="issues-tag"><a href="<?php TheOpenStandardIssues::the_issues_link($tag->slug); ?>" class="issues-<?php echo $tag->slug; ?>"><?php echo $tag->name; ?></a></li>
                                <?php
                                } ?>                            
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>    

    <?php $featured_term_ids = array(); ?>
        
    <!-- FEATURED ARTICLES BY TOPIC -->
    <section class="featured">
        <div class="row">
            <div class="medium-12 columns">
                <h2>Featured Articles by Topic</h2>
                <ul class="medium-block-grid-5">
                    <?php 
                    $categories = array(
                        get_term_by('slug', 'live', 'category'),
                        get_term_by('slug', 'learn', 'category'),
                        get_term_by('slug', 'innovate', 'category'),
                        get_term_by('slug', 'engage', 'category'),
                        get_term_by('slug', 'opinion', 'category')
                    );

                    $ordered_featured_posts = array();
                    
                    foreach ($categories as $category) {
                        foreach ($featured_posts as $i => $featured_post) {
                            $featured_post_categories = get_post_categories($featured_post, array('featured', 'lead', 'sponsored'));
                            
                            $featured_post->category_slugs = array();
                            $featured_post->categories = $featured_post_categories;

                            foreach ($featured_post_categories as $featured_post_category) {
                                $featured_post->category_slugs[] = $featured_post_category->slug;
                            }

                            if (in_array($category->slug, $featured_post->category_slugs)) {
                                $ordered_featured_posts[] = $featured_post;
                                unset($featured_posts[$i]);
                                break;
                            }
                        }
                    }

                    foreach ($ordered_featured_posts as $post) {
                        $category = get_post_categories($post, array('featured', 'lead'), 1);
                        // Only show one post per category.
                        if (empty($category) || in_array($category->term_id, $featured_term_ids)):
                            continue;
                        else:
                            $featured_term_ids[] = $category->term_id;
                        endif; ?>

                        <li class="featured-articles-item">  
                            <div class="topics-tag-normal <?php echo $category->slug; ?>">
                                <a href="<?php echo get_category_link($category->term_id); ?>"><?php echo $category->name; ?></a>
                            </div>
                            <a href="<?php the_permalink(); ?>">
                                <img src="<?php echo get_post_thumbnail_url('homepage-featured'); ?>" />
                                <div class="<?php echo has_category('sponsored') ? 'sponsored-content-container' : ''; ?>">
                                <h3><?php echo one_of(simple_fields_fieldgroup('short_title'), get_the_title()); ?></h3>
                                    <?php
                                    if (has_category('sponsored')) { ?>
                                        <p class="sponsored-content">Sponsored</p>
                                    <?php
                                    } ?>
                                </div>
                            </a>
                        </li>                
                    <?php 
                    }; ?>
                </ul>
            </div>
        </div>
    </section>

    <div class="row">
        <div class="medium-12 columns">
           <hr>
        </div>
    </div>

    <section class="body">
        <div class="row">
            <!-- RECENT ARTICLES -->
            <div class="medium-8 columns">
                <div class="recent-articles">
                    <a href="#"><h4>Recent Articles</h4></a>
                    
                    <?php
                    // Get all recent posts not in the Featured category.
                    $options = array('category__not_in' => array($featured_term_id, $lead_term_id));
                    $recent_posts = get_posts($options);
                    ?>
                    <ul>
                        <?php
                        foreach ($recent_posts as $post) { ?>

                            <li class="recent-articles-item">
                                <?php if (has_post_thumbnail()) { ?>
                                <div class="thumbnail">
                                    <?php the_post_thumbnail('thumbnail'); ?>
                                </div>
                                <?php } ?>

                                <a href="<?php the_permalink(); ?>"><h3><?php echo one_of(simple_fields_fieldgroup('short_title'), get_the_title()); ?></h3></a>
                                <p><?php the_excerpt(); ?></p>
                                <p>
                                    <?php
                                    $categories = get_post_categories($post);
                                    foreach ($categories as $category) { ?>
                                        <a href="<?php echo get_category_link($category->term_id); ?>" class="topics-tag-minimal <?php echo $category->slug; ?>"><?php echo $category->name; ?></a>
                                    <?php
                                    } ?>
                                    <span class="timestamp"><?php echo human_time_diff(get_the_time('U'), current_time('timestamp')) . ' ago'; ?></span>
                                </p>
                            </li>

                        <?php
                        }; ?>
                    </ul>
                </div>
            </div>
        </div>
    </section>

<?php get_footer(); ?>
