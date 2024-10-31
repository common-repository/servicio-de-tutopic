<?php  namespace TuTopicPlugin\App\Helpers;

/**
* @package     MLocal
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

use TuTopicPlugin\App\Helpers\OldImplementations\MediasLocal as MLocalOld;
use WP_Query;
//use TuTopicPlugin\App\Helpers\MediasLocal;

class MLocal
{

    public static function getDataFromOldSystem() {
      $itemsOlds = MLocalOld::get();

      if (!$itemsOlds || !is_array($itemsOlds)) return false;
      foreach ($itemsOlds as $key => $item) {
        Self::add($item);
      }

      MLocalOld::set([]);
    }

    const post_type_name = 'tutopic_medios_cache';

    //Self::putMeta($id)
    public static function putMeta($ID = null, $meta_key, $data)
    {
        if (!$ID) return;
        $post = get_post(sanitize_key($ID));
        if (!$post || $post->post_type !== Self::post_type_name) return;
        return update_post_meta($ID, $meta_key, $data);
    }

    public static function initialize() {
      add_action( 'init', [ Self::class, 'TuTopicMediasLocalDatabase' ], 0 );
    }

    // Register Custom Post Type
    public static function TuTopicMediasLocalDatabase() {

      $args = array(
        'label'                 => __( 'Medios digitales cache', 'tutopic' ),
        'description'           => __( 'Datos del cache de Medios digitales descargados desde TuTopic', 'tutopic' ),
        //'labels'              => $labels,
        'supports'              => array( 'custom-fields' ),
        'hierarchical'          => false,'public'              => false,'show_ui'             => false,
        'show_in_menu'          => false,'show_in_admin_bar'   => false,'show_in_nav_menus'   => false,
        'can_export'            => true,'has_archive'         => false,'exclude_from_search' => true,
        'publicly_queryable'    => true,'capability_type'     => 'post','show_in_rest'        => false,
        //'menu_position'       => 5,
      );

      register_post_type( Self::post_type_name , $args );

      // Origin
      register_post_meta( Self::post_type_name , 'tt_origin', array(
         'show_in_rest' => true, 'single' => true, 'type' => 'string',
      ));
      // Attachment
      register_post_meta( Self::post_type_name , 'tt_attachment_id', array(
         'show_in_rest' => true, 'single' => true, 'type' => 'integer',
      ));
      // Custom Post ID
      register_post_meta( Self::post_type_name, 'tt_local_cpost', array(
         'show_in_rest' => true, 'single' => true, 'type' => 'integer',
      ));


      // Get Backs
      Self::getDataFromOldSystem();

    }

    /////////////////////////////////////////////////////


    // Obtener todas
    public static function get() {

      $query = new WP_Query([
        'post_type' => Self::post_type_name,
        'meta_key' => 'tt_origin'
      ]);
      if (!$query || !$query->have_posts()) return [];

      $items = [];

      foreach ($query->posts as $key => $post) {
        $items[] = Self::FormatDataByPostID($post->ID);
      }

      return $items;

    }

    // Agregar elemento
    public static function add($data) {
      $post_id = wp_insert_post(['post_title'    =>  '[NULL]', 'post_content'  =>  '[NULL]','post_type' => Self::post_type_name]);
      Self::putMeta($post_id, 'tt_origin',          esc_url_raw($data['origin']));
      Self::putMeta($post_id, 'tt_attachment_id',   sanitize_key($data['attachment_id']));
      Self::putMeta($post_id, 'tt_local_cpost',     sanitize_key($post_id));
      return $data;
    }


    // Obtener Orden
    public static function getMedia($origin) {

      // QUERY
      $args     = [ 'post_type'   => Self::post_type_name,
                    'meta_key'    => 'tt_origin',
                    'meta_query'  => [
                    [
                      'key'     => 'tt_origin',
                      'compare' => '=',
                      'value'   => esc_url_raw($origin),
                    ]]
                  ];

      $query    = new WP_Query($args);


      // QUERY (¿Have?)
      if (!$query || !$query->have_posts()) return null;
      $query->the_post();
      $post = $query->post;
      if (!$post || !$post->ID) return null;

      // Formatear datos
      return Self::FormatDataByPostID($post->ID);

    }

    // Agregar Origin de "Media" [Args]
    public static function putMedia($media) {

      if ($mediaResult = Self::getMedia($media['origin'])) {
        //Existe
        foreach ($media as $mediaKey => $mediaValue) {
          Self::putMeta( $mediaResult['local_cpost'],
                         'tt_'.$mediaKey, $mediaValue );
        }
        return true;
      }else{
        //No existe
        return Self::add($media);
      }

      return false;

    }

    /////////////////////////////////////////////////////

    // Formatear Data por el ID del post
    public static function FormatDataByPostID($post_id = null) {

      if (!$post_id) return null;
      $post = get_post(sanitize_key($post_id));
      if (!$post || $post->post_type !== Self::post_type_name) return null;

      $data = [];
      $data['origin']          = (string)   esc_url_raw ( get_post_meta($post_id, 'tt_origin', true)        );
      $data['attachment_id']   = (integer)  sanitize_key( get_post_meta($post_id, 'tt_attachment_id', true) );
      $data['local_cpost']     = (integer)  sanitize_key( get_post_meta($post_id, 'tt_local_cpost', true)   );

      // Debe retornarse los datos
      return $data;

    }

}
