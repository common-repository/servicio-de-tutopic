<?php namespace TuTopicPlugin\App\AdminMenu\Templates;

/**
* @package     Settings
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

class Settings {
  public function handle() {

    // Si el usuario no puede "manejar opciones" (Permisos del WP)
    if ( !current_user_can( 'manage_options' ) )  {
      wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
    }

    // Insertar Layout (Dentro del layout se hace la logica de la pagina)
    include TUTOPIC_PLUGIN_BASE_PATH.'/layouts/plugin.layout.php';

  }
}
