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
  .modalBgBorderLeft.borderleft-naranja{
    background-image: url(<?php echo esc_url(TUTOPIC_PLUGIN_BASE_URL). '/assets/images/bg-naranja-modal.png'; ?>);
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

<div id="modal_verifyOrder" class="w3-modal">

    <form method="POST" action="<?php
        echo admin_url( 'admin-post.php' );
      ?>"
      class="w3-modal-content w3-card-4 w3-animate-zoom modalBgBorderLeft borderleft-naranja"
      >

      <input type="hidden" name="action" value="tutopic_service_deny_delivery" />
      <input type="hidden" name="delivery_id" id="service_deny_delivery_id" value="" />
      <input type="hidden" name="sender_url"
        value="                <?php
                        $items_page_aux = (integer) sanitize_text_field((isset($_GET["items_page"])) ? $_GET["items_page"] : 0);
                        $url_aux = admin_url( "admin.php?page=tutopic-orders&items_page=".$items_page_aux );
                        echo $url_aux;
                        ?>"
      />

      <div class="w3-container">
        <span onclick="document.getElementById('modal_verifyOrder').style.display='none'" class="w3-button btnCloseModal w3-display-topright text-secondary">&times;</span>

        <div class="modalTitleIcon">
          <i class="mr-2 text-secondary fa fa-frown-o"></i>
          <div class="modalTitle">
            <h1>Denegar entrega</h1>
            <div class="hrBottomOrange"></div>
          </div>
        </div>

        <p class="textCheckModal text-justify text-dark">
          Lamentamos haya quedado insatisfecho con su pedido, podría comentarnos que le ha disgustado o deberiamos corregir, para poder realizar una nueva entrega.
        </p>

        <div class="w-100">
          <label class="text-secondary text-bold labelTextarea" for="service_delivery_deny_comment">
            Comentario:
          </label>
          <textarea class="textareaComent" id="service_delivery_deny_comment" name="comment" rows="4"></textarea>
        </div>

      </div>

      <footer class="w3-container modalFooterActions mb-1">
        <a onclick="document.getElementById('modal_verifyOrder').style.display='none'" class="btnSecondaryOutline mr-1 btnCenterWidth">
          Cancelar
        </a>
        <button type="submit" class="btnSecondary btnHoverOutlineSecondary disabled-after-click mr-1 btnCenterWidth">
          Enviar comentarios
        </button>
      </footer>

    </form>

</div>
