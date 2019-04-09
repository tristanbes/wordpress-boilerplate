<?php

if (!class_exists('Timber')) {
    add_action('admin_notices', function () {
        echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="'.esc_url(admin_url('plugins.php#timber')).'">'.esc_url(admin_url('plugins.php')).'</a></p></div>';
    });

    add_filter('template_include', function ($template) {
        return get_stylesheet_directory().'/no-timber.html';
    });

    return;
}

// Activate Twig caching.
if (class_exists('Timber') && WP_ENV === 'production') {
    Timber::$cache = true;
}

Timber::$dirname = ['templates', 'views'];

class Site extends TimberSite
{
    public function __construct()
    {
        add_theme_support('custom-logo');
        add_theme_support('title-tag');
        add_theme_support('post-thumbnails');
        add_theme_support('menus');
        add_theme_support('html5', ['comment-list', 'comment-form', 'search-form', 'gallery', 'caption']);

        add_filter('timber_context', [$this, 'add_to_context']);
        add_filter('get_custom_logo', [$this, 'get_logo_url']);

        add_image_size('small_banner', 576, 360, true);
        add_image_size('medium_banner', 768, 360, true);

        add_action('init', [$this, 'acf_add_options_page']);
        add_action('init', [$this, 'register_nav_menus']);
        add_action('init', [$this, 'theme_styles']);
        add_action('init', [$this, 'theme_scripts']);

        parent::__construct();
    }

    public function get_logo_url($url)
    {
        $custom_logo_id = get_theme_mod('custom_logo');
        $url            = wp_get_attachment_image_src($custom_logo_id);

        return $url[0];
    }

    public function acf_add_options_page()
    {
        if (function_exists('acf_add_options_page')) {
            acf_add_options_page();
            acf_add_options_sub_page('Footer');
        }
    }

    public function register_nav_menus()
    {
        if (function_exists('register_nav_menus')) {
            register_nav_menus([
                'header_menu' => 'Menu header',
            ]);
        }
    }

    public function add_to_context($context)
    {
        $context['professionnal_services'] = get_field('professional_services', 'options');
        $context['individual_services']    = get_field('individual_services', 'options');
        $context['footer_options']         = [
            'logo'      => get_field('logo', 'options'),
            'copyright' => get_field('copyright', 'options'),
        ];

        $args = [
            'depth' => 1,
        ];
        $context['header_menu']   = new TimberMenu('header_menu', $args);
        $context['footer_menu_1'] = new TimberMenu('footer_menu_1', $args);
        $context['footer_menu_2'] = new TimberMenu('footer_menu_2', $args);
        $context['footer_menu_3'] = new TimberMenu('footer_menu_3', $args);

        global $template;
        $context['template'] = basename($template, '.php');

        $this->custom_logo = get_custom_logo();
        $context['site']   = $this;

        $context['search_form'] = get_search_form(false);

        return $context;
    }

    public function theme_styles()
    {
        if ('wp-login.php' != $GLOBALS['pagenow'] && !is_admin()) {
            wp_register_style('style', get_template_directory_uri().'/dist/css/style.min.css');
            wp_enqueue_style('style');
            wp_register_style('vendors', get_template_directory_uri().'/dist/css/vendors.min.css');
            wp_enqueue_style('vendors');
        }
    }

    public function theme_scripts()
    {
        if ('wp-login.php' != $GLOBALS['pagenow'] && !is_admin()) {
            wp_register_script('vendors', get_template_directory_uri().'/dist/js/vendors.min.js', false, '', true);
            wp_enqueue_script('vendors');
            wp_register_script('scripts', get_template_directory_uri().'/dist/js/scripts.min.js', false, '', true);
            wp_enqueue_script('scripts');
        }
    }
}

new Site();