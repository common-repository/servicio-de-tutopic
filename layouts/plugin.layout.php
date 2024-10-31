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

  // Comprobar estado actual de vinculacion
  TuTopicPlugin\Service\TuTopic::ComprobarVinculacion();
  // Obtener datos publicos
  $public_data = TuTopicPlugin\Service\TuTopic::getPublicData();
  // Datos de mi mismo
  $meData = null;
  if (get_option('association_ready')) {
    $meData = TuTopicPlugin\Service\TuTopic::getMe();
  }

?>

<style media="screen">
  /*Solo aparece en el Layout de TuTopic para el plugin*/
  #wpbody-content {
    background-image: url('<?php echo esc_url(TUTOPIC_PLUGIN_BASE_URL); ?>/assets/images/transparencia-superior-girada.png')!important;
    background-size: contain;
    background-repeat: no-repeat;
    background-repeat: repeat-y;
    background-size: 400px auto;
    background-position: right top;
    min-height: 100vh;
  }
</style>

<script type="text/javascript">
  /*Solo se ejecuta en el Layout de TuTopic para el plugin*/
  jQuery(document).ready(function($) {
    $(".check-for-tab").change((evt) => {
        const checkItem = $(evt.target);
        if(checkItem.attr('checked')) {
          const new_page = checkItem.attr('aria-controls');
          console.log('to => ', new_page)
          window.history.replaceState('', '', ttpcsvc_updateURLParameter(window.location.href, "page", new_page));
        }
    });
  });
</script>
<div class="wrap">
  <!-- NO BORRAR! o no apareceran las notices -->
  <h2 style="display:none;font-size:36px;">Warp None For Notices</h2>
</div>

<div class="main-layout">
  <div class="w-100 text-center mb-2 mt-2">
    <img class="imgTutopicLogo" src="<?php echo esc_url(TUTOPIC_PLUGIN_BASE_URL). '/assets/images/logo-tutopic.png'; ?>" alt="tutopic">
  </div>
  <?php if ($meData): ?>
    <div class="tabset mt-2">
      <div class="d-flex justify-end w-100 text-primary text-right mb-3 pr-2">
        <i class="fa fa-money iconOrdersSaldo"></i>
        <div class="footerDataComponent">
          <span class="labelDataComponent text-bold">Saldo total</span>
          <h2 style="line-height: 30px;" class="text-primary m-0" id="saldoTotal" >
            <?php echo esc_html($meData->credits); ?> EUR
          </h2>
        </div>
      </div>
    </div>
  <?php endif; ?>
  <div class="tabset">


    <?php if (get_option('association_ready')): ?>
      <!-- Tab 0 -->
      <input type="radio"  class="check-for-tab" name="tabset" id="tab0" aria-controls="tutopic-service-slug"
        <?php echo ($_GET['page'] === 'tutopic-service-slug') ? 'checked' : ''; ?>
      >
      <label class="ml-2 "   for="tab0">
        <i class="fa fa-home"></i>
        <span class="d-none-767">
          Dashboard
        </span>
      </label>

      <!-- Tab 1 -->
      <input type="radio" class="check-for-tab" name="tabset" id="tab1" aria-controls="create_page"
      <?php echo ($_GET['page'] === 'create_page') ? 'checked' : ''; ?>>
      <label class="ml-2 "  for="tab1">
        <i class="fa fa-pencil"></i>
        <span class="d-none-767">
          Hacer pedido
        </span>
      </label>

      <!-- Tab 2 -->
      <input type="radio" class="check-for-tab" name="tabset" id="tab2" aria-controls="tutopic-orders"
        <?php echo ($_GET['page'] === 'tutopic-orders') ? 'checked' : ''; ?>>
      <label class="" for="tab2">
        <i class="fa fa-inbox"></i>
        <span class="d-none-767">
          Mis pedidos
        </span>
      </label>
    <?php endif; ?>

    <!-- Tab 3 -->
    <input type="radio" class="check-for-tab" name="tabset" id="tab3" aria-controls="settings-tutopic-screen"
      <?php echo ($_GET['page'] === 'settings-tutopic-screen') ? 'checked' : ''; ?>>
    <label class="" for="tab3">
      <i class="fa fa-sliders"></i>
      <span class="d-none-767" >
        Configuración
      </span>
    </label>

    <a class="btnSecondary btnIrTutopic" href="<?php echo esc_url(TUTOPIC_BASE_URL);?>">
      <i class="fa fa-sign-out"></i>
      <span class="d-none-767">
        Ir a tutopic
      </span>
    </a>

    <div class="tab-panels">

      <?php if (get_option('association_ready')): ?>

        <!-- Home? -->
        <section id="tutopic-service-slug" class="tab-panel shadow">
          <?php include TUTOPIC_PLUGIN_BASE_PATH.'/templates/home.template.php'; ?>
        </section>

        <!-- Crear pedidos -->
        <section id="create_page" class="tab-panel shadow">
          <?php include TUTOPIC_PLUGIN_BASE_PATH.'/templates/create-page.template.php'; ?>
        </section>

        <!-- Mis pedidos -->
        <section id="tutopic-orders" class="tab-panel shadow p-0 pt-3 pb-3">
          <?php include TUTOPIC_PLUGIN_BASE_PATH.'/templates/orders.template.php'; ?>
          <!-- <h2>Mis pedidos</h2> -->
        </section>
      <?php endif; ?>

      <!-- Configuracion -->
      <section id="settings-tutopic-screen" class="tab-panel shadow">
        <?php include TUTOPIC_PLUGIN_BASE_PATH.'/templates/options-page.template.php'; ?>
        <!-- <h2>Configuración</h2> -->
      </section>

    </div>

  </div>
</div>
