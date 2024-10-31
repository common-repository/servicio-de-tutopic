<?php namespace TuTopicPlugin\Popalo\Helpers;

/**
* @package     Utils
* @copyright   (C) 2020 - 2021 Tutopic
* @license     GNU General Public License v2 or later
* @license     http://www.gnu.org/licenses/gpl-2.0.html
* @author      Tutopic
*
* This file is part of "Tutopic para WordPress".
* "Tutopic para WordPress" is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License or (at your option) any later version.
* "Tutopic para WordPress" is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
*
* Opalo Disclaimer:
* Se uso a una derivacion no-oficial de "Opalo Framework" en una version inferior 1.3 (desarrollado por Nextscale) llamado "Popalo" adaptada a plugins para la realizacion del plugin "Servicio de TuTopic en Wordpress", sin embargo "Opalo Framework", aun no esta licenciado oficialmente y en un futuro se piensa oficalizar bajo la licencia MIT. este Disclaimer fue escrito originalmente en Español en 2021.
* Este archivo es una derivacion originalmente desarrollada para "Opalo Framework"
*
*/

use TuTopicPlugin\Popalo\Helpers\UserMeta;

class Utils {

  /*BACKEND*/
  const useSession = false;

  //Sanitize one Dictionary or Text Depending the case using Wp Sanitize Functions
  public static function sanitize_dictionary_or_text($object, $antiLoop = 0) {

    $antiLoop++;

    if ($antiLoop > 10)
      return $object;

    if (is_string($object) || is_numeric($object)) {
      // Si es un string o un numero, sanitizarlos normalmente
        $object = sanitize_text_field($object);
    }else if (is_array($object)){
      // Si es un arreglo, sanitizar cada propiedad del areglo
      foreach ($object as $key => $value)
        $object[$key]   = Self::sanitize_dictionary_or_text($value, $antiLoop);
    }else if (is_object($object)){
      // Si es un objeto, sanitizar cada propiedad del objeto
      foreach ($object as $key => $value)
        $object->{$key} = Self::sanitize_dictionary_or_text($value, $antiLoop);
    }

    return $object;

  }

 public static function sanitize_text_or_array_field($array_or_string) {
      if( is_string($array_or_string) ){
          $array_or_string = sanitize_text_field($array_or_string);
      }elseif( is_array($array_or_string) ){
          foreach ( $array_or_string as $key => &$value ) {
              if ( is_array( $value ) ) {
                  $value = sanitize_text_or_array_field($value);
              }
              else {
                  $value = sanitize_text_field( $value );
              }
          }
      }

      return $array_or_string;
  }

  public static function initializeNotices() {
    if (Self::useSession) {
      // Has Cache Problems in Nginx y Varnish
      add_action('init',      [ Self::class, 'myStartSession' ], 1);
      add_action('wp_logout', [ Self::class, 'myEndSession' ]);
      add_action('wp_login',  [ Self::class, 'myEndSession' ]);
    }else{
      // Void Cache Problems (Default Option)
      // # No initializacion required #
    }
  }

  /*Usado SOLO cuando es necesario usar sessiones para manejar las "Notices"*/
  function myStartSession() { if(!session_id()) { session_start(); } }
  function myEndSession() { session_destroy(); }
  /**/




  // El Sender URL es desde donde se envio el post, comunmente usando en admin post
  public static function getSenderUrl() {

    // Metodos de wordpress para obtenerlo (aveces falla, TODO: identificar por que)
    $sender_url = wp_get_referer();
    // Si lo enviamos manualmente en el form, usar ese
    if (isset($_POST['sender_url']))
      $sender_url = sanitize_text_field($_POST['sender_url']);

    return $sender_url;

  }

  public static function redirect($url, $status = 200) {
    wp_redirect($url);
    exit;status_header(500);die();
  }

  public static function redirectWithError($url, $resBody = null, $status = 500) {
    if (Self::useSession) {
      $_SESSION['res_error'] = Self::sanitize_dictionary_or_text($resBody);
    }else{
      UserMeta::addNotices('error', $resBody);
    }
    Self::redirect($url, 500);
  }

  public static function redirectWithSuccess($url, $resBody = null, $status = 200) {
    if (Self::useSession) {
      $_SESSION['res_success'] = Self::sanitize_dictionary_or_text($resBody);
    }else{
      UserMeta::addNotices('success', $resBody);
    }
    Self::redirect($url, 500);
  }




  /**/

  public static function isJson($string) {
   json_decode($string);
   return (json_last_error() == JSON_ERROR_NONE);
  }


  /*FRONTEND*/
  public static function showNotices() {



      $errorsNotices      = null;
      $successNotices     = null;

      if (Self::useSession) {
        #Metodo con SESSION
        // Si hay notices de error en la session
        if (isset($_SESSION['res_error'])) {

          $errorsNotices = Self::sanitize_dictionary_or_text($_SESSION['res_error']);
          // TODO: Sanitized Array

          // Marcar vista
          unset($_SESSION['res_error']);
        }
        // Si hay notices de success en la sesion
        if (isset($_SESSION['res_success'])) {

          $successNotices = Self::sanitize_dictionary_or_text($_SESSION['res_success']);
          // TODO: Sanitized Array

          // Marcar vista
          unset($_SESSION['res_success']);
        }
      }else{
        #Metodo con UserMetas
        // Si hay notices de error
        if (UserMeta::getNotices('error')) {
          $errorsNotices = UserMeta::getNotices('error');
          // Marcar vista
          UserMeta::deleteNotices('error');
        }
        // Si hay notices de success
        if (UserMeta::getNotices('success')) {
          $successNotices = UserMeta::getNotices('success');
          // Marcar vista
          UserMeta::deleteNotices('success');
        }
      }


      #RUTA
      // Si hay notices de error en la ruta
      if (isset($_GET['error'])) {
        // This is sanitized down By $errorsNotices = Self::sanitize_dictionary_or_text($errorsNotices);
        $errorDecoded = str_replace('\\','',urldecode(urldecode($_GET['error'])));
        $errorDecoded = ltrim($errorDecoded, '"');
        $errorDecoded = rtrim($errorDecoded, '"');
        $errorsNotices = $errorDecoded;
      }
      // Si hay notices de success en la ruta
      if (isset($_GET['success'])) {
        // This is sanitized down By $successNotices = Self::sanitize_dictionary_or_text($successNotices);
        $successDecoded = str_replace('\\','',urldecode(urldecode($_GET['success'])));
        $successDecoded = ltrim($successDecoded, '"');
        $successDecoded = rtrim($successDecoded, '"');
        $successNotices = $successDecoded;
      }

      #PREPROCESAMIENTO
      if ($errorsNotices  && is_string($errorsNotices)  && Self::isJson($errorsNotices))
        $errorsNotices = json_decode($errorsNotices);

      if ($successNotices && is_string($successNotices) &&  Self::isJson($successNotices))
        $successNotices = json_decode($successNotices);

      #Sanitizidado
      $errorsNotices = Self::sanitize_dictionary_or_text($errorsNotices);
      $successNotices = Self::sanitize_dictionary_or_text($successNotices);

      // Desacoplar errores
       if (isset($errorsNotices) && $errorsNotices) {
         ?>
          <div class="error notice">
            <?php
              if (is_array($errorsNotices) || is_object($errorsNotices)) {
                // Si es un arreglo...
                foreach ($errorsNotices as $error) {
                  if (is_array($error) || is_object($error)) {
                    // Si tambien es un arreglo...
                    foreach ($error as $error_single)
                      echo "<p>".esc_html($error_single)."</p>";
                  }else{
                    // Si esta vez no es un arreglo!
                    echo "<p>".esc_html($error)."</p>";
                  }
                }
              }else{
                // Si no es un arreglo...
                echo "<p>".esc_html($errorsNotices)."</p>";
              }
            ?>
          </div>
        <?php
      }

    // Desacoplar success
      if (isset($successNotices) && $successNotices) {
        ?>
         <div class="notice-success notice">
           <?php
             if (is_array($successNotices) || is_object($successNotices)) {
               // Si es un arreglo...
               foreach ($successNotices as $success) {
                 if (is_array($success) || is_object($success)) {
                   // Si tambien es un arreglo...
                   foreach ($success as $success_single)
                     echo "<p>".esc_html($success_single)."</p>";
                 }else{
                   // Si esta vez no es un arreglo!
                   echo "<p>".esc_html($success)."</p>";
                 }
               }
             }else{
               // Si no es un arreglo...
               echo "<p>".esc_html($successNotices)."</p>";
             }
           ?>
         </div>
       <?php
     }

  }


  /*Response and Continue Execution*/
  // Used in:
  // TuTopic.php:137
  public static function FakeResponse($response) {
    // Buffer all upcoming output...
    ob_start();
      // Send your response.
      echo json_encode($response);
      // Get the size of the output.
      $size = ob_get_length();
      header("Content-Encoding: none");
      header("Content-Length: {$size}");
      header("Connection: close");
      ob_end_flush();
      ob_flush();
    flush();
  }

}

?>
