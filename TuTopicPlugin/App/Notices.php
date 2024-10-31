<?php namespace TuTopicPlugin\App;

/**
* @package     Notices
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

class Notices  {

  public function __construct() {
    // NOTICES globales:
    add_action('admin_notices', [$this, 'globalNotices']);
    // Controlador de notices:
    \TuTopicPlugin\Popalo\Helpers\Utils::showNotices();
  }

  public function globalNotices() {

    // Notices globales que deben cerrarse
      //TODO

    // Notices globales constantes

    $association_code   = get_option('association_code');
    $association_ready  = get_option('association_ready');

    global $pagenow;

    if (!$association_code && !$association_ready) {
      // Si no se encuentra vinculado pero tampoco hay codigo
      if ( $pagenow !== 'options-general.php' ):
        ?>
          <div class="notice notice-info is-dismissible">
            <p>
              El plugin de <strong>TuTopic</strong> no ha sido vinculado a ninguna cuenta de <strong>TuTopic</strong>, para poder configurarlo vaya a la pantalla de
              <a href="<?php echo admin_url( 'options-general.php?page=settings-tutopic-screen' );?>">
                <?php echo __('Settings');?>
              </a>.
            </p>
          </div>
        <?php
      endif;

    }else if (!$association_ready) {
      // Si no se encuentra vinculado y tampoco hay codigo
      //19fc0e448edd5720a77dbad43ee101aac3c4ab7c
      ?>
        <div class="notice notice-error is-dismissible">
          <p>
            En el plugin de <strong>TuTopic</strong> se ha configurado una API Key de vinculacion a <strong>TuTopic</strong>, pero esta es invalida o no esta activa, por lo que el plugin no esta vinculado, para poder configurarla vaya a la pantalla de
            <a href="<?php echo admin_url( 'options-general.php?page=settings-tutopic-screen' );?>">
              <?php echo __('Settings');?>
            </a>.
          </p>
        </div>
      <?php
    }

  }

}

?>
