<?php namespace TuTopicPlugin\App\AdminMenu;

/**
* @package     AdminMenu
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

use TuTopicPlugin\Popalo\AdminMenu\OptionPage;
use TuTopicPlugin\Popalo\AdminMenu\MenuPage;
use TuTopicPlugin\Popalo\AdminMenu\SubMenuPage;
use TuTopicPlugin\Popalo\Notifications;

class AdminMenu {

    public function __construct() {
      add_action( 'admin_menu', [$this, 'adminMenu'] );
    }

    // Callback para registrar las options y admin menus
    public function adminMenu() {

      # Registrar Option "Ajustes de TuTopic"
      $this->registerOption([
        'title'             => 'Configuraciones de TuTopic',
        'menu_title'        => 'Ajustes de TuTopic',
        'capability'        => 'manage_options',
        'menu_slug'         => 'settings-tutopic-screen'
      ],new \TuTopicPlugin\App\AdminMenu\Templates\Settings());//settings_tutopic_function


      // Si hay una cuenta vinculada
      if (get_option('association_ready')) {
        # Registrar Menu "Crear pedido"
        /*$this->registerMenu([
          'title'             => 'Servicio de TuTopic',
          'menu_title'        => 'TuTopic',
          'capability'        => 'administrator',
          'menu_slug'         => 'create_page',
          'icon_url'          => TUTOPIC_PLUGIN_BASE_URL.'/assets/images/icon.png',
          'styles_callback'   => [$this, 'styles_create_order_page'],
          'scripts_callback'  => [$this, 'scripts_create_order_page'],
        ],new \TuTopicPlugin\App\AdminMenu\Templates\CreateOrder());//my_cool_plugin_settings_page
        # Registrar Menu "Pedidos"
        $this->registerMenu([
          'title'             => 'Pedidos de TuTopic',
          'menu_title'        => 'Pedidos',
          'capability'        => 'administrator',
          'menu_slug'         => 'tutopic-orders',
          'icon_url'          => TUTOPIC_PLUGIN_BASE_URL.'/assets/images/icon.png',
        ],new \TuTopicPlugin\App\AdminMenu\Templates\Orders());*/
        $this->registerMenu([
          'title'             => 'Servicio de TuTopic',
          'menu_title'        => 'TuTopic',
          'capability'        => 'administrator',
          'menu_slug'         => 'tutopic-service-slug',
          'icon_url'          => TUTOPIC_PLUGIN_BASE_URL.'/assets/images/icon.png',
          'callback'          => [new \TuTopicPlugin\App\AdminMenu\Templates\CreateOrder(),'handle'],
          'notification_count'=> Notifications::getNoticesCount(),
        ]);
        $this->registerSubMenu([
          'title'             => 'Crear pedido de TuTopic',
          'parent_slug'       => 'tutopic-service-slug',
          'menu_title'        => 'Crear pedido',
          'capability'        => 'administrator',
          'menu_slug'         => 'create_page',
          //'icon_url'          => TUTOPIC_PLUGIN_BASE_URL.'/assets/images/icon.png',
        ],new \TuTopicPlugin\App\AdminMenu\Templates\CreateOrder());//my_cool_plugin_settings_page*/
        # Registrar Menu "Pedidos"
        $this->registerSubMenu([
          'title'             => 'Pedidos de TuTopic',
          'parent_slug'       => 'tutopic-service-slug',
          'menu_title'        => 'Pedidos',
          'capability'        => 'administrator',
          'menu_slug'         => 'tutopic-orders',
          //'icon_url'          => TUTOPIC_PLUGIN_BASE_URL.'/assets/images/icon.png',
        ],new \TuTopicPlugin\App\AdminMenu\Templates\Orders());
      }
      //add_submenu_page


    }

    /*-----*/

    public function  styles_create_order_page() {

    }

    public function styles_settings_page() {

    }

    public function  scripts_create_order_page() {

    }

    public function scripts_settings_page() {

    }
    /*----*/

    // Funcion para submenus (TODO: pasar al padre)
    public function registerSubMenu($data, $class_callback = null) {
      // Meter en data el callback (No se deberia sanitizar un callback)
      if ($class_callback)
        $data['callback'] = [$class_callback, 'handle'];
      return new SubMenuPage($data);
    }

    // Funcion para menus (TODO: pasar al padre)
    public function registerMenu($data, $class_callback = null) {
      // Meter en data el callback (No se deberia sanitizar un callback)
      if ($class_callback)
        $data['callback'] = [$class_callback, 'handle'];
      return new MenuPage($data);
    }

    // Funcion para options (TODO: pasar al padre)
    public function registerOption($data, $class_callback = null) {
      // Meter en data el callback (No se deberia sanitizar un callback)
      if ($class_callback)
        $data['callback'] = [$class_callback,'handle'];
      return new OptionPage($data);
    }

}
