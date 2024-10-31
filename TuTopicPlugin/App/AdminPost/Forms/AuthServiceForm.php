<?php namespace TuTopicPlugin\App\AdminPost\Forms;

/**
* @package     AuthServiceForm
* @copyright   (C) 2020 - 2021 Tutopic
* @license     GNU General Public License v2 or later
* @license     http://www.gnu.org/licenses/gpl-2.0.html
* @author      Tutopic
*
* This file is part of "Tutopic para WordPress".
* "Tutopic para WordPress" is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License or (at your option) any later version.
* "Tutopic para WordPress" is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
*
*/

use TuTopicPlugin\Popalo\Helpers\Utils as PopaloUtils;
use TuTopicPlugin\Popalo\Connection;
use TuTopicPlugin\Popalo\UsersRepository;
/**
  * Procesar formulario "Servicio de autenticacion"
  * //wp_create_user
  */
class AuthServiceForm {
    function handle() {

      /* Fase 1: Inicializacion */
      // Obtenemos el "Sender URL"
      $sender_url = PopaloUtils::getSenderUrl();

      // Validaciones
      if ( empty( $_POST['politica_privacidad'] ) || !$_POST['politica_privacidad'] ) {
        PopaloUtils::redirectWithError($sender_url, 'Debe aceptar la politica de privacidad de TuTopic para continuar.', 400);
      }
      if ( empty( $_POST['association_code'] ) ) {
        PopaloUtils::redirectWithError($sender_url, 'Debe establecer un codigo de asociacion', 400);
      }
      if ( strlen( $_POST['association_code'] ) !== 40 ) {
        PopaloUtils::redirectWithError($sender_url, 'El codigo de asociacion debe medir 40 caracteres', 400);
      }
      if ( empty( $_POST['on_patronize'] ) || !$_POST['on_patronize'] ) {
        $monetary_value = 0;
        $on_patronize = 0;
      }else{
        $monetary_value = $_POST['monetary_value'];
        $on_patronize = 1;

        update_option('monetary_value', sanitize_text_field($_POST['monetary_value']));
        update_option('on_patronize', sanitize_text_field($_POST['on_patronize']));
      }


      // Sanitizacion de las competencias asociadas
      $competencias_asociadas = [];

      // Unsanitized Array
      $competencias_asociadas = isset( $_POST['association_competences'] ) ? (array) $_POST['association_competences'] : array();
      // Sanitized Array
      $competencias_asociadas = array_map( 'sanitize_key', $competencias_asociadas );


      /* Fase 2: Recibiendo datos */
      // Tan pronto obtenemos el "association_code" lo guardamos en las opciones
      update_option('association_code',
        sanitize_text_field($_POST['association_code']));

      update_option('association_user',
        sanitize_key($_POST['association_user']));

      #Contribuir al autor original
      update_option('contribuite_to_original_author',
        rest_sanitize_boolean($_POST['contribuite_to_original_author']));

      update_option('association_competences', $competencias_asociadas);

      #Autopublicar articulo al aprobarse
      update_option('auto_publish_in_approbe',
        rest_sanitize_boolean($_POST['auto_publish_in_approbe']));

      #Autocentrar imagenes del articulo al publicarse
      update_option('auto_center_in_publish',
        rest_sanitize_boolean($_POST['auto_center_in_publish']));



      //auto_publish_in_approbe
      /* Fase 3: Inicializacion #2 */
      // Inicializamos la asociacion en falso
      update_option('association_ready', false);

      /* Fase 4: Comprobando estado de vinculacion segun options (association_code, association_ready) */
      //En este caso, tambien sirve para saber si se realizo bien o no esta peticion.
      $response = \TuTopicPlugin\Service\TuTopic::ComprobarVinculacion();
      if ($response->status !== 200) {
        // En caso de no recibir un '200' por parte de TuTopic
        PopaloUtils::redirectWithError($sender_url, $response->body, $response->status);
      }

      /* Fase 5: Se usa para ademas linkear el sitio (primero se comprobo, pero esto hace el linkeo oficialmente)*/
      $response = Connection::post(TUTOPIC_BASE_SERVICE_URL.'/site-link', [
        'body' => [
          'sender_url'              =>    get_site_url(),
          'categories'              =>    $competencias_asociadas,
          'monetary_value'          =>    $monetary_value,
          'on_patronize'            =>    $on_patronize
        ],
        'headers' => [
          "X-Requested-With"        =>    "XMLHttpRequest",
          "TuTopic-Api-Key"         =>    get_option('association_code'),
          'X-Origin-Site-Base-URL'  =>    get_site_url(),
        ]
      ]);

      // En caso de recibir un '200' por parte de TuTopic
      if ($response->status === 200) {
        // Redireccionar a formulario anterior
        PopaloUtils::redirectWithSuccess($sender_url, 'Cuenta vinculada con exito!', 200);
      }

      // En caso de no recibir un '200' por parte de TuTopic
      PopaloUtils::redirectWithError($sender_url, $response->body, $response->status);

    }
}
