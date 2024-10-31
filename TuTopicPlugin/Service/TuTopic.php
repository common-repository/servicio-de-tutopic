<?php namespace TuTopicPlugin\Service;

/**
* @package     Tutopic
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
use TuTopicPlugin\Popalo\PostsRepository;
use TuTopicPlugin\App\Helpers\MediaHelper;
use TuTopicPlugin\App\Helpers\WPContentHelper;
use TuTopicPlugin\Popalo\UsersRepository;
use TuTopicPlugin\App\Helpers\OrdersLocal;
use TuTopicPlugin\Popalo\Helpers\Utils;

class TuTopic {

    public static function getPublicData() {
      $response = Connection::get(TUTOPIC_BASE_URL.'/api/data/get-config-data', [
        'headers' => ["X-Requested-With" => "XMLHttpRequest",'X-Origin-Site-Base-URL' => get_site_url()]
      ]);

      // Si todo salio bien
      if ($response->status === 200)
        return json_decode($response->body);

      return null;
    }

    //OLD: TuTopic_ComprobarEstadoVinculacion
    public static function ComprobarVinculacion() {

      // Creamos un usuario para Tutopic

      $user = UsersRepository::systemCreate('Tutopic', 'soporte@tutopic.com');
      if ($user)
        if (!get_option('association_user'))
          update_option('association_user', $user);

      // Inicializamos la vinculacion en false
      update_option('association_ready', false);

      // Hacemos la peticion de comprobacion de estado
      $response = Connection::post(TUTOPIC_BASE_SERVICE_URL.'/api-key-comprobe', [
        'body' => ['tutopic_key' => get_option('association_code')],
        'headers' => ["X-Requested-With" => "XMLHttpRequest",'X-Origin-Site-Base-URL' => get_site_url()]
      ]);

      // Si todo salio bien
      if ($response->status === 200) {
        // Ponemos la vinculacion en true
        update_option('association_ready', true);
        return $response;
      }

      return $response;

    }

    public static function getOrders($page = 1) {

      $response = Connection::get(TUTOPIC_BASE_SERVICE_URL.'/orders?page='.$page, [
        'headers' => [
          "X-Requested-With" => "XMLHttpRequest",
          "TuTopic-Api-Key"  => get_option('association_code'),
          'X-Origin-Site-Base-URL' => get_site_url(),
        ]
      ]);

      // Si todo salio bien
      if ($response->status === 200) {
        return json_decode($response->body);
      }

      return $response->body;

    }

    public static function getPointSeo() {

      $response = Connection::get(TUTOPIC_BASE_SERVICE_URL.'/point-seo', [
        'headers' => [
          "X-Requested-With" => "XMLHttpRequest",
          "TuTopic-Api-Key"  => get_option('association_code'),
          'X-Origin-Site-Base-URL' => get_site_url(),
        ]
      ]);

      // Si todo salio bien
      if ($response->status === 200) {
        return json_decode($response->body);
      }

      return $response->body;

    }

    public static function getMe() {

      $response = Connection::get(TUTOPIC_BASE_SERVICE_URL.'/get-me', [
        'headers' => [
          "X-Requested-With" => "XMLHttpRequest",
          "TuTopic-Api-Key"  => get_option('association_code'),
          'X-Origin-Site-Base-URL' => get_site_url(),
        ]
      ]);

      if ($response->status === 200) {
        return json_decode($response->body);
      }

      return $response->body;

    }

    public static function getCompetences($dataPublic = false) {

      // Si las competencias estan en CACHE o ya se pidieron en esta ejecusion...
      if (isset($GLOBALS['tutopic_competences_cache'])) {
        return $GLOBALS['tutopic_competences_cache'];
      }

      $url = TUTOPIC_BASE_SERVICE_URL.'/data/competences';
      $headers = [
        "X-Requested-With"        => "XMLHttpRequest",
        "TuTopic-Api-Key"         => get_option('association_code'),
        'X-Origin-Site-Base-URL'  => get_site_url(),
      ];
      if ($dataPublic) {
        /*
          Accedera a competencias PUBLICAS no tomara en cuenta los datos de configuracion
          del sitio y no enviara la api-key de TuTopic
        */
        $url = TUTOPIC_BASE_SERVICE_URL.'/data-public/competences';
        // No enviara la api-key de tutopic, por que son publicas, se asume no se tiene
        unset($headers['TuTopic-Api-Key']);
      }

      $response = Connection::get($url, [
        'headers' => $headers,
      ]);

      // Si todo salio bien
      if ($response->status === 200){
        $result = json_decode($response->body);
        // Lo guardamos en cache
        $GLOBALS['tutopic_competences_cache'] = $result;
        // Lo retornamos
        return $result;
      }

      return null;
    }

    public static function createDraftByDelivery($deliveryID, $postType = 'post', $newTitle = null, $statusDelivery = 'draft', $sendFakeResponse = false) {

      $log_instance = sha1(uniqid());
      write_log('[TuTLog] Function createDraftByDelivery Begin - '.$log_instance);

      // Enviar respuesta falsa para que el Laravel desocupe el hilo de ejecusion que pedira el wordpress en la siguiente peticion
      if ($sendFakeResponse)  {
        write_log('[TuTLog] Send Fake Response - '.$log_instance);
        Utils::FakeResponse([
          'success' => true,
          'message'	=> 'Delivery agregado con exito!'
        ]);
      }

      write_log('[TuTLog] Request To '.TUTOPIC_BASE_SERVICE_URL.'/content/delivery/'.$deliveryID.' - '.$log_instance);
      $response = Connection::get(TUTOPIC_BASE_SERVICE_URL.'/content/delivery/'.$deliveryID, [
        'headers' => [
          "X-Requested-With"          => "XMLHttpRequest",
          "TuTopic-Api-Key"           => get_option('association_code'),
          'X-Origin-Site-Base-URL'    => get_site_url(),
          'Connection'                => 'Keep-Alive'
        ]
      ]);


      //return json_encode($response);exit();
      //TuTopicPlugin
      //var_dump($response->body);exit();
      if ($response->status === 200) {

        write_log('[TuTLog] Request (200) Result: '.$log_instance);

        $body = json_decode($response->body);

        //#OrderID
        $order_id         = $body->order_id;

        write_log('[TuTLog] Order ID: '.$order_id.' - '.$log_instance);

        //#Delivery
        $delivery         = $body->delivery;

        //#Autor Original
        $autorOriginal    = null;
        if ($body->autor)
        $autorOriginal    = $body->autor;

        $delivery_content = $body->content;
        $delivery_title   = str_replace(['.docx','.doc','.DOCX','.DOC'],'',$delivery->title);
        if ($newTitle) $delivery_title = $newTitle;

        $result           = MediaHelper::HTMLSCRtoAttachment($delivery_content);
        $delivery_content = $result->html;
        $attachmentArray  = $result->attachments;

        $delivery_content = WPContentHelper::transform($delivery_content);

        $post = OrdersLocal::getPostByOrder($order_id);
        $orderLocalDebugPattern = OrdersLocal::getOrderLocalByOrder($order_id);
        write_log('[TuTLog] POST ID : '.$post.' - '.$log_instance);
        write_log($orderLocalDebugPattern);
        write_log('[TuTLog] --- ');


        if ($post) {
          write_log('[TuTLog] Determino que POST '.$post.' existe para '.$order_id.' - '.$log_instance);
          // Existe post
          $post = PostsRepository::updateAssocDraft($post, $delivery_title, $delivery_content);
          // Borrar todas las etiquetas
          wp_set_post_terms ($post, [], 'post_tag');
          // Borrar todas las categorias
          wp_set_post_terms ($post, [], 'category');
        }else{
          write_log('[TuTLog] Determino no existe un POST existe para '.$order_id.' - '.$log_instance);
          // No existe post
          $post = PostsRepository::createAssocDraft($delivery_title, $delivery_content, $postType, $statusDelivery, $autorOriginal);
          OrdersLocal::addPosttoOrder($order_id, $post);
          OrdersLocal::strikeOrder($order_id);
        }

        //exit(); <--- si se quiere hacer debug

        //var_dump($delivery->taxonomies);exit();


        // Registrar taxonomias
        if ($delivery && isset($delivery->taxonomies) && $delivery->taxonomies) {
          foreach ($delivery->taxonomies as $taxonomy) {

            //
            if (   strtolower($taxonomy->term_name) !== 'sin categoria'
                && strtolower($taxonomy->term_name) !== 'sin categoría'
                && strtolower($taxonomy->term_name) !== 'no category'
                && strtolower($taxonomy->term_name) !== 'uncategorized') {

              $term  = get_term_by('name', $taxonomy->term_name , $taxonomy->taxonomy);
              //check existence
              if($term == false) {
                $term = wp_insert_term($taxonomy->term_name, $taxonomy->taxonomy);
                $term_id = $term['term_id'];
              }else{
                $term_id = $term->term_id;
              }
              wp_set_post_terms( $post, [$term_id], $taxonomy->taxonomy, true );

            }

          }
        }

        // Si el post tiene mas de 1 categoria quitar 'Uncategorized'

        $categories = get_the_category( $post );

        $default = get_cat_name( get_option( 'default_category' ) );
        if (!$default) $default = 'Uncategorized';

        if( count( $categories ) >= 2 && in_category( $default, $post ) ) {
          wp_remove_object_terms( $post, $default, 'category' );
        }

        // Hacer que la primera imagen que se envie sea el attachmetn
        if ($attachmentArray && sizeof($attachmentArray)) {
          //wp_get_attachment_url( $attachment_id )
          //var_dump($attachmentArray);exit();
          foreach ($attachmentArray as $attachmentItem) {
            set_post_thumbnail($post, $attachmentItem['id']);
            break;
          }
        }

        if ($post) {
          Self::webhookDeliveryPublish($deliveryID, $post);
        }

        return $post;

      }else{
        var_dump($response->body);exit();
      }

      return null;

    }

    public static function webhookDeliveryPublish($delivery_id, $post_id) {

      //var_dump('TEST');exit();

      $request = Connection::post(TUTOPIC_BASE_SERVICE_URL.'/webhook-publish/delivery/'.$delivery_id, [
        'headers' => [
          "X-Requested-With"          => "XMLHttpRequest",
          "TuTopic-Api-Key"           => get_option('association_code'),
          'X-Origin-Site-Base-URL'    => get_site_url(),
          'Connection'                => 'Keep-Alive'
        ],
        'body'    => [
          'publish_url'       => get_permalink($post_id),
          'post_title'        => get_the_title($post_id),
          'post_status'       => get_post_status($post_id) //publish, future, draft, pending, private
        ]
      ]);
      //var_dump($request);exit();

    }


}

?>
