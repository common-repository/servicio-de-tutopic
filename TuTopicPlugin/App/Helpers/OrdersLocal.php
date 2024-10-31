<?php  namespace TuTopicPlugin\App\Helpers;

/**
* @package     OrdersLocal
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

use TuTopicPlugin\App\Helpers\OLocal;
use TuTopicPlugin\Popalo\Helpers\Utils;

class OrdersLocal
{

  /**
   *
   * ------
   * orders.template.php
   * OrdersLocal::isOrderStriked
   * \TuTopicPlugin\App\Helpers\OrdersLocal::get()
   * ------
   *
   * ------
   * TuTopic.php
   * TuTopicPlugin\Service\TuTopic
   * OrdersLocal::getPostByOrder($order_id)
   * OrdersLocal::addPosttoOrder($order_id, $post)
   * OrdersLocal::strikeOrder($order_id)
   * ------
   *
   */

   // Inicializar
   public static function initialize() {
     OLocal::initialize();
   }

   // Get Order Post
   public static function isOrderStriked($order) {

     $data = Utils::sanitize_dictionary_or_text(OLocal::getOrder($order));

     // Si no encontro la orden
     if (!$data)
       return null;

     if (isset($data['strikethrough']) && $data['strikethrough']) {
       // Si encontro el post
       return $data['strikethrough'];
     }

     return null;

   }

   // Tachar Orden
   public static function strikeOrder($order) {
     OLocal::putOrder($order, null, null, true);
   }

   // Obtener todas
   public static function get() {
     return OLocal::get();
   }

   // Agregar Post a Orden
   public static function addPosttoOrder($order, $post) {
     OLocal::putOrder($order, $post, null, null);
   }

   // Get Order Post
   public static function getPostByOrder($order) {

     $data = OLocal::getOrder($order);

     // Si no encontro la orden
     if (!$data)
       return null;

     if (isset($data['post']) && $data['post']) {
       // Si encontro el post
       return $data['post'];
     }

     return null;

   }

   // Get Order Local By Order (Fines de Debug)
   public static function getOrderLocalByOrder($order) {


     write_log('[TuTLog] BEGIN ---- DEBUG GET ORDER LOCAL BY ORDER ');

      return OLocal::getOrderDebug($order);

     write_log('[TuTLog] EXIT  ---- DEBUG GET ORDER LOCAL BY ORDER ');

   }

}
