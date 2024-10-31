<?php namespace TuTopicPlugin\App\AdminPost\Forms;

/**
* @package     ApproveDeliveryServiceForm
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
use TuTopicPlugin\Service\TuTopic;
use TuTopicPlugin\Service\PostService;

/**
  * Procesar formulario "Servicio de creacion de ordenes"
  */
class ApproveDeliveryServiceForm {

    function handle($data) {

      $sender_url = esc_url(PopaloUtils::getSenderUrl());

      if (!get_option('association_ready'))
        PopaloUtils::redirectWithError($sender_url, "No se encuentra vinculada con ninguna cuenta de TuTopic.", 401);

      //approve_post_type
      if (!isset($_POST['delivery_id']) || !$_POST['delivery_id'])
        PopaloUtils::redirectWithError($sender_url, "No hay un ID de Delivery seleccionado", 401);

      $delivery_id = sanitize_key($_POST['delivery_id']);

      $response = Connection::post(TUTOPIC_BASE_SERVICE_URL.'/accept/delivery/'.$delivery_id, [
        'headers' => [
          "X-Requested-With" => "XMLHttpRequest",
          "TuTopic-Api-Key"  => get_option('association_code')
        ]
      ]);
      //var_dump($response);exit();

      //var_dump($response->body);exit();

      if ($response->status >= 200 && $response->status <= 204) {

        // PopaloUtils::redirectWithSuccess($sender_url, $response->body, $response->status);

        try {

          $result = PostService::createPost(
            $delivery_id,
            sanitize_text_field($_POST['approve_post_type']),
            sanitize_text_field($_POST['post_title'])
          );

          if ( is_wp_error($result) ){
             PopaloUtils::redirectWithError($sender_url, $result->get_error_message(), 500);
          }else{
            $redirect =Utils::sanitize_dictionary_or_text(get_site_url().'/wp-admin/post.php?post='.$result.'&action=edit');
            PopaloUtils::redirectWithSuccess($redirect, 'Borrador de entrega #'.$delivery_id.' creado con exito.', 200);
          }

        } catch (\Exception $e) {
          PopaloUtils::redirectWithError($sender_url, $e->getMessage(), 500);
          //var_dump($content);exit();
        }




        PopaloUtils::redirectWithError($sender_url, 'Error desconocido.', 500);


      }//order_completed

      PopaloUtils::redirectWithError($sender_url, $response->body, $response->status);

    }
}


