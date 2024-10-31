<?php namespace TuTopicPlugin\Popalo;

/**
* @package     Notifications
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

use TuTopicPlugin\Popalo\Notifications\OptionsDriver;

class Notifications {

    /**
      * La idea es que esta clase tenga en un futuro varios "Drivers" es decir
      * formas de guardar notificaciones, y una de ellas sea desde la base de datos
      * pero por ahora sera solo por options
      */

    // Driver y sistema
    public static $driver       = 'options';
    public static $keyName      = 'popalo-notifications';
    // Modelo de datos de notificaciones
    public static $primaryKey   = '__id';
    public static $seeKey       = '__see';
    public static $classKey     = '__class';

    public function __construct() {
      //add_action('init', [$this, 'init'] );
      $this->init();
    }

    public function init() {
      switch (Self::$driver) {
        default:
        case 'options':

          register_setting( 'popalo', Self::$keyName );
          if (!Self::getNotifications()) {
            Self::setNotifications([]);
          }

          // Mirar todas
          $this->comprobeAllSee();

          break;
      }
    }

    // Mira las notificaciones que deberian mirarse por parametros $__GET
    public function comprobeAllSee() {
      // Recorremos todas las notificaciones que no se han visto
      foreach (Self::get() as $notification) {
        // Obtenemos el ID
        $id = $notification->{Self::$primaryKey};
        // Comprobamos si se envio un "ver" de esa notificacion
        if (isset($_GET["notice_{$id}_see"])) {
          Self::seeById($id);
        }
      }
    }

    /**/

    public static function getNotifications($array = false) {
      switch (Self::$driver) {
        default:
        case 'options':
          if ($result = get_option( Self::$keyName )) {
            return json_decode($result, $array);
          }
          break;
      }
      return null;
    }

    public static function setNotifications($data = []) {
      switch (Self::$driver) {
        default:
        case 'options':
          update_option( Self::$keyName , json_encode($data));
          break;
      }
    }

    public static function deleteAllNotifications() {
      Self::setNotifications([]);
    }

    public static function addNotification($data = null) {
      switch (Self::$driver) {
        default:
        case 'options':

          // Arreglo de notificaciones vacio
          $notifications = [];

          // Si es que tiene algo, se fusiona este arreglo vacio con el anterior arreglo de notificaciones guardado
          if ($notificationsStoreds = Self::getNotifications()) {
            if (is_array($notificationsStoreds)) {
              $notifications = array_merge($notifications, $notificationsStoreds);
            }
          }

          // Si las notificacion que vas a agregar no tiene una clase, se pone por defecto info (Self::$classKey === '__class')
          if (!isset($data[Self::$classKey]) || empty($data[Self::$classKey]))
            $data[Self::$classKey] = 'notice-info';

          // Si las notificacion que vas a agregar no tiene un ID, se genera uno (Self::$primaryKey === '__id')
          if (!isset($data[Self::$primaryKey]) || empty($data[Self::$primaryKey]))
            $data[Self::$primaryKey] = uniqid();

          // Si las notificacion que vas a agregar no tiene un SEE, se genera uno (Self::$seeKey === '__see')
          if (!isset($data[Self::$seeKey]))
            $data[Self::$seeKey] = false;

          // Se agrega la notificacion a los datos
          $notifications[] = $data;

          // Se guardan las notificaciones
          Self::setNotifications($notifications);

          break;
      }
    }

    public static function removeById($id = null) {
      switch (Self::$driver) {
        default:
        case 'options':

              // Arreglo de notificaciones vacio
              $notifications = [];
              // Si es que tiene algo, se fusiona este arreglo vacio con el anterior arreglo de notificaciones guardado
              if ($notificationsStoreds = Self::getNotifications()) {
                if (is_array($notificationsStoreds)) {
                  $notifications = array_merge($notifications, $notificationsStoreds);
                }
              }


              foreach ($notifications as $index => $item) {
                if ($item->{Self::$primaryKey} == $id) {
                    unset($notifications[$index]);
                }
              }

              // Se guardan las notificaciones
              Self::setNotifications($notifications);

          break;
      }
    }

    public static function updateByID($id = null, $toUpdate = null) {
      switch (Self::$driver) {
        default:
        case 'options':

            // Arreglo de notificaciones vacio
            $notifications = [];
            // Si es que tiene algo, se fusiona este arreglo vacio con el anterior arreglo de notificaciones guardado
            if ($notificationsStoreds = Self::getNotifications(true)) {
              if (is_array($notificationsStoreds)) {
                $notifications = array_merge($notifications, $notificationsStoreds);
              }
            }

            foreach ($notifications as $index => $item) {
              // Si la encuentra
              if ($item[Self::$primaryKey] == $id) {
                  // Si hay cosas que actualizar
                  if ($toUpdate) {
                    // Actualizo propiedad por propiedad
                    foreach ($toUpdate as $keyUpdate => $valueUpdate)
                      $notifications[$index][$keyUpdate] = $valueUpdate;
                  }
              }
            }

            // Se guardan las notificaciones
            Self::setNotifications($notifications);

          break;
      }
    }

    public static function add($message, $class = null, $message_full = null) {

      $data = [];

      if (gettype($message) === 'string') {
        // Si enviaste un string como primer argumento, el message solo es el message del data
        $data['message']            = $message;
      }else if (gettype($message) === 'array') {
        // Si enviaste un array como primer argumento, todo "message" es el data
        $data                       = $message;
      }

      // Si enviaste un segundo argumento, este sera la clase dentro del data
      if ($class) {
        $data['class']              = $class;
      }

      // Si enviaste un tercer argumento, este sera la clase dentro de message_full
      if ($message_full) {
        $data['message_full']       = $message_full;
      }

      switch (Self::$driver) {
        default:
        case 'options':

          return Self::addNotification([
            '__class'       => (isset($data['class'])) ? $data['class'] : null,
            'message'       => $data['message'],
            'message_full'  => $data['message_full']
          ]);

          break;
      }

      return null;

    }

    public static function getQuery($query = ['see' => false]) {
      switch (Self::$driver) {
        default:
        case 'options':

            $allItems =  Self::getNotifications();
            $items    =  [];

            foreach ($allItems as $index => $item) {
              // Guardamos el "Index" del item
              $item->__index = $index;
              // Si el Query requiere un 'see' en especifico
              if (isset($query['see'])) {
                // Agregar el item si su 'see' coincide con el 'see' del query requerido (Self::$seeKey === '__see')
                if ($item->{Self::$seeKey} == $query['see'])
                  $items[] = $item;
              }
              // Si el Query requiere un 'id' en especifico
              if (isset($query['id'])) {
                // Agregar el item si su 'id' coincide con el 'id' del query requerido (Self::$primaryKey === '__id')
                if ($item->{Self::$primaryKey} == $query['id'])
                  $items[] = $item;
              }
            }

            return $items;

          break;
      }
      return null;
    }

    /**/

    // Obtiene una notificacion en especifico por su ID
    public static function getByID($id) {
      $query = Self::getQuery(['id' => $id]);
      if ($query && sizeof($query)) {
        return $query[0];
      }
      return null;
    }

    // Mara como "vista" una notificacion en especifico por su ID
    public static function seeById($id) {
      return Self::updateByID($id,[Self::$seeKey => true]);
    }

    // Obtiene un arreglo de notificaciones que no haz visto
    public static function get() {
      return Self::getQuery(['see' => false]);
    }

    // Crea una nueva notificacion
    public static function create($message, $class = null, $message_full) {
      return Self::add($message, $class, $message_full);
    }

    public static function showNotices() {

      foreach (Self::get() as $notification) {
        $id = $notification->{Self::$primaryKey};
        $see_url = add_query_arg(["notice_{$id}_see" => true]);
        ?>
            <div class="notice <?php echo $notification->{Self::$classKey};?> is-dismissible popalo-notification-dimiss" popalopropid="<?php echo $id;?>"  >

              <p><?php echo $notification->message;?></p>
              <?php /*<br/><p><?php echo $notification->message_full;?></p> */ ?>

            </div>
        <?php
      }

    }

    public static function getNoticesCount() {

      $notifications = Self::get();
      return sizeof($notifications);

    }

    public static function showNoticesPages() {

      $notifications = Self::get();
      $notifications = array_reverse($notifications);

      foreach ($notifications as $notification) {
        $id = $notification->{Self::$primaryKey};
        $see_url = add_query_arg(["notice_{$id}_see" => true]);
        ?>

            <button
              id="tt-header-<?php echo esc_attr($id);?>"
              class="accordion notify-tutopic-header text-dark ff-tutopic"
              >
              <div class="center-circle-notify-container">
                <div class="center-cicle-notify"></div>
              </div>
              <span class="span-notify">
                <?php echo esc_html($notification->message);?>
              </span>
              <div  class="div-delete-notify"
                    popalopropid="<?php echo esc_attr($id);?>"
                >
                <i class="fa fa-times"></i>
              </div>
            </button>
            <div  id="tt-div-<?php echo esc_attr($id);?>"
                  class="panel text-gray notify-tutopic-panel ff-tutopic"
            >
              <p class="p-notify">
                <?php echo esc_html($notification->message_full);?>
              </p>
              <small class="text-dark pb-5 hover-a-btn">
                <?php if (!empty($notification->cta_tex) && !empty($notification->cta_url)): ?>
                  <a href="<?php echo esc_url($notification->cta_url);?>" >
                    <?php echo esc_html($notification->cta_text);?>
                  </a>
                <?php endif; ?>
              </small>
            </div>

        <?php
      }

    }

}

?>
