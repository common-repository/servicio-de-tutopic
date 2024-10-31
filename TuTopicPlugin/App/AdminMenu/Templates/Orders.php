<?php namespace TuTopicPlugin\App\AdminMenu\Templates;

/**
* @package     Orders
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

class Orders {
  public function handle() {

    // Comproba que la vinculacion siga siendo valida
    $response = \TuTopicPlugin\Service\TuTopic::ComprobarVinculacion();
    // Api Key Invalida.
    if ($response->status !== 200) { wp_die( __( $response->body ) ); }

    // Insertar Layout (Dentro del layout se hace la logica de la pagina)
    include TUTOPIC_PLUGIN_BASE_PATH.'/layouts/plugin.layout.php';

  }
}