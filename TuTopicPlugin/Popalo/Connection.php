<?php namespace TuTopicPlugin\Popalo;

/**
* @package     Connection
* @copyright   (C) 2020 - 2021 Tutopic
* @license     GNU General Public License v2 or later
* @license     http://www.gnu.org/licenses/gpl-2.0.html
* @author      Tutopic
*
* This file is part of "Tutopic para WordPress".
* "Tutopic para WordPress" is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License or (at your option) any later version.
* "Tutopic para WordPress" is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
*
* Opalo Disclaimer:
* Se uso a una derivacion no-oficial de "Opalo Framework" en una version inferior 1.3 (desarrollado por Nextscale) llamado "Popalo" adaptada a plugins para la realizacion del plugin "Servicio de TuTopic en Wordpress", sin embargo "Opalo Framework", aun no esta licenciado oficialmente y en un futuro se piensa oficalizar bajo la licencia MIT. este Disclaimer fue escrito originalmente en Español en 2021.
* Este archivo es una derivacion originalmente desarrollada para "Opalo Framework"
*
*/

class Connection {

    public static function response($wpResponse) {

      if (is_wp_error($wpResponse)) {
        $statusRes  = 500;
        $bodyRes    = $wpResponse->get_error_message();
      }else{
        $statusRes  = wp_remote_retrieve_response_code($wpResponse);
        $bodyRes    = wp_remote_retrieve_body($wpResponse);
        //$statusRes  = wp_remote_retrieve_response_code($wpResponse);
        //$bodyRes    = wp_remote_retrieve_body($wpResponse);
      }

      /*Clase anonima "Response"*/
      $response = new Class($bodyRes, $statusRes) {
        public $body;
        public $status;
        public function __construct($body = null, $status = 200) {
          $this->body   = $body;
          $this->status = $status;
        }
      };

      return $response;

    }

    public static function get($wp_url, $args) {
      $wpResponse = wp_remote_get($wp_url, $args);
      return Self::response($wpResponse);
    }

    public static function post($wp_url, $args) {
      $wpResponse = wp_remote_post($wp_url, $args);
      return Self::response($wpResponse);
    }

}

?>
