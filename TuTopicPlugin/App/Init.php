<?php namespace TuTopicPlugin\App;

/**
* @package     InitParent
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


//Popalo (el Opalo de los plugins [WIP])
use TuTopicPlugin\Popalo\InitParent;

//App
use TuTopicPlugin\App\AdminPost\AdminPost;
use TuTopicPlugin\App\AdminMenu\AdminMenu;
use TuTopicPlugin\App\Notices;
use TuTopicPlugin\App\ApiRest;
use TuTopicPlugin\Popalo\Notifications;

class Init extends InitParent {

    // Init
    public function init() {
      \TuTopicPlugin\App\Helpers\OrdersLocal::initialize();
      \TuTopicPlugin\App\Helpers\MediasLocal::initialize();

      // :)
      $api_rest_service = ApiRest::init();
    }

    // Registrar ajustes
    public function register_settings() {

      // Registramos las opciones de configuracion de TuTopic
      register_setting( 'tutopic-settings', 'association_code' );
      register_setting( 'tutopic-settings', 'association_ready' );

      // Registramos otras opciones de configuracion de TuTopic
      register_setting( 'tutopic-settings', 'association_competences' );
      if (!get_option('association_competences')) update_option('association_competences',[]);
      register_setting( 'tutopic-settings', 'association_user' );

      // Contrubuir al auto original
      register_setting( 'tutopic-settings', 'contribuite_to_original_author' );
      // Auto-publish
      register_setting( 'tutopic-settings', 'auto_publish_in_approbe' );
      // Auto-Center
      register_setting( 'tutopic-settings', 'auto_center_in_publish' );
      // Lista de ordenes que han creado un articulo equivalente
      register_setting( 'tutopic-settings', 'orders_local' );
      if (!get_option('orders_local')) update_option('orders_local',[]);
      // Lista de 'medios' que han creado una foto/video/recurso equivalente de Tutopic en el servicio del plugin
      register_setting( 'tutopic-settings', 'medias_local' );
      if (!get_option('medias_local')) update_option('medias_local',[]);

      // Registramos los formularios a los que se les puede hacer post (TODO: pasar a padre [Holliwood])
      $admin_post = new AdminPost();

      // Registramos el menu-admin (options y menus) (TODO: pasar a padre [Holliwood])
      $admin_menu = new AdminMenu();

      // Servicio de notificaciones (TODO: pasar a padre [Holliwood])
      $notifications_service = new Notifications();

      // Hacemos que el plugin tenga un boton de "ajustes" en gestion de plugins
      add_filter('plugin_action_links_'.plugin_basename(__FILE__), 'salcode_add_plugin_page_settings_link');
      function salcode_add_plugin_page_settings_link( $links ) {
      	$links[] = '<a href="' .
      		admin_url( 'options-general.php?page=settings-tutopic-screen' ) .
      		'">' . __('Settings') . '</a>';
      	return $links;
      }

      // Muestra notices (errores/sucesss/info)
      new Notices();

    }

    // Registrar ajustes de admin
    public function register_admin_settings() {
      //
    }

}

?>
