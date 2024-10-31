<?php namespace TuTopicPlugin\App\AdminPost\Forms;

/**
* @package     DenyDeliveryServiceForm
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
class CorrectDeliveryServiceForm {
    function handle($data) {

      $sender_url = esc_url(PopaloUtils::getSenderUrl());

      if (!get_option('association_ready'))
        PopaloUtils::redirectWithError($sender_url, "No se encuentra vinculada con ninguna cuenta de TuTopic.", 401);

      if (!isset($_POST['delivery_id']) || !$_POST['delivery_id'])
        PopaloUtils::redirectWithError($sender_url, "No hay un ID de Delivery seleccionado", 401);

      // Validaciones
      if ( !empty( $_POST['comment'] ) && (strlen($_POST['comment']) > 256 || strlen($_POST['comment']) < 4) ) {
        PopaloUtils::redirectWithError($sender_url, 'El comentario que envie al corregir una entrega debe estar entre 4 y 256 caracteres.', 400);
      }

      $response = Connection::post(TUTOPIC_BASE_SERVICE_URL.'/reject/delivery/'.$_POST['delivery_id'], [
        'body' => [
          'comment'     => sanitize_text_field($_POST['comment']),
          'type_reject' => 'correction'
        ],
        'headers' => [
          "X-Requested-With" => "XMLHttpRequest",
          "TuTopic-Api-Key"  => sanitize_text_field(get_option('association_code'))
        ]
      ]);

      //var_dump($response->body);exit();

      if ($response->status >= 200 && $response->status <= 204) {
        PopaloUtils::redirectWithSuccess($sender_url, $response->body, $response->status);
      }

      PopaloUtils::redirectWithError($sender_url, $response->body, $response->status);

    }
}

?>
