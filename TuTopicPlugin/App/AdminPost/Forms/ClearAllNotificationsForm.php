<?php namespace TuTopicPlugin\App\AdminPost\Forms;

/**
* @package     ClearAllNotificationsForm
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

use TuTopicPlugin\Popalo\Helpers\Utils as PopaloUtils;
use TuTopicPlugin\Popalo\Notifications;
use TuTopicPlugin\Popalo\Connection;
use TuTopicPlugin\Service\TuTopic;
/**
  * Borrar TODAS las notificaciones
  */
class ClearAllNotificationsForm {

    function handle($data) {

      /*
        IDEA: No hace falta una sanitizacion aqui por que ya esta sanitizado en
        PopaloUtils::getSenderUrl(), esto viola el principio de DRY, pero bueno...
        supongo que re-sanitizarlo tampoco hara daño a nadie
      */
      $sender_url = PopaloUtils::sanitize_dictionary_or_text(PopaloUtils::getSenderUrl());
      Notifications::deleteAllNotifications();
      PopaloUtils::redirect($sender_url);

    }

}

?>
