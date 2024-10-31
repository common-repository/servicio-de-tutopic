<?php namespace TuTopicPlugin\App\AdminPost;

/**
* @package     AdminPost
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

use TuTopicPlugin\Popalo\AdminPost\Form;

class AdminPost {

    /* Registrar formularios a los que se le puede hacer post */
    public function __construct() {
      # Registrar formulario "Servicio de autenticacion"
      $this->registerForm('tutopic_service_auth',
                          new \TuTopicPlugin\App\AdminPost\Forms\AuthServiceForm());
      # Registrar formulario "Servicio de creacion de ordenes"
      $this->registerForm('tutopic_service_create_order',
                          new \TuTopicPlugin\App\AdminPost\Forms\CreateOrderServiceForm());
      # Registrar formulario "Denegar entrega"
      $this->registerForm('tutopic_service_deny_delivery',
                          new \TuTopicPlugin\App\AdminPost\Forms\DenyDeliveryServiceForm());
      # Registrar formulario "Corregir entrega"
      $this->registerForm('tutopic_service_correction_delivery',
                          new \TuTopicPlugin\App\AdminPost\Forms\CorrectDeliveryServiceForm());
      # Registrar formulario "Aprobar entrega"
      $this->registerForm('tutopic_service_approve_delivery',
                          new \TuTopicPlugin\App\AdminPost\Forms\ApproveDeliveryServiceForm());
      # Registrar formulario "Crear borrador"
      $this->registerForm('tutopic_service_create_draft_delivery',
                          new \TuTopicPlugin\App\AdminPost\Forms\CreateDraftForm());
      # Registrar formulario "Eliminar TODAS las Notificaciones"
      $this->registerForm('tutopic_service_clear_notifications',
                          new \TuTopicPlugin\App\AdminPost\Forms\ClearAllNotificationsForm());
    }

    // Funcion para registrar formularios (TODO: pasar al padre)
    public function registerForm($form_name, $class) {
      return new Form($form_name, [$class,'handle']);
    }

}

?>
