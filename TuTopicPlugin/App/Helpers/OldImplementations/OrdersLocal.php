<?php  namespace TuTopicPlugin\App\Helpers\OldImplementations;

/**
* @package     OrdersLocal (Implementacion antigua)
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

//use TuTopicPlugin\App\Helpers\OrdersLocal;

class OrdersLocal
{

    // Obtener todas
    public static function get() {
      $data = get_option('orders_local');
      if (!$data || !is_array($data)) $data = [];
      return $data;
    }

    // Establecer todas
    public static function set($data = []) {
      return update_option('orders_local', $data);
    }

    // Agregar elemento
    public static function add($item) {
      $data   = Self::get();
      $data[] = $item;
      return update_option('orders_local', $data);
    }

    // Obtener Orden
    public static function getOrder($id) {
      $items   = Self::get();

      foreach ($items as $item)
        if ($item['order'] == $id)
          return $item;

      return null;
    }

    // Agregar Orden [Args]
    public static function putOrderData($order) {
      if (Self::getOrder($order['order'])) {
        //Existe
        $items = Self::get();
        foreach ($items as $itemKey => $item) {
          if ($item['order'] == $order['order']) {
            $items[$itemKey] = $order;
          }
        }
        return Self::set($items);
      }else{
        //No existe
        return Self::add($order);
      }
      return false;
    }

    // Agregar Orden
    public static function putOrder($order, $post = null, $data = null, $strikethrough = null) {

      if ($orderExists = Self::getOrder($order)) {
        //Existe
          if ($order !== null)
          $orderExists['order']         = $order;

          if ($post !== null)
          $orderExists['post']          = $post;

          if ($data !== null)
          $orderExists['data']          = $data;

          if ($strikethrough !== null)
          $orderExists['strikethrough'] = $strikethrough;

          return Self::putOrderData($orderExists);

      }else{
        //No existe
          return Self::putOrderData([
            'order'             => $order,
            'post'              => $post,
            'data'              => $data,
            'strikethrough'     => $strikethrough
          ]);

      }

      return false;

    }

    // Get Order Post
    public static function isOrderStriked($order) {

      $data = Self::getOrder($order);

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
      Self::putOrder($order, null, null, true);
    }

    // Agregar Post a Orden
    public static function addPosttoOrder($order, $post) {
      Self::putOrder($order, $post, null, null);
    }

    // Get Order Post
    public static function getPostByOrder($order) {

      $data = Self::getOrder($order);

      // Si no encontro la orden
      if (!$data)
        return null;

      if (isset($data['post']) && $data['post']) {
        // Si encontro el post
        return $data['post'];
      }

      return null;

    }

}
