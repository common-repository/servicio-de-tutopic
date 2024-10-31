<?php
/*
Plugin Name: Tutopic para WordPress
Plugin URI: https://tutopic.com/es
Description: Con Tutopic podrás solicitar fácilmente todo tipo de artículos y contenidos relacionados con la temática de tu blog, bajo criterios que necesites
Version: 1.18
Author: Tutopic
Author URI: https://tutopic.com/es
Text Domain: tutopic
Domain Path:
License: GNU General Public License v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 2 of the License or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program. If not, see http://www.gnu.org/licenses/

You can contact us at soporte@tutopic.com

Tutopic para WordPress incorporates code from:

- Font Awesome por Dave Gandy - http://fontawesome.io
- W3.CSS 4.15 December 2020 by Jan Egil and Borge Refsnes
- Unofficial derivation of Opalo Framework (MIT) By 2020 Nextscale's technology - https://github.com/Hector1567XD/opalo-framework.

*/

// CONSTANTES
//define('TUTOPIC_BASE_URL',               'http://localhost:8000');
//define('TUTOPIC_BASE_URL', '      https://tutopic.nextscale.net');
define('TUTOPIC_BASE_URL',                 'https://tutopic.com');
//define('TUTOPIC_BASE_URL',                   'https://tutopic.net');

define('TUTOPIC_BASE_SERVICE_URL',    TUTOPIC_BASE_URL.'/api/wp-service');
define('TUTOPIC_PLUGIN_BASE_URL',     plugin_dir_url(__FILE__));
define('TUTOPIC_PLUGIN_BASE_PATH',    plugin_dir_path(__FILE__));

// AUTOLOADER
require_once 'vendor/autoload.php';
// INICIALIZACION
$init = new TuTopicPlugin\App\Init();
// Inicializar "Notices" del Helper de Popalo
TuTopicPlugin\Popalo\Helpers\Utils::initializeNotices();

// CARGA DE CSS Y JS
function ttpcsvc_load_css_plugin_admin() {

    // TODO: poner elegantemente en algun objeto o clase

    wp_enqueue_style( 'tutopic-main',               TUTOPIC_PLUGIN_BASE_URL . 'assets/css/main.css' );
    wp_enqueue_style( 'tutopic-layout',             TUTOPIC_PLUGIN_BASE_URL . 'assets/css/layout.css' );

    wp_enqueue_style( 'tutopic-layout-w3c',         TUTOPIC_PLUGIN_BASE_URL . 'assets/vendor/css/w3.css' );

    wp_enqueue_script( 'tutopic-update-url',        TUTOPIC_PLUGIN_BASE_URL . 'assets/vendor/js/updateURLParameter.js' );
    wp_enqueue_script( 'tutopic-main',              TUTOPIC_PLUGIN_BASE_URL . 'assets/js/main.js' );

    wp_enqueue_script( 'tutopic-config-data',       TUTOPIC_PLUGIN_BASE_URL . 'assets/js/config-data.js' );
    wp_enqueue_script( 'tutopic-post-calculator',   TUTOPIC_PLUGIN_BASE_URL . 'assets/js/post-calculator.js' );


    // Imports (Styles)
    wp_enqueue_style( 'tutopic-create-page',        TUTOPIC_PLUGIN_BASE_URL . 'assets/css/pages/create-page.css' );
    // Imports (Styles)
    wp_enqueue_style( 'tutopic-settings-page',      TUTOPIC_PLUGIN_BASE_URL . 'assets/css/pages/settings-page.css' );
    // Imports (Styles)
    wp_enqueue_style( 'tutopic-home-page',          TUTOPIC_PLUGIN_BASE_URL . 'assets/css/pages/home-page.css' );
    // Imports (Scripts)
    wp_enqueue_script( 'tutopic-create-page',       TUTOPIC_PLUGIN_BASE_URL . 'assets/js/pages/create-page.js' );
    // Imports (Scripts)
    wp_enqueue_script( 'tutopic-settings-page',     TUTOPIC_PLUGIN_BASE_URL . 'assets/js/pages/settings-page.js' );
    // Imports (Scripts)
    wp_enqueue_script( 'tutopic-home-page',         TUTOPIC_PLUGIN_BASE_URL . 'assets/js/pages/home-page.js' );

}
add_action( 'admin_enqueue_scripts', 'ttpcsvc_load_css_plugin_admin' );

// Notificaciones frontend de Popalo (Version TuTopic)
require_once 'includes/frontend-notifications.php';
// Aumenta el tiempo de Timeout
add_filter( 'http_request_timeout', function ( $time ) {
  return 45; // Default timeout is 5
});


if (!function_exists('write_log')) {

    function write_log($log) {
        if (true === WP_DEBUG) {
            if (is_array($log) || is_object($log)) {
                error_log(print_r($log, true));
            } else {
                error_log($log);
            }
        }
    }

}
/*
  Prefix of functions:
  TuTopicPlugin\
  ttpcsvc_
*/
