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
    background-image: url(<?php echo esc_url(TUTOPIC_PLUGIN_BASE_URL). '/assets/images/transparencia-superior-girada-1.png'; ?>);
    padding-right: 60px !important;
  }
  @media (max-width: 500px){
    .modalBgBorderLeft{
      background-image: none !important;
      background-color: #fff;
      padding-left: 0px !important;
      padding-right: 0px !important;
    }
  }
</style>

<div id="modal_orderDetail" class="w3-modal">
    <style media="screen">
      .min-h-400 {
        min-height: 400px;
      }
    </style>
    <div class="w3-modal-content min-h-400 w3-card-4 w3-animate-zoom modalBgBorderLeft borderleft-superior-girada">

      <div class="w3-container">
        <span onclick="document.getElementById('modal_orderDetail').style.display='none'" class="w3-button btnCloseModal w3-display-topright text-primary">&times;</span>

        <div class="modalTitle">
          <h1>Detalles del pedido</h1>
          <div class="hrBottom"></div>
        </div>

        <h2 class="dataTitle d-none" id="ordertitle_field">
          Titulo:
          <span id="ordertitle" class="dataItem">
            No posee
          </span>
        </h2>
        <h2 class="dataTitle d-none" id="orderstate_field">
          Estado:
          <span id="orderstate" class="dataItem">
            No posee
          </span>
        </h2>
        <h2 class="dataTitle d-none" id="orderCost_field">
          Coste:
          <span id="orderCost" class="dataItem">
            No posee
          </span>
        </h2>
        <h2 class="dataTitle d-none" id="orderCode_field">
          Codigo:
          <span id="orderCode" class="dataItem">
            No posee
          </span>
        </h2>
        <h2 class="dataTitle d-none" id="orderQuality_field">
          Calidad:
          <span id="orderQuality" class="dataItem">
            No posee
          </span>
        </h2>
        <h2 class="dataTitle d-none" id="orderRedactor_field">
          Redactor:
          <span id="orderRedactor" class="dataItem">
            No posee
          </span>
        </h2>
        <h2 class="dataTitle d-none" id="orderDays_field">
          Dias de trabajo:
          <span id="orderDays" class="dataItem">
            No posee
          </span>
        </h2>
        <h2 class="dataTitle d-none" id="orderCreate_at_field">
          Creado el:
          <span id="orderCreate_at" class="dataItem">
            No posee
          </span>
        </h2>

        <div class="delivery_field d-none" id="delivery_field">
          <div class="hrBlueModal"></div>

          <h1 class="dataTitle title-large text-primary">
            Entrega <span id="order_delivery_id"></span> <br/>
          </h1>

          <h2 class="dataTitle">
            Comentarios del redactor: <br/>
            <span id="orderComent" class="dataItem">
              No posee
            </span>
          </h2>

          <div class="d-flex mt-2 mb-2">
            <a id="downloadFile" class="btnPrimaryOutline d-none" target="__blank" href="#">Descargar</a>
            <form method="POST" action="<?php echo admin_url( 'admin-post.php' ); ?>">
              <input type="hidden" name="action" value="tutopic_service_create_draft_delivery" />
              <input type="hidden" name="delivery_id" id="service_create_draft_delivery_id" value="" />
              <input type="hidden" name="sender_url"
                value="
                <?php
                $items_page_aux = (integer) sanitize_text_field((isset($_GET["items_page"])) ? $_GET["items_page"] : 0);
                $url_aux = admin_url( "admin.php?page=tutopic-orders&items_page=".$items_page_aux );
                echo $url_aux;
                ?>
                "
              />
              <button type="submit" id="viewDocumentWp" class="btnSecondaryOutline disabled-after-click d-none ml-1">
                Crear borrador
              </button>
            </form>
            <!--<a id="viewDocumentWp" class="btnPrimaryOutline d-none" href="#">Ver en wordpress</a>-->
          </div>

        </div>

      </div>

      <footer class="w3-container modalFooterActions mb-1 d-none" id="order_detail_buttons_field">
        <a id="btnDetail_Deny" class="btnSecondary btnSecondaryShadow btnHoverOutlineSecondary mr-1 btnCenterWidth">
          <i class="fa fa-ban"></i>
          Denegar
        </a>
        <a id="btnDetail_Fix" class="btnPrimary btnHoverOutlinePrimary mr-1 btnCenterWidth">
          <i class="fa fa-pencil"></i>
          Corregir
        </a>
        <a id="btnDetail_Approve" class="btnPrimary btnHoverOutlinePrimary mr-1 btnCenterWidth">
          <i class="fa fa-check"></i>
          Aceptar
        </a>
      </footer>

    </div>
</div>
