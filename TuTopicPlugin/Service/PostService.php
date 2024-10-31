<?php namespace TuTopicPlugin\Service;

/**
* @package     PostService
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



use TuTopicPlugin\Popalo\Connection;
use TuTopicPlugin\Service\TuTopic;

/**
  * Servicio para manejar posts
  */
class PostService {

  public static function createPost($delivery_id, $approve_post_type = 'post', $approve_post_title = null, $sendFakeResponse = false) {

    $postState = get_option('auto_publish_in_approbe');
    if ($postState) {
      $postState = 'publish';
    }else{
      $postState = 'draft';
    }

    // Obtenemos el ID del post recien creado (y lo sanitizamos)
    $post_id = sanitize_key(TuTopic::createDraftByDelivery($delivery_id, $approve_post_type, $approve_post_title, $postState, $sendFakeResponse));

    return $post_id;

  }

}