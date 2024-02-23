<?php
/*
Plugin Name: Takeoff VCS loader
Description: This plugin is meant to be used with the takeoff theme. It will parse CSV file and load data into the database. CAUTION: All data will be deleted as new CSV file is loaded.
Version: 0.1.0
Author: Egor Demeshko
Author URI: https://t.me/egor_demeshko
Text Domain: to_takeoff
*/

if (!defined('ABSPATH')) exit;

include_once __DIR__ . '/src/php/processLoading.php';

define('URL_PATH', plugin_dir_url(__FILE__));
define('PATH', plugin_dir_path(__FILE__));


class Takeoff
{


    public function __construct()
    {
        add_action('admin_menu', [$this, 'init']);

        //register rest api in wordpress
        add_action('rest_api_init', function () {
            register_rest_route('takeoff/v1', '/process_loading', [
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => [$this, 'process_loading'],
                //'permission_callback' => [$this, 'check_permission'],
            ]);
        });
    }


    public function init()
    {
        add_options_page('CSV Loader', 'CSV Loader', 'manage_options', 'takeoff-csvloader', [$this, 'render_page']);
        add_action('admin_init', [$this, 'add_scripts']);
    }


    public function check_permission()
    {

        if (!current_user_can('publish_posts')) {
            return new WP_Error('rest_forbidden', 'You are not allowed to access this resource.', ['status' => 401]);
        }
        return true;
    }


    public function process_loading($request)
    {
        ProcessFile::processRequest($request);
    }


    public function render_page()
    {
        include __DIR__ . '/views/main.php';
    }


    public function add_scripts()
    {
        wp_enqueue_script('takeoff-csvloader', plugins_url('src/js/takeoff_csvloader.js', __FILE__), [], '1.0.0', true);
    }
}


$takeoff = new Takeoff();
