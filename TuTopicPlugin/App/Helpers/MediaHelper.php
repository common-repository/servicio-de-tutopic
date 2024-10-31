<?php  namespace TuTopicPlugin\App\Helpers;

/**
* @package     MediaHelper
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

use DOMDocument;
use TuTopicPlugin\App\Helpers\MediasLocal;

class MediaHelper
{

    public static function HTMLExtractSCRImages($html) {

      $doc = new DOMDocument();
      @$doc->loadHTML($html);

      $tags = $doc->getElementsByTagName('img');

      $srcArray = [];
      foreach ($tags as $tag)
        $srcArray[] = $tag->getAttribute('src');

      return $srcArray;

    }

    public static function HTMLSCRtoAttachment($html) {

      $srcArray = Self::HTMLExtractSCRImages($html);
      $srcAttachArray = [];

      // Recorremos los SRC encontrados en el HTML
      foreach ($srcArray as $image_url) {

        // Sanitizamos la URL
        $image_url = esc_url_raw($image_url);

        // Obtengo Diccionario del Attachment por la url de la imagen
        $Media = MediasLocal::getMedia($image_url);

        if ($Media) {
          write_log('[TuTLog-IMG] Ya existe un MEDIA para: '.$image_url. ' es '.$Media['attachment_id']);
          // Si ya existe obtengo su srcID
          $srcID = $Media['attachment_id'];
        }else{
          // Si no existe, lo creo dentro de la biblioteca
          $srcID = Self::ImageToAttachment($image_url);
          // Y ademas lo guardo en las medias
          MediasLocal::putMedia(['origin' => esc_url_raw($image_url), 'attachment_id' => $srcID]);
        }

        $srcAttachArray[$image_url] = [
          'id'  =>  $srcID,
          'url' =>  esc_url(wp_get_attachment_url($srcID)),
        ];

      }

      foreach ($srcAttachArray as $attachID => $attach) {
        //$html = esc_html(str_replace($attachID, $attach['url'], $html));
        /*
          BUG: NO se puede sanitizar ni escapar el contenido que sera publicado en wordpress
          habran que hacer pruebas en TuTopic para que no se envie contenido erroneo
          y ademas fortalezer la API para que no la use un tercero

          Pero NO es posible sanitizar ni escapar el contenido HTML por que el contenido que se
          trae desde TuTopic es HTML y espera publicarse en el post como HTML, si se
          sanitiza, el contenido HTML de TuTopic mostrara las etiquetas, lo cual
          no es un comportamiento deseado
        */
        $html = str_replace($attachID, $attach['url'], $html);
      }

      return new class($html, $srcAttachArray) {
          public $html;
          public $attachments;
          public function __construct($html, $attachments) {
            $this->html = $html;
            $this->attachments = $attachments;
          }
      };

    }

    public static function ImageToAttachment($image_url) {

		    write_log('[TuTLog-Img] Descargando imagen: '.$image_url);

        $upload_dir = wp_upload_dir();

    		//write_log('[TuTLog-Img] Upload dir is: '.$upload_dir['path']);

    		/**/
        /* ALTERNATIVA 1 (FALLO):
        $arrContextOptions = array("ssl"=>array("verify_peer"=>false,"verify_peer_name"=>false));
        $image_data = file_get_contents( $image_url, false, stream_context_create($arrContextOptions) );*/

        /* ALTERNATIVA 2: */
    		$ch = curl_init();
    		curl_setopt($ch, CURLOPT_URL, $image_url);
    		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    		$image_data = curl_exec($ch);
    		curl_close($ch);

		    /**/

        $filename = basename( $image_url );

        if ( wp_mkdir_p( $upload_dir['path'] ) ) {
          $file = $upload_dir['path'] . '/' . $filename;
        }
        else {
          $file = $upload_dir['basedir'] . '/' . $filename;
        }

        file_put_contents( $file, $image_data );

        $wp_filetype = wp_check_filetype( $filename, null );

        $attachment = array(
          'post_mime_type' => $wp_filetype['type'],
          'post_title' => sanitize_file_name( $filename ),
          'post_content' => '',
          'post_status' => 'inherit'
        );

        $attach_id = wp_insert_attachment( $attachment, $file );
        require_once( ABSPATH . 'wp-admin/includes/image.php' );
        $attach_data = wp_generate_attachment_metadata( $attach_id, $file );
        wp_update_attachment_metadata( $attach_id, $attach_data );

        return $attach_id;

    }
}
