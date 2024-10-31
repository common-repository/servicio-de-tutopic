<?php namespace TuTopicPlugin\Popalo\AdminPost;

/**
* @package     Form
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

class Form {

    protected $name;
    protected $callback;

    // Constructor
    public function __construct($name, $callback) {
      //Propiedades
      $this->name     = $name;
      $this->callback = $callback;

      // Agregamos el action que llamara a los forms
      add_action( 'admin_post_nopriv_'.$this->name, $this->callback );
      add_action( 'admin_post_'.$this->name, $this->callback );
    }

}

?>
