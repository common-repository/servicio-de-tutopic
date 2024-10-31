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

  // Obtener competencias o tematicas
  $competences = TuTopicPlugin\Service\TuTopic::getCompetences();
?>

<script type="text/javascript">
  jQuery(document).ready(($) => {
    setTimeout(() => {
      TuTopicConfigDataHelper.setPublicData('<?php echo wp_json_encode($public_data); ?>');
    }, 10);
  });
</script>

<form class="row" method="post" action="<?php echo admin_url( 'admin-post.php' ); ?>">

  <input type="hidden" name="action" value="tutopic_service_create_order" />
  <input type="hidden" name="sender_url" value="<?php echo admin_url( 'admin.php?page=create_page' ); ?>" />

  <input type="hidden" name="keywords" id="keywords" value="[]" />
  <input type="hidden" name="avoid_words" id="avoid_words" value="[]" />

  <!-- BODY -->
  <!-- Datos basicos-->
  <div class="col-md-6 col-12">
    <div class="separation_defautl">

      <h2 class="text-primary page-title text-bold">Datos basicos</h2>

      <div class="field-container">
        <input class="field-input" id="title" name="title" type="text" placeholder=" ">
        <label class="field-placeholder" for="title">Titulo</label>
      </div>

      <div class="field-container">
        <input class="field-input" id="words_number" name="words_number" type="text" placeholder=" ">
        <label class="field-placeholder" for="words_number">Numero de palabras</label>
      </div>

      <div class="field-container">
        <select class="field-input" id="competency_id" name="competency_id" placeholder=" ">
          <?php
          if( $competences ){
            $competences[]  = new Class () {
              public $id    = 'custom';
              public $name  = 'Personalizada';
            };
            foreach( $competences as $competence ){
              ?>
              <option value="<?php echo esc_attr($competence->id);?>">
                <?php echo esc_html($competence->name);?>
              </option>
              <?php
            }
          }
          ?>
        </select>
        <label class="field-placeholder" for="competency_id">Tematica</label>
      </div>

      <div class="field-container d-none" id="custom_competency_field">
        <input class="field-input" id="custom_competency" name="custom_competency" type="text" placeholder=" " />
        <label class="field-placeholder" for="custom_competency">Tematica personalizada</label>
      </div>

      <div class="field-container">
        <select class="field-input" id="is_urgent" name="is_urgent" placeholder=" ">
          <option value="0">No</option>
          <option value="1">Si</option>
        </select>
        <label class="field-placeholder" for="is_urgent">Urgente</label>
      </div>

      <div class="field-container d-none" id="days_less_field">
        <select class="field-input" id="days_less" name="days_less" placeholder=" ">
          <option value="0">Ningun día menos</option>
          <option value="1">1 Día menos</option>
          <option value="2">2 Días menos</option>
        </select>
        <label class="field-placeholder" for="days_less">Dias menos</label>
      </div>

      <div class="field-container">
        <textarea
          class="field-input"
          rows="3"
          id="comment"
          name="comment"
          type="text"
        ></textarea>
        <label class="field-placeholder" for="comment">Comentario</label>
      </div>

      <div class="field-container">
        <select class="field-input" id="quality_level_needed" name="quality_level_needed" placeholder="" >
          <option value="junior">Promedio</option>
          <option value="semisenior">Alta</option>
          <option value="senior">Muy alta</option>
        </select>
        <label class="field-placeholder" for="quality_level_needed">Nivel de calidad</label>
      </div>

    </div>
  </div>

  <!-- Gestion de palabras -->
  <div class="col-md-6 col-12">
    <div class="separation_defautl">

      <h2 class="text-primary page-title text-bold">
        Gestion de palabras
      </h2>

      <div class="row">
        <div class="boxWords">
          <div class="separation_defautl">
            <div class="w-100 boxWordsOrientation">
              <div class="field-container">
                <input class="field-input" id="word" type="text" placeholder=" ">
                <label class="field-placeholder" for="inputName">Palabras a emplear</label>
              </div>
              <div class="btnBlueIcon btnWidthIconBlue" id="deseadasButton" >
                <i class="fa fa-plus"></i>
              </div>
            </div>

            <ul class="listWords text-primary" id="yesWords_list">
            </ul>

          </div>
        </div>
        <div class="boxWords">
          <div class="separation_defautl">
            <div class="w-100 boxWordsOrientation">
              <div class="field-container">
                <input class="field-input" id="noword" type="text" placeholder=" ">
                <label class="field-placeholder" for="inputName">Palabras a evitar</label>
              </div>
              <div class="btnBlueIcon btnWidthIconBlue" id="indeseadasButton" >
                <i class="fa fa-plus"></i>
              </div>
            </div>

            <ul class="listWords text-secondary" id="noWords_list">
            </ul>

          </div>
        </div>
      </div>

      <div  class ="row"
            id    ="need_corrector_div"
        >
        <div class="px-5">
          <input
            type="checkbox"
            name="need_corrector"
            id="need_corrector"
            value="true"
            class="tutopic-normal-checkbox"
          />
          <label for="need_corrector">
            Solicitar correcion de un segundo redactor
          </label>
        </div>
        <div class="px-5">
          <small>Para pedidos con calidad cuya calidad es <b>promedio</b> la inclusion de un redactor es opcional y costara <b>0.8 EUR adicionales</b></small>
        </div>
      </div>


    </div>
  </div>

  <!-- Imagenes -->
  <?php if (false): ?>
    <div class="col-md-6 col-12">
      <div class="separation_defautl">
        <h2 class="text-primary page-title text-bold">Sugerir Imagenes</h2>
        <p class="text-justify">
          Puedes contextualizar más al redactor u orientar tu articulo a imagenes especificas si le
          recomiendas algunas (Maximo 3 imagenes).
        </p>
        <ul class="mb-3">
          <li class="itemImage">
            <div>
              imagen-rara.jpg
            </div>
            <div class="btnBlueIcon">
              <i class="fa fa-close"></i>
            </div>
          </li>
          <li class="itemImage">
            <div>
              imagen-rara.jpg
            </div>
            <div class="btnBlueIcon">
              <i class="fa fa-close"></i>
            </div>
          </li>
        </ul>
        <input style="display: none" type="file" id="addImage" name="addImage" value="">
        <label for="addImage" class="btnPrimaryOutline btnAddImage">
          <i class="fa fa-upload"></i>
          Subir imagen
        </label>
      </div>
    </div>
  <?php endif; ?>

  <!-- FOOTER -->
  <div class="col-md-9 col-12">
    <div class="separation_defautl">
      <div class="row">

        <div class="footerDataColTop text-primary">
          <i class="fa fa-money iconFooter"></i>

          <div class="footerDataComponent">
            <span class="labelDataComponent">Coste</span>
            <h1 class="text-primary m-0" id="preview_cost" >0.00 EUR</h1>
          </div>
        </div>

        <div class="footerDataColBottom text-primary">
          <i class="fa fa-clock-o iconFooter"></i>

          <div class="footerDataComponent">
            <span class="labelDataComponent">Tiempo</span>
            <h1 class="text-primary m-0" id="preview_days_to_work" >0 Dias</h1>
          </div>
        </div>

      </div>
    </div>
  </div>

  <div class="col-md-3 col-12 align-center">
    <div class="separation_defautl w-100 footerAction">
      <button type="submit"  class="btnPrimary btnSendOrder disabled-after-click" href="#">
        Realizar pedido
      </button>
    </div>
  </div>

</form>
