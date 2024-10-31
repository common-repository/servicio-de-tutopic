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
?>

<style media="screen">
  .modalBgBorderLeft.borderleft-superior-girada{
    background-image: url(
        <?php echo esc_url(TUTOPIC_PLUGIN_BASE_URL).
        '/assets/images/transparencia-superior-girada-1.png'; ?>
      );
    padding-right: 60px !important;
  }
  @media (max-width: 600px){
    .modalBgBorderLeft{
      background-image: none !important;
      background-color: #fff;
      padding-left: 0px !important;
      padding-right: 0px !important;
    }
  }
</style>
<?php use TuTopicPlugin\App\Helpers\OrdersLocal; ?>
<div id="modal_checkOrder" class="w3-modal">
    <form
      method="POST" action="<?php echo admin_url( 'admin-post.php' ); ?>"
      class="w3-modal-content w3-card-4 w3-animate-zoom modalBgBorderLeft borderleft-superior-girada"
      >

      <input type="hidden" name="action" value="tutopic_service_approve_delivery" />
      <input type="hidden" name="delivery_id" id="service_approve_delivery_id" value="" />
      <input type="hidden" name="sender_url"
        value="<?php
        $items_page_aux = (integer) sanitize_text_field((isset($_GET["items_page"])) ? $_GET["items_page"] : 0);
        $url_aux = admin_url( "admin.php?page=tutopic-orders&items_page=".$items_page_aux );
        echo $url_aux;
        ?>"
      />

      <div class="w3-container">
        <span onclick="document.getElementById('modal_checkOrder').style.display='none'" class="w3-button btnCloseModal w3-display-topright text-primary">&times;</span>

        <div class="modalTitleIcon">
          <i class="mr-2 text-primary fa fa-flag-checkered"></i>
          <div class="modalTitle">
            <h1>Aprobar Entrega</h1>
            <div class="hrBottom"></div>
          </div>
        </div>

        <p class="textCheckModal text-justify text-dark">
          Nos alegra hayas optado por nuestros servicios, esperamos te hayan funcionado.
        </p>
        <p class="textCheckModal text-justify text-dark">
          Antes de terminar, nos gustaría saber donde deberíamos guardar tu pedido, seleccione alguna de las siguientes opciones.
        </p>

        <div class="mb-3">
          <?php if (false): ?>
            <input style="display: none" type="file" id="fileSave" name="fileSave" value="">
            <label for="fileSave" class="btnPrimaryOutline btnAddImage">
              <i class="fa fa-upload"></i>
              Lugar de guardado
            </label>
          <?php endif; ?>

          <?php if (false): ?>
            <div class="field-container">
              <select class="field-input"
                      id="approve_post_type"
                      name="approve_post_type" placeholder=" ">
                <?php
                $postTypes = get_post_types([], 'objects');
                /*var_dump($postTypes);*/
                if( $postTypes ){
                  foreach( $postTypes as $postType ){
                    if (!in_array($postType->name,[
                      'attachment','revision','nav_menu_item','custom_css','customize_changeset',
                      'oembed_cache','user_request','wp_block'
                    ])):
                    ?>
                      <option
                        value="<?php echo esc_attr($postType->name); ?>"
                      >
                        <?php echo esc_html($postType->label); ?>
                      </option>
                    <?php
                    endif;
                  }
                }
                ?>
              </select>
              <label class="field-placeholder" for="approve_post_type">Tipo de post</label>
            </div>
          <?php else: ?>
              <input type="hidden" name="approve_post_type" id="approve_post_type" value="post" />
          <?php endif; ?>

            <div class="field-container">
              <input class="field-input" id="service_delivery_new_title" name="post_title" type="text" placeholder=" " />
              <label class="field-placeholder" for="post_title">Titulo de guardado</label>
            </div>


        </div>
      </div>

      <footer class="w3-container modalFooterActions mb-1">
        <button type="submit" class="btnPrimary btnHoverOutlinePrimary mr-1 disabled-after-click btnCenterWidth">
          Aceptar
        </button>
      </footer>

    </form>
</div>
