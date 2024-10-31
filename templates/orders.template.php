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

  \TuTopicPlugin\App\Helpers\OrdersLocal::get();
  // Comprobar estado actual de vinculacion
  TuTopicPlugin\Service\TuTopic::ComprobarVinculacion();


  $items_page_aux = (integer) sanitize_text_field((isset($_GET["items_page"])) ? $_GET["items_page"] : 0);
  $orders = TuTopicPlugin\Service\TuTopic::getOrders($items_page_aux);

?>

<?php use TuTopicPlugin\App\Helpers\OrdersLocal; ?>
<?php
  /*
    var_dump($orders);
    text-decoration: overline;
  */
?>


<div>
  <table class="tutopic-table">
    <tr>
      <th>Titulo</th>
      <th class="d-none-450">Estado</th>
      <th class="d-none-850">Codigo</th>
      <th class="d-none-500">Coste</th>
      <th class="d-none-1024">Entrega</th>
      <th>&nbsp;</th>
    </tr>
    <?php foreach($orders->items as $order): ?>
      <?php $haveTachado = OrdersLocal::isOrderStriked($order->id); ?>
      <tr>
        <td class="<?php echo ($haveTachado) ? 'texto-tachado' : ''; ?>">
          <?php echo esc_html($order->title);?>
        </td>
        <td class="d-none-450 text-primary text-center <?php echo ($haveTachado) ? 'texto-tachado' : ''; ?>">
          <?php echo esc_html($order->estado);?>
        </td>
        <td class="d-none-850 text-center <?php echo ($haveTachado) ? 'texto-tachado' : ''; ?>">
          <?php echo esc_html($order->public_code);?>
        </td>
        <td class="d-none-500 text-primary text-center <?php echo ($haveTachado) ? 'texto-tachado' : ''; ?>">
          <?php echo esc_html($order->balance_charged);?> EUR
        </td>
        <td class="d-none-1024 text-primary text-center <?php echo ($haveTachado) ? 'texto-tachado' : ''; ?>">
          <?php if ($order->last_delivery_public): ?>
            <?php echo
                  ($order->last_delivery_public->enviado_a_cliente_el) ?
                  esc_html($order->last_delivery_public->enviado_a_cliente_el) :
                  esc_html($order->last_delivery_public->enviado_el)
                  ;
            ?>
          <?php else: ?>
            <span class="text-gray">Sin entregas</span>
          <?php endif; ?>
        </td>
        <td class="text-center">
          <?php  /*onclick="openModal('<?php echo $order->public_code; ?>')"*/ ?>
          <button id="<?php echo esc_attr($order->public_code); ?>"
            class="btnPrimary btnViewOrder btnViewOrderAction"
            >
            <i class="fa fa-eye"></i>
            <span class="d-none-767">
              Detalle
            </span>
          </button>
        </td>
      </tr>
    <?php endforeach;?>
    <?php /*var_dump($orders->paginate); */?>
  </table>
  <div class="center w-100 mt-3">
    <div class="pagination">
      <?php if ($orders->paginate->back): ?>
        <a href="<?php echo admin_url( "admin.php?page=".'tutopic-orders'."&items_".$orders->paginate->back->url ); ?>">&laquo;</a>
      <?php endif; ?>
      <?php foreach ($orders->paginate->pages as $key => $page): ?>
        <a  class="<?php echo ($page->current ? 'active' : ''); ?>"
            href="<?php echo admin_url( "admin.php?page=".'tutopic-orders'."&items_".$page->url ); ?>"
          >
          <?php echo esc_html($page->page); ?>
        </a>
      <?php endforeach; ?>
      <?php if ($orders->paginate->next): ?>
        <a href="<?php echo
          admin_url( "admin.php?page=".'tutopic-orders'."&items_".$orders->paginate->next->url );
        ?>">&raquo;</a>
      <?php endif; ?>
    </div>
  </div>
</div>


<?php
// Detalle
  include TUTOPIC_PLUGIN_BASE_PATH.'/modals/orderDetail.modal.php';
  // Aceptar entrega
  include TUTOPIC_PLUGIN_BASE_PATH.'/modals/checkOrder.modal.php';
  // Rechazar entrega
  include TUTOPIC_PLUGIN_BASE_PATH.'/modals/verifyOrder.modal.php';
  // Corregir entrega
  include TUTOPIC_PLUGIN_BASE_PATH.'/modals/correctionOrder.modal.php';
?>

<script type="text/javascript">


// Inicializado JQuery
jQuery(document).ready(($) => {//orders_locals

  const orders_locals = <?php echo wp_json_encode(\TuTopicPlugin\App\Helpers\OrdersLocal::get()); ?>;
  console.log('ORDERS LOCALS', orders_locals);

  $('button.btnViewOrderAction').click(function(evt) {
    console.log('OPEN MODAL: ', $(this).attr('id'))
    openModal($(this).attr('id'));
  });

  function blankData(campo) {
    $('#'+campo+'_field').addClass('d-none');
    $('#'+campo+'').html('');
  }

  function blankAllData() {
    $('#delivery_field').addClass('d-none');
    $('#downloadFile').addClass('d-none');
    $('#viewDocumentWp').addClass('d-none');
    $('#order_detail_buttons_field').addClass('d-none');
    blankData('ordertitle');
    blankData('orderstate');
    blankData('orderCode');
    blankData('orderCost');
    blankData('orderQuality');
    blankData('orderCreate_at');
    blankData('orderDays');
    blankData('orderRedactor');
    blankData('orderComent');
  }

  function printData(campo, value = null, blankValue = null) {
    // Si y solo si hay valor... si no hay valor SI Y SOLO SI se ha establecido un "blankValue" que seria el "default" entonces pasa
    if (value || blankValue) {
      $('#'+campo+'_field').removeClass('d-none');
      $('#'+campo+'').html((value) ? value : blankValue);
    }
  }

  // Modal de detalle de ordenes
  var modal_orderDetail   = document.getElementById('modal_orderDetail');
  var modal_denyDetail    = document.getElementById('modal_verifyOrder');
  var modal_fixDetail     = document.getElementById('modal_correctionOrder');
  var modal_aproveDetail  = document.getElementById('modal_checkOrder');
  /*
    btnDetail_Deny
    btnDetail_Fix
    btnDetail_Approve
  */

  var openModal = function(id) {

    // Borramos todos los datos de modals anteriores
    blankAllData();

    // Insertando los datos de la orden dentro del modal
    var orders = <?php echo wp_json_encode($orders->items); ?>;
    let order = null;

    for (var i = 0; i < orders.length; i++) {
      if(orders[i].public_code == id){

        // Obteniendo orden actual
        order = orders[i];
        let orderLocal = null;
        orderLocal = orders_locals.find((item) => item.order === order.id);

        console.log(orderLocal);
        console.log(order);

        // Open
        $('#btnDetail_Deny').off('click');
        $('#btnDetail_Approve').off('click');
        $('#btnDetail_Fix').off('click');

        // Titulo de orden
        printData('ordertitle', order.title);
        printData('orderstate', order.estado);
        printData('orderCode', order.public_code);

        printData('orderQuality', order.nivel_calidad);
        printData('orderRedactor', order.redactor_nickname, 'No posee');


        if (order.last_delivery_public && order.last_delivery_public.submit_client) {

          // Si hay una entrega


          // Open
          if (order.state !== 'order_completed') {
            // Si la orden no se ha completado

            $('#btnDetail_Deny').on('click',(evt) => {
              openDenyModal(order);
            });
            $('#btnDetail_Approve').on('click',(evt) => {
              openApproveModal(order);
            });
            $('#btnDetail_Fix').on('click',(evt) => {
              openCorrectionModal(order);
            });
            $('#order_detail_buttons_field').removeClass('d-none');

          }



          $('#delivery_field').removeClass('d-none');
          $('#order_delivery_id').html('#'+order.last_delivery_public.id);

          $('#downloadFile').removeClass('d-none');
          $('#downloadFile').attr('href', order.last_delivery_public.document_link);

          $('#viewDocumentWp').removeClass('d-none');

          //
          if (orderLocal && orderLocal.post) {
            $('#viewDocumentWp').text('Actualizar articulo');
          }else{
            $('#viewDocumentWp').text('Crear borrador');
          }
          //

          $('#service_create_draft_delivery_id').val(order.last_delivery_public.id);


          if (order.last_delivery_public.comments[0]) {
            let first_comment = order.last_delivery_public.comments[0];
            console.log(first_comment)
            if (first_comment.sender.is_redactor) {
              printData('orderComent', first_comment.message, 'No hay comentario');
            }else{
              printData('orderComent', null, 'No hay comentario del redactor');
            }
          }else {
            printData('orderComent', null, 'No hay comentario');
          }


        }else{
          // Si no hay una entrega
          printData('orderCost', order.balance_charged);

          var dateISO = new Date(order.created_at)/*.toISOString()*/;
          console.log(dateISO);
          var dateStr = dateISO.getDate() + '/' + dateISO.getMonth() + '/' + dateISO.getFullYear();

          printData('orderCreate_at', dateStr);


          printData('orderDays', order.days_to_work + ' días');

        }


      }
    }

    // Abriendo el modal
    modal_orderDetail.style.display = 'flex';

  }

  // Registrar aqui para que el modal se cierre dando click en el contorno de afuera
  window.onclick = function(event) {
    // Modal de detalle de orden
    if (event.target == modal_orderDetail) {
      modal_orderDetail.style.display = "none";
    }
    // Modal de aprobar
    if (event.target == modal_aproveDetail) {
      modal_aproveDetail.style.display = "none";
    }
    // Modal de denegar
    if (event.target == modal_denyDetail) {
      modal_denyDetail.style.display = "none";
    }
  }

  // Abrir Modal de Denegar
  var openDenyModal = function(orderCurrent) {
    // Abriendo el modal
    modal_denyDetail.style.display = 'flex';
    console.log('Open Deny Modal');
    console.log(orderCurrent);
    $('#service_deny_delivery_id').val(orderCurrent.last_delivery_public.id);
    $('#service_delivery_deny_comment').val('');
  }

  // Abrir Modal de Aprobar
  var openApproveModal = function(orderCurrent) {
    // Abriendo el modal
    modal_aproveDetail.style.display = 'flex';
    console.log('Open Approve Modal');
    console.log(orderCurrent);
    $('#service_approve_delivery_id').val(orderCurrent.last_delivery_public.id);
    var currentTitle = orderCurrent.last_delivery_public.title;
    //'.docx','.docx','.doc'
    currentTitle = currentTitle.replace(".docx", "");
    currentTitle = currentTitle.replace(".docx", "");
    currentTitle = currentTitle.replace(".doc", "");
    $('#service_delivery_new_title').val(currentTitle);
  }

  // Abrir Modal de Corregir
  var openCorrectionModal = function(orderCurrent) {
    // Abriendo el modal
    modal_fixDetail.style.display = 'flex';
    console.log('Open Correction Modal');
    console.log(orderCurrent);
    $('#service_correction_delivery_id').val(orderCurrent.last_delivery_public.id);
    $('#service_delivery_correction_comment').val('');
  }

});

</script>
