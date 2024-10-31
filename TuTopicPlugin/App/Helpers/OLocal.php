<?php  namespace TuTopicPlugin\App\Helpers;

/**
* @package     OLocal
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

use TuTopicPlugin\Popalo\Helpers\Utils;
use WP_Query;
use TuTopicPlugin\App\Helpers\OldImplementations\OrdersLocal as OLocalOld;
//use TuTopicPlugin\App\Helpers\OrdersLocal;

class OLocal
{

    /*
    - order
    - post
    - data
    - strikethrough
    */

  public static function getDataFromOldSystem() {
    $ordersOlds = OLocalOld::get();
    //var_dump($ordersOlds);exit();
    if (!$ordersOlds || !is_array($ordersOlds)) return false;
    foreach ($ordersOlds as $key => $order) {
      Self::add($order);
    }
    OLocalOld::set([]);
  }

  //Self::putMeta($id)
  public static function putMeta($ID = null, $meta_key, $data)
  {
      if (!$ID) return;
      $post = get_post($ID);
      if (!$post || $post->post_type !== 'tutopic_orders') return;

      return update_post_meta($ID, $meta_key, $data);
  }

  public static function initialize() {
    add_action( 'init', [ Self::class, 'TuTopicOrdersPostType' ], 0 );
  }

  // Register Custom Post Type
  public static function TuTopicOrdersPostType() {

  	$args = array(
  		'label'                 => __( 'Orden de tutopic', 'tutopic' ),
  		'description'           => __( 'Datos de las ordenes entradas al sistema por el plugin de TuTopic', 'tutopic' ),
  		//'labels'                => $labels,
  		'supports'              => array( 'custom-fields' ),
  		'hierarchical'          => false,
  		'public'                => false,
  		'show_ui'               => false,
  		'show_in_menu'          => false,
  		//'menu_position'         => 5,
  		'show_in_admin_bar'     => false,
  		'show_in_nav_menus'     => false,
  		'can_export'            => true,
  		'has_archive'           => false,
  		'exclude_from_search'   => true,
  		'publicly_queryable'    => true,
  		'capability_type'       => 'page',
  		'show_in_rest'          => false,
  	);

  	register_post_type( 'tutopic_orders', $args );

    // Orden
    register_post_meta( 'tutopic_orders', 'tt_order', array(
       'show_in_rest' => true, 'single' => true, 'type' => 'integer',
    ));
    // Post
    register_post_meta( 'tutopic_orders', 'tt_post', array(
       'show_in_rest' => true, 'single' => true, 'type' => 'integer',
    ));
    // Data
    register_post_meta( 'tutopic_orders', 'tt_data', array(
       'show_in_rest' => true, 'single' => true, 'type' => 'object',
    ));
    // Strikethrough
    register_post_meta( 'tutopic_orders', 'tt_strikethrough', array(
       'show_in_rest' => true, 'single' => true, 'type' => 'boolean',
    ));
    // Custom Post ID
    register_post_meta( 'tutopic_orders', 'tt_order_post_id', array(
       'show_in_rest' => true, 'single' => true, 'type' => 'integer',
    ));

    // Get Backs
    Self::getDataFromOldSystem();

  }
  //

  // Obtener todas
  public static function get() {

    $query = new WP_Query([
      'post_type' => 'tutopic_orders',
      'meta_key' => 'tt_order',
      'post_status' => array('publish', 'pending', 'draft', 'auto-draft', 'future', 'private', 'inherit', 'trash')
    ]);
    if (!$query || !$query->have_posts()) return [];

    $items = [];

    foreach ($query->posts as $key => $post) {
      $items[] = Self::FormatDataByPostID($post->ID);
    }

    //var_dump($items);exit();
    return $items;

  }

  // Agregar elemento
  public static function add($data) {

    // ID del post recien publicado
    $post_id = sanitize_key(wp_insert_post([
      'post_title'    =>  '[NULL]', 'post_content'  =>  '[NULL]','post_type' => 'tutopic_orders'
    ]));

    Self::putMeta($post_id, 'tt_order',           $data['order']);
    Self::putMeta($post_id, 'tt_post',            $data['post']);
    Self::putMeta($post_id, 'tt_data',            $data['data']);
    Self::putMeta($post_id, 'tt_strikethrough',   (boolean) $data['strikethrough']);
    Self::putMeta($post_id, 'tt_order_post_id',   $post_id);

    return $data;

  }


  public static function putOrder($order, $post = null, $data = null, $strikethrough = null) {

    if ($orderExists = Self::getOrder($order)) {

      $post_custom_id = sanitize_key($orderExists['order_post_id']);

      if ($post !== null)
      Self::putMeta($post_custom_id, 'tt_post',            $post);
      if ($data !== null)
      Self::putMeta($post_custom_id, 'tt_data',            $data);
      if ($strikethrough !== null)
      Self::putMeta($post_custom_id, 'tt_strikethrough',   (boolean) $strikethrough);

      return $order;

    }else{

      return Self::add([
        'order'             => $order,
        'post'              => $post,
        'data'              => $data,
        'strikethrough'     => $strikethrough
      ]);

    }

    return false;

  }


  // Formatear Data por el ID del post
  public static function FormatDataByPostID($post_id = null) {

    if (!$post_id) return null;
    $post = get_post($post_id);
    if (!$post || $post->post_type !== 'tutopic_orders') return null;

    $data = [];
    $data['order']          = (integer) sanitize_key(get_post_meta($post_id, 'tt_order', true));
    $data['post']           = (integer) sanitize_key(get_post_meta($post_id, 'tt_post', true));
    $data['data']           = get_post_meta($post_id, 'tt_data', true);
    $data['strikethrough']  = (boolean) get_post_meta($post_id, 'tt_strikethrough', true);
    $data['order_post_id']  = (integer) sanitize_key(get_post_meta($post_id, 'tt_order_post_id', true));

    if (get_post($data['post']))
      return $data;

    $data['post']           = null;
    $data['strikethrough']  = false;
    Self::putMeta($post_id, 'tt_post',            $data['post']);
    Self::putMeta($post_id, 'tt_strikethrough',   $data['strikethrough']);

    return $data;

  }

  // Obtener Orden
  public static function getOrder($id) {

    // QUERY
    $args     = [ 'post_type'   => 'tutopic_orders',
                  'meta_key'    => 'tt_order',
                  'meta_query'  => [
                  [
                    'key'     => 'tt_order',
                    'compare' => '=',
                    'value'   => $id,
                  ]],
                  'post_status' => array('publish', 'pending', 'draft', 'auto-draft', 'future', 'private', 'inherit', 'trash')
                ];
    $query    = new WP_Query($args);

    // QUERY (¿Have?)
    if (!$query || !$query->have_posts()) return null;
    $query->the_post();
    $post = $query->post;
    if (!$post || !$post->ID) return null;

    $formatData = Utils::sanitize_dictionary_or_text(Self::FormatDataByPostID($post->ID));

    // Formatear datos
    return $formatData;

  }


  // Obtener Orden (Debug)
  public static function getOrderDebug($id) {

    // QUERY
    $args     = [ 'post_type'   => 'tutopic_orders',
                  'meta_key'    => 'tt_order',
                  'meta_query'  => [
                  [
                    'key'     => 'tt_order',
                    'compare' => '=',
                    'value'   => $id,
                  ]],
                  'post_status' => array('publish', 'pending', 'draft', 'auto-draft', 'future', 'private', 'inherit', 'trash')
                ];

    write_log('[OLocalDebug] WP Query ARGS');
    write_log($args);

    $query    = new WP_Query($args);
    write_log('[OLocalDebug] WPQUERY SQL '.$query->request);

    // QUERY (¿Have?)
    if (!$query || !$query->have_posts()) {
      write_log('[OLocalDebug] No se encontraron POST que coincidieran con la tt_order '.$id);
      return null;
    }
    $query->the_post();
    $post = $query->post;
    if (!$post || !$post->ID) return null;

    write_log('[OLocalDebug] El POST que coincide con la tt_orden '.$id.' es '.$post->ID);
    write_log($post->ID);

    $formatData = Self::FormatDataByPostID($post->ID);

    // Formatear datos
    return $formatData;

  }

}
