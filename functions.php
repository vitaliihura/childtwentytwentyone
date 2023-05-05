<?php

/**
 * Scripts and Styles
 */
function childtwentytwentyone_enqueue_styles()
{
    // styles
    $parent_style = 'twentytwentyone-style';
    wp_enqueue_style($parent_style, get_template_directory_uri() . '/style.css');
    wp_enqueue_style('my-style', get_stylesheet_directory_uri() . '/style.css', array($parent_style), wp_get_theme()->get('Version'));
    wp_enqueue_style('main-style', get_stylesheet_directory_uri() . '/assets/front/css/main.css', array($parent_style), wp_get_theme()->get('Version'));

    // scripts
    wp_enqueue_script('jquery');
    wp_enqueue_script('main-script', get_stylesheet_directory_uri() . '/assets/front/js/main.js', array('jquery'), wp_get_theme()->get('Version'), true);
    wp_localize_script('main-script', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
}

add_action('wp_enqueue_scripts', 'childtwentytwentyone_enqueue_styles');


function childtwentytwentyone_add_services_settings_scripts()
{
    // styles
    wp_enqueue_style('my-main-admin-style', get_stylesheet_directory_uri() . '/assets/admin/css/main.css', array(), wp_get_theme()->get('Version'));

    // scripts
    wp_enqueue_script('jquery');
    wp_enqueue_script('my-main-admin-script', get_stylesheet_directory_uri() . '/assets/admin/js/main.js', array('jquery'), wp_get_theme()->get('Version'), true);
}

add_action('admin_enqueue_scripts', 'childtwentytwentyone_add_services_settings_scripts');


/**
 * Handling the add demo data event
 */
function childtwentytwentyone_add_services_test_data_ajax()
{
    ob_start();
    childtwentytwentyone_add_services_test_data();
    wp_send_json_success(ob_get_clean());
}

add_action('wp_ajax_add_services_test_data_ajax', 'childtwentytwentyone_add_services_test_data_ajax');


/**
 * Handler the event and add the shortcode if the home page is static
 */
function childtwentytwentyone_add_shortcode_on_home_ajax()
{
    ob_start();
    childtwentytwentyone_add_shortcode_on_home();
    wp_send_json_success(ob_get_clean());
}

add_action('wp_ajax_add_shortcode_on_home_ajax', 'childtwentytwentyone_add_shortcode_on_home_ajax');

function childtwentytwentyone_add_shortcode_on_home()
{
    $home_content = get_post_field('post_content', get_option('page_on_front'));
    $home_content .= '[loop type="services" title="Best posts"]';
    $update_post = array(
        'ID' => get_option('page_on_front'),
        'post_content' => $home_content,
    );
    wp_update_post($update_post);
}


/**
 * A shortcode is added if the main page is dynamic
 */
add_filter('the_content', 'add_services_loop_shortcode');
function add_services_loop_shortcode($content)
{
    if (is_front_page() && is_home()) {
        echo do_shortcode('[loop type="services" title="Best posts for dynamic front page"]');
    }
    return $content;
}


/**
 * Custom Post Types
 */
function childtwentytwentyone_create_services_post_type()
{
    $labels = array(
        'name' => __('Services', 'childtwentytwentyone'),
        'singular_name' => __('Service', 'childtwentytwentyone'),
        'add_new' => __('Add New', 'childtwentytwentyone'),
        'add_new_item' => __('Add New Service', 'childtwentytwentyone'),
        'edit_item' => __('Edit Service', 'childtwentytwentyone'),
        'new_item' => __('New Service', 'childtwentytwentyone'),
        'view_item' => __('View Service', 'childtwentytwentyone'),
        'search_items' => __('Search Services', 'childtwentytwentyone'),
        'not_found' => __('No services found', 'childtwentytwentyone'),
        'not_found_in_trash' => __('No services found in trash', 'childtwentytwentyone'),
        'parent_item_colon' => __('Parent Service:', 'childtwentytwentyone'),
        'menu_name' => __('Services', 'childtwentytwentyone'),
    );
    $args = array(
        'labels' => $labels,
        'description' => __('Description.', 'childtwentytwentyone'),
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'services'),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => 5,
        'supports' => array('title', 'editor', 'thumbnail'),
    );
    register_post_type('services', $args);
}

add_action('init', 'childtwentytwentyone_create_services_post_type');


/**
 * Add the Services Settings page to the Settings menu
 */
function childtwentytwentyone_add_services_settings_page()
{
    add_options_page(
        'Services Settings',
        'Services Settings',
        'manage_options',
        'services-settings',
        'childtwentytwentyone_services_settings_page'
    );
}

add_action('admin_menu', 'childtwentytwentyone_add_services_settings_page');

/**
 * Page Services Settings
 */
function childtwentytwentyone_services_settings_page()
{
    ?>
    <div class="wrap">
        <h1>Services Settings</h1>
        <form>
            <p>
                <strong>This will work if on the "Reading Settings" page for "Your homepage displays" will be selected
                    "A static page (select below)"</strong><br><br>
                <a href="#" class="button-primary add_shortcode_on_home_ajax">Add shortcode on home</a>
                <strong>[loop type="services" title="Best posts"]</strong>
            </p>
            <hr>
            <p>
                <a href="#" class="button-primary add_services_test_data_ajax">Add test data</a>
            </p>
            <p class="add_services_test_data_ajax_loading"></p>
        </form>
    </div>
    <?php
}

/**
 * 'Add test data' button click handler
 */
function childtwentytwentyone_add_services_test_data()
{
    $images = array(
        'https://look.com.ua/pic/202211/1920x1080/look.com.ua-398871.jpg',
        'https://look.com.ua/pic/201603/1920x1080/look.com.ua-154847.jpg',
        'https://look.com.ua/pic/202103/1920x1080/look.com.ua-375576.jpg',
        'https://look.com.ua/pic/202202/1920x1080/look.com.ua-383831.jpg',
        'https://look.com.ua/pic/202103/1920x1080/look.com.ua-375136.jpg',
        'https://look.com.ua/pic/202103/1920x1080/look.com.ua-375563.jpg',
        'https://look.com.ua/pic/202103/1920x1080/look.com.ua-375125.jpg',
        'https://look.com.ua/pic/202103/1920x1080/look.com.ua-375127.jpg',
        'https://look.com.ua/pic/202103/1920x1080/look.com.ua-375129.jpg',
        'https://look.com.ua/pic/202103/1920x1080/look.com.ua-375138.jpg',
        'https://look.com.ua/pic/202103/1920x1080/look.com.ua-375113.jpg',
        'https://look.com.ua/pic/202103/1920x1080/look.com.ua-375124.jpg',
        'https://look.com.ua/pic/202103/1920x1080/look.com.ua-375130.jpg',
        'https://look.com.ua/pic/202103/1920x1080/look.com.ua-375119.jpg',
        'https://look.com.ua/pic/202102/1920x1080/look.com.ua-374460.jpg',
        'https://look.com.ua/pic/202102/1920x1080/look.com.ua-374087.jpg',
        'https://look.com.ua/pic/202102/1920x1080/look.com.ua-374065.jpg',
        'https://look.com.ua/pic/202102/1920x1080/look.com.ua-373106.jpg',
        'https://look.com.ua/pic/202102/1920x1080/look.com.ua-373114.jpg',
        'https://look.com.ua/pic/202102/1920x1080/look.com.ua-373115.jpg',
        'https://look.com.ua/pic/202102/1920x1080/look.com.ua-372482.jpg',
        'https://look.com.ua/pic/202102/1920x1080/look.com.ua-372481.jpg',
        'https://look.com.ua/pic/202012/1920x1080/look.com.ua-369176.jpg',
        'https://look.com.ua/pic/202006/1920x1080/look.com.ua-356479.jpg',
        'https://look.com.ua/pic/201909/1920x1080/look.com.ua-354669.jpg',
        'https://look.com.ua/pic/201905/1920x1080/look.com.ua-347068.jpg',
        'https://look.com.ua/pic/201904/1920x1080/look.com.ua-343421.jpg',
        'https://look.com.ua/pic/201904/1920x1080/look.com.ua-343420.jpg',
        'https://look.com.ua/pic/201903/1920x1080/look.com.ua-333168.jpg',
        'https://look.com.ua/pic/201903/1920x1080/look.com.ua-333166.jpg'
    );


    for ($i = 1; $i <= 3; $i++) {
        $title = 'Service ' . $i;
        $content = 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using "Content here, content here", making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for "lorem ipsum" will uncover many web sites still in their infancy.';
        $excerpt = substr($content, 0, 30);
        $post_data = array(
            'post_title' => $title,
            'post_content' => $content,
            'post_excerpt' => $excerpt,
            'post_status' => 'publish',
            'post_type' => 'services'
        );
        $post_id = wp_insert_post($post_data);

        $image_url = $images[$i];
        $thumbnail_html = media_sideload_image($image_url, $post_id);
        preg_match('/<img.+src=[\'"](?P<src>.+?)[\'"].*>/i', $thumbnail_html, $match);
        $image_src = $match['src'];
        $thumbnail_url = $image_src;
        $thumbnail_id = attachment_url_to_postid($thumbnail_url);


        if (!is_wp_error($thumbnail_id)) {
            set_post_thumbnail($post_id, $thumbnail_id);
        }
    }
}

add_action('admin_post_add_services_test_data', 'childtwentytwentyone_add_services_test_data');


/**
 * Shortcode
 */
function childtwentytwentyone_loop_services($atts)
{
    $atts = shortcode_atts(array(
        'title' => 'Best posts',
        'type' => 'services',
    ), $atts, 'loop');

    $args = array(
        'post_type' => $atts['type'],
        'post_status' => 'publish',
        'posts_per_page' => 10,
        'paged' => 1,
    );

    $query = new WP_Query($args);
    $total_pages = $query->max_num_pages;

    $output = '';

    if ($query->have_posts()) {
        $output .= '<div class="loop-services">';
        $output .= '<h2>' . $atts['title'] . '</h2>';
        $output .= '<ul>';

        while ($query->have_posts()) {
            $query->the_post();
            $output .= '<li>';
            $output .= '<div class="thumbnail">' . get_the_post_thumbnail() . '</div>';
            $output .= '<h3 class="title">' . get_the_title() . '</h3>';
            $output .= '<div class="excerpt">' . get_the_excerpt() . '</div>';
            $output .= '<a class="link" href="' . get_permalink() . '">Read more</a>';
            $output .= '</li>';
        }

        $output .= '</ul>';
        $output .= '</div>';

        wp_reset_postdata();

        $output .= '<div class="load-more-wrap">';
        $output .= '<div class="load-line"><span class="line"></span></div>';
        $output .= '<a href="#" class="load-more-button" data-type="' . $atts['type'] . '" data-paged="2" data-all-page="' . $total_pages . '">Load more</a>';
        $output .= '</div>';
    }

    return $output;
}

add_shortcode('loop', 'childtwentytwentyone_loop_services');


/**
 * Ajax handler for loading posts for Shortcode Loop
 */
function childtwentytwentyone_load_posts()
{
    $paged = $_POST['paged'];
    $type = $_POST['type'];

    $args = array(
        'post_type' => $type,
        'post_status' => 'publish',
        'paged' => $paged,
        'posts_per_page' => 10,
    );

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            echo '<li>';
            echo '<div class="thumbnail">' . get_the_post_thumbnail() . '</div>';
            echo '<h3 class="title">' . get_the_title() . '</h3>';
            echo '<div class="excerpt">' . get_the_excerpt() . '</div>';
            echo '<a class="link" href="' . get_permalink() . '">Read more</a>';
            echo '</li>';
        }

        wp_reset_postdata();
    }

    die();
}

add_action('wp_ajax_nopriv_childtwentytwentyone_load_posts', 'childtwentytwentyone_load_posts');
add_action('wp_ajax_childtwentytwentyone_load_posts', 'childtwentytwentyone_load_posts');
