<?php  namespace TuTopicPlugin\App\Helpers;

/**
* @package     WPContentHelper
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

class WPContentHelper
{
  // Transformacion
  public static function transform($content) {

    // Si el ajuste de auto-centrar esta activado, obtener todas las imagenes ya auto-centradas
    if (get_option('auto_center_in_publish'))
      $content = Self::centerAllImages($content);

    return $content;

  }

  // Centrar todas las imagenes
  public static function centerAllImages($content) {

    $tags = Self::getImgTags($content);

    $paramCenter = '"align":"center"';
    $newTags = [];
    foreach ($tags as $tag) {

      $oldTag = $tag;

      if ($param = Self::tagHaveParam($tag)) {
        // Si tiene parametro
        if ($param !== $paramCenter) {
          // Si no es un parametro de centro
          $tag = str_replace($param, $paramCenter, $tag);

          if (in_array($param, ['"align":"left"','"align":"right"','"align":"wide"','"align":"full"'])) {
            $tag = str_replace(['alignleft','alignright','aligncenter','alignfull','alignwide'],'aligncenter',$tag);
          }

        }
      }else{

        // Si no tiene parametro
        if (strpos($tag, '{')) {
          // Si tiene otro tipo de parametros
          $tag = str_replace(['<!-- wp:image {','<!-- wp:image{','<!-- wp:image  {'], '<!-- wp:image {'.$paramCenter.',"className":"aligncenter",', $tag);
        }else{
          $tag = str_replace('<!-- wp:image', '<!-- wp:image {'.$paramCenter.'}', $tag);
        }

        #$tag = str_replace(['wp-block-image','is-resized'])

        if (strpos($tag, 'div')) {
          // Si tiene DIV
          $tag = str_replace(['figure class="'],'figure class="aligncenter ',$tag);
        }

      }

      if (!$param || $param !== $paramCenter) {
        // Si no tiene DIV
        if (!strpos($tag, 'div')) {
          // Eliminamos el wp-block-image del figure
          $tag = str_replace(['wp-block-image'],'',$tag);
          // Obtenemos el ID del figure
          $idBlock = Self::get_string_between($tag, 'id="', '"');
          // Eliminamos el id con todo y atributo del figure
          $tag = str_replace('id="'.$idBlock.'"','',$tag);
          // Agregamos el div al principio del figure
          $tag = str_replace('<figure','<div id="'.$idBlock.'" class="wp-block-image aligncenter"><figure',$tag);
          // Agregamos el div al final del figure
          $tag = str_replace('</figure>','</figure></div>',$tag);

          $tag = str_replace(['figure class="'],'figure class="aligncenter ',$tag);
        }
      }

      //echo htmlentities($tag).'</br></br>';//<--- si se quiere hacer debug
      $newTags[$oldTag] = $tag;
    }

    //Replace
    foreach ($newTags as $oldTag => $newTag) {
      $content = str_replace($oldTag,$newTag,$content);
    }

    // Retornamos el contenido
    /*
      BUG: NO se puede sanitizar el contenido que sera publicado en wordpress
      habran que hacer pruebas en TuTopic para que no se envie contenido erroneo
      y ademas fortalezer la API para que no la use un tercero

      Pero NO es posible sanitizar el contenido HTML por que el contenido que se
      trae desde TuTopic es HTML y espera publicarse en el post como HTML, si se
      sanitiza, el contenido HTML de TuTopic mostrara las etiquetas
    */
    #return wp_filter_post_kses($content);
    return $content;

  }

  public static function tagHaveParam($tag) {
    $params = ['"align":"wide"',
        '"align":"full"',
        '"align":"left"',
        '"align":"right"',
        '"align":"center"'];

    $haveParam = null;
    foreach ($params as $param) {
      if (strpos($tag, $param)) {
        $haveParam = $param;
        break;
      }
    }
    return $haveParam;
  }

  public static function getImgTags($content) {
    $nContent = $content;
    $tags = [];
    while ($tag = Self::get_string_between($nContent, '<!-- wp:image', '<!-- /wp:image -->', true)) {
      $tags[] = $tag;
      $nContent = str_replace($tag, '', $nContent);
    }
    return $tags;
  }

  public static function get_string_between($string, $start, $end, $persist = false){

      $string = ' ' . $string;

      $ini = strpos($string, $start);
      if ($ini == 0) return null;

      $ini += strlen($start);
      $len = strpos($string, $end, $ini) - $ini;

      if (!$persist)
        return substr($string, $ini, $len);

      return $start.substr($string, $ini, $len).$end;

  }


}
