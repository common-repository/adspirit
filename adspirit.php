<?php
defined('ABSPATH') or die('No script kiddies please!');

/*
Plugin Name: AdSpirit AdServer Banner Manager
Description: <strong>Inserting banner ads</strong> into your website has never been easier. Use the AdSpirit AdServer and our free plugin in order to manage, report and deliver banners, popups, layers and other forms of online advertising into your website. Simply add a text like “[adspirit 123]” into your content and the Plugin will automatically insert the corresponding banner ads at this place. In order to use this plugin you need to have an active account of the <a href="http://www.adspirit.com">AdSpirit AdServer</a>.
Version: 4.7
Author: AdSpirit
Author URI: http://www.adspirit.com
License: GPL2
*/

require_once dirname(__FILE__) . '/AdspiritBanners.php';
require_once dirname(__FILE__) . '/AdspiritCodeParser.php';
require_once dirname(__FILE__) . '/AdspiritBannerModel.php';
require_once dirname(__FILE__) . '/AdspiritWidget.php';


/** Database setup */
register_activation_hook(__FILE__, array('AdspiritBanners', 'install'));
register_uninstall_hook(__FILE__, array('AdspiritBanners', 'uninstall'));
add_action('admin_menu', 'adspirit_plugin_menu');


/** Add Settings button to plugin overview page */
if (!function_exists('adspirit_admin_action_links')) {
    function adspirit_admin_action_links($links, $file)
    {
        if ( !current_user_can('edit_posts') )
            return;
        static $my_plugin;
        if (!$my_plugin) {
            $my_plugin = plugin_basename(__FILE__);
        }
        if ($file == $my_plugin) {
            $settings_link = "<a href='" . AdspiritBanners::getAdminUrl() . "'>Settings</a>";
            array_unshift($links, $settings_link);
        }
        return $links;
    }

    /** Add left main menu item */
    function adspirit_plugin_menu()
    {
        if ( !current_user_can('edit_posts') )
            return;
        $icon = null; //icon for menu
        add_options_page('Adspirit Options', 'Adspirit', 'manage_options', AdspiritBanners::ADSPIRIT_ADMIN_PAGE_ID, 'displayBannerListPage', $icon);
        //add it, but do not show it with first null parameter: http://stackoverflow.com/questions/3902760/how-do-you-add-a-wordpress-admin-page-without-adding-it-to-the-menu
        add_submenu_page(null, 'Adspirit Banner Update', 'Update', 'manage_options', AdspiritBanners::ADSPIRIT_BANNER_UPDATE_PAGE_ID, 'displayBannerUpdatePage');
    }

    /** Display view */
    function displayBannerListPage()
    {
        if ( !current_user_can('edit_posts') )
            return;
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }
        include_once 'views/bannerList.php';
    }

    function displayBannerUpdatePage()
    {
        if ( !current_user_can('edit_posts') )
            return;
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }
        include_once 'views/bannerUpdate.php';
    }

    function adspiritAddAsyncJsScript()
    {
        $code = AdspiritCodeParser::getAsyncJsScript();
        if ($code) {
            echo $code;
        }
    }


    //shortCode hook

    function adspirit_shortcode_function($atts, $content = null)
    {
        $id = isset($atts[0]) ? (int)$atts[0] : false;
        $code = "";
        if ($id) {
            $code = AdspiritBanners::getBannerCodeById($id);
        }
        return $code;
    }

    function registerAdspiritWidget()
    {
        register_widget('AdspiritWidget');
    }
}

add_filter('plugin_action_links', 'adspirit_admin_action_links', 10, 2);


add_shortcode('adspirit', 'adspirit_shortcode_function');


add_action('widgets_init', 'registerAdspiritWidget');

add_action('wp_footer', 'adspiritAddAsyncJsScript', 10000);

//debugging function
if (!function_exists('dump')) {
    function dump($var)
    {
        echo "<pre><div align='left'>";
        print_r($var);
        echo "</div></pre>";
    }
}