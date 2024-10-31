<?php

/**
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

$pointS_seo = TuTopicPlugin\Service\TuTopic::getPointSeo();

?>

<div class="row">
  <div class="col-12">
    <div class="separation_defautl">
      <h2 class="text-primary page-title text-bold mb-1">Bienvenido!</h2>
      <p  class="text-content text-dark mt-1 mb-1">Ahora estas en el panel del plugin de TuTopic, desde aqui podras crear pedidos y ver su estado para convertirlos en entradas o pagians de wordpress!.</p>
    </div>
  </div>
</div>

<!-- <hr/> -->
<hr class="separation-bar w-100" />

<div class="row">
  <div class="col-12">
    <div class="separation_defautl">
      <h4 class="text-primary text-bold mb-1">Puntaje de SEO</h4>
      <h5 class="text-content text-bold text-dark m-0"><?= $pointS_seo ?> Puntos</h5>
    </div>
  </div>
</div>


<div class="row p-5">
  <div class="col-12 notification-title-moptions mb-2">
    <div class="">
      <h2 class="text-dark page-title text-bold ">
        Notificaciones
      </h2>
    </div>
    <div class="clear-notification-div">
      <?php if (TuTopicPlugin\Popalo\Notifications::getNoticesCount()): ?>
        <form
          action="<?php echo admin_url( 'admin-post.php' ); ?>"
          method="post"
        >
          <input type="hidden" name="action" value="tutopic_service_clear_notifications" />
          <input type="hidden" name="sender_url" value="<?php echo admin_url( 'admin.php?page=tutopic-service-slug' ); ?>" />
          <button type="submit" class="btnPrimary btnSendOrder disabled-after-click">
            Limpiar notificaciones
          </button>
        </form>
      <?php endif; ?>
    </div>
  </div>
  <div class="col-12 notifications-home">
    <?php echo TuTopicPlugin\Popalo\Notifications::showNoticesPages(); ?>
  </div>
</div>

<style media="screen">
  .separation-bar {
    /*margin: 0px -30px!important;*/
    border-bottom: 1px solid rgba(177,182,188,1);
    margin: 10px 0px
  }
</style>
