<?php namespace TuTopicPlugin\Popalo\AdminMenu;

/**
* @package     OptionPage
* @copyright   (C) 2020 - 2021 Tutopic
* @license     GNU General Public License v2 or later
* @license     http://www.gnu.org/licenses/gpl-2.0.html
* @author      Tutopic
*
* This file is part of "Tutopic para WordPress".
* "Tutopic para WordPress" is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License or (at your option) any later version.
* "Tutopic para WordPress" is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
*
* Based on //https://wordpress.stackexchange.com/questions/41207/how-do-i-enqueue-styles-scripts-on-certain-wp-admin-pages
* Opalo Disclaimer:
* Se uso a una derivacion no-oficial de "Opalo Framework" en una version inferior 1.3 (desarrollado por Nextscale) llamado "Popalo" adaptada a plugins para la realizacion del plugin "Servicio de TuTopic en Wordpress", sin embargo "Opalo Framework", aun no esta licenciado oficialmente y en un futuro se piensa oficalizar bajo la licencia MIT. este Disclaimer fue escrito originalmente en Español en 2021.
* Este archivo es una derivacion originalmente desarrollada para "Opalo Framework"
*
*/

class OptionPage {

    protected $title;
    protected $menu_title;
    protected $capability;
    protected $menu_slug;
    protected $callback;
    protected $position;
    protected $styles_callback;
    protected $scripts_callback;

    // Constructor
    public function __construct($args = []) {
      // Recibir datos
      $this->title            = $args['title'];
      $this->menu_title       = $args['menu_title'];
      $this->capability       = $args['capability'];
      $this->menu_slug        = $args['menu_slug'];
      $this->callback         = $args['callback'];
      $this->position         = (isset($args['position'])) ? $args['position'] : null;
      $this->styles_callback  = (isset($args['styles_callback'])) ? $args['styles_callback'] : null;
      $this->scripts_callback = (isset($args['scripts_callback'])) ? $args['scripts_callback'] : null;

      // Agregar pagina de opciones
      $option = add_options_page($this->title, $this->menu_title, $this->capability, $this->menu_slug, $this->callback, $this->position);

      if ($cb = $this->styles_callback) {
        add_action( 'admin_print_styles-' . $option,  $cb );
      }
      if ($cb = $this->scripts_callback) {
        add_action( 'admin_print_scripts-' . $option, $cb );
      }//admin_enqueue_scripts

    }

}

?>
