<?php namespace TuTopicPlugin\App\AdminPost\Forms;

/**
* @package     CreateOrderServiceForm
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

/**
  * Procesar formulario "Servicio de creacion de ordenes"
  */
class CreateOrderServiceForm {
    function handle() {

      /* Fase 1: Inicializacion */
      // Obtenemos el "Sender URL"
        $sender_url = esc_url(PopaloUtils::getSenderUrl());

      /* Fase 2: Comprobando vinculacion */
        // No se encuentra vinculada
        if (!get_option('association_ready')) {
          PopaloUtils::redirectWithError($sender_url, "No se encuentra vinculada con ninguna cuenta de TuTopic.", 401);
        }
        // Se encuentra vinculada pero ahora la asociacion no es valida
        $response = \TuTopicPlugin\Service\TuTopic::ComprobarVinculacion();// Hacemos la peticion de comprobacion de estado
        if (!get_option('association_ready')) {
          PopaloUtils::redirectWithSuccess($sender_url, 'Error en vinculacion con cuenta de TuTopic, asociacion invalida.', 401);
        }

        //var_dump($_POST);exit();

      // Validaciones
      $erroresLocales = [];

      if ( empty( $_POST['title'] ) ) {
        $erroresLocales[] = 'El titulo es obligatorio.';
      }
      if ( !empty( $_POST['title'] ) && (strlen($_POST['title']) > 300 || strlen($_POST['title']) < 4) ) {
        $erroresLocales[] = 'El titulo del pedido estar entre 4 y 300 caracteres.';
      }
      if ( empty( $_POST['words_number'] ) ) {
        $erroresLocales[] = 'El campo numero de palabras es requerido.';
      }
      if ( !empty( $_POST['comment'] ) && (strlen($_POST['title']) > 256) ) {
        $erroresLocales[] = 'El comentario de la entrega tiene un maximo de caracteres de 256.';
      }
      if ( empty( $_POST['competency_id'] ) ) {
        $erroresLocales[] = 'Es necesario especificar la tematica del pedido.';
      }
      if ( empty( $_POST['quality_level_needed'] ) ) {
        $erroresLocales[] = 'Es necesario especificar el nivel de calidad requerido.';
      }

      if (sizeof($erroresLocales)) {
        PopaloUtils::redirectWithError($sender_url,$erroresLocales, 400);
      }


      $keywords = [];
      // Getting "keywords" Array
      $keywords = isset( $_POST['keywords'] ) ? json_decode(str_replace('\\','',$_POST['keywords'])) : array();
      // Sanitize "keywords" Array Function
      $keywords = array_map( 'sanitize_text_field', $keywords );

      $avoid_words = [];
      // Getting "avoid_words" Array
      $avoid_words = isset( $_POST['avoid_words'] ) ? json_decode(str_replace('\\','',$_POST['avoid_words'])) : array();
      // Sanitize "avoid_words" Array Function
      $avoid_words = array_map( 'sanitize_text_field', $avoid_words );


      $body = [
        'title'                 =>    sanitize_text_field($_POST['title']),
        'words_number'          =>    sanitize_text_field($_POST['words_number']),
        'keywords'              =>    json_encode($keywords),
        'avoid_words'           =>    json_encode($avoid_words),
        'comment'               =>    (isset($_POST['comment']) && !empty($_POST['comment'])) ? sanitize_text_field($_POST['comment']) : null,
        'competency_id'         =>    sanitize_key($_POST['competency_id']),
        'is_urgent'             =>    rest_sanitize_boolean($_POST['is_urgent']),
        'days_less'             =>    sanitize_text_field($_POST['days_less']),
        'quality_level_needed'  =>    sanitize_text_field($_POST['quality_level_needed']),
        'site_url'              =>    get_site_url(),
        'need_corrector'        =>
        (isset($_POST['need_corrector']) && !empty($_POST['need_corrector'])) ?
        rest_sanitize_boolean($_POST['need_corrector']) : false,
      ];
      //var_dump($body);exit();

      if ($body['competency_id'] === 'custom') {
        if (!isset($_POST['custom_competency']) || empty($_POST['custom_competency'])) {
          return PopaloUtils::redirectWithError($sender_url, 'Debe darle un nombre a la competencia personalizada.', 400);
        }
        $body['custom_competency'] = sanitize_text_field($_POST['custom_competency']);
      }
      //var_dump($body);exit();

      // Peticion para crear una nueva orden
      $response = Connection::post(TUTOPIC_BASE_SERVICE_URL.'/orders', [
        'body' => $body,
        //'body' => $_POST <--- tambien podria servir igual de bien y hasta mejor -feredev
        'headers' => [
          "X-Requested-With" => "XMLHttpRequest",
          "TuTopic-Api-Key"  => get_option('association_code'),
          'X-Origin-Site-Base-URL' => get_site_url(),
        ]
      ]);


      // En caso de recibir un '200' por parte de TuTopic
      if ($response->status >= 200 && $response->status <= 204) {
        $body = json_decode($response->body);
        PopaloUtils::redirectWithSuccess($sender_url, $body->message, $response->status);
      }

      // En caso de no recibir un '200' por parte de TuTopic
      PopaloUtils::redirectWithError($sender_url, $response->body, $response->status);

    }
}


/*"words_number"  => "required|integer|min:800|max:5000",
"title"         => "string|max:48|min:4",
"keywords"      => ["string", new Max10Items()],//[]
"avoid_words"   => ["string", new Max10Items()],//[]
/*'comment'       => 'string|max:256',
'competency_id' => 'required|exists:competences,id',
'image_1'       => "file|image|mimes:jpeg,bmp,png|between:0,8192",
'image_2'       => "file|image|mimes:jpeg,bmp,png|between:0,8192",
'image_3'       => "file|image|mimes:jpeg,bmp,png|between:0,8192",
'is_urgent'     => 'required|boolean'*/


?>
