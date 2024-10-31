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
  $competences = TuTopicPlugin\Service\TuTopic::getCompetences(true);

?>

<form class="row" method="post" action="<?php echo admin_url( 'admin-post.php' );?>">
  <input type="hidden" name="action" value="tutopic_service_auth" />
  <input type="hidden" name="sender_url" value="<?php echo admin_url( 'options-general.php?page=settings-tutopic-screen' );?>" />
  <!-- BODY -->
  <!-- Datos basicos-->
  <div class="col-12">
    <div class="separation_defautl">
      <h2 class="text-primary page-title text-bold mb-1">API-KEY de Tutopic</h2>
      <p  class="text-content text-dark mt-1 mb-1">Configura el API-KEY del plugin para poder empezar a usarlo, podrás conseguir este codigo en la pagina de <a target="_blank"
        href="<?php echo esc_url(TUTOPIC_BASE_URL); ?>/app/perfil"
        class="link-tutopic">perfil de tu cuenta</a> en la plataforma.</p>
      <div class="field-container">
        <input class="field-input" id="association_code" name="association_code" type="text" placeholder=""
          value="<?php echo esc_attr( get_option('association_code') ); ?>"
          required
          maxlength="40"
        />
        <label class="field-placeholder" for="inputName">API-Key</label>
      </div>
    </div>
    <div class="separation_defautl">
      <h2 class="text-primary page-title text-bold mb-1">Tematicas del sitio</h2>
      <p  class="text-content text-dark mt-1">Selecciona hasta 3 tematicas que describan a tu sitio para poder ofrecerte un servicio personalizado y de calidad mediante el plugin Tutopic.</p>
      <div class="tutopic-checkbox-field">
        <?php
        $assocCompetences = get_option('association_competences');
        if( $competences ){
          foreach( $competences as $competence ){
            $checkID = 'check_competence_'.$competence->id;
            ?>
            <div class="tutopic-checkbox-item">

                <input  id="<?php echo esc_attr($checkID); ?>"
                        <?php echo (in_array($competence->id,$assocCompetences,false)) ? 'checked="checked"' : ''; ?>
                        name="association_competences[]"
                        class="association-checkbox tutopic-checkbox-check"
                        type="checkbox" value="<?php echo esc_attr($competence->id); ?>"
                        />

                <label  class="tutopic-checkbox-label"
                  for="<?php echo esc_attr($checkID); ?>"
                  >
                  <?php echo esc_html($competence->name); ?>
                </label>

            </div>
          <?php
        }
      }
      ?>
      </div>
      <script type="text/javascript">
          /*Este script sirve para que solo puedas escoger 3 tematicas del sitio*/
          jQuery(document).ready(function($) {
              var limit = 3;
              $("input[name='association_competences[]']").on('change', (evt) => {
                 if($("input[name='association_competences[]']:checked").length > limit) {
                     console.log('Limite permitido de tematicas!');
                     evt.target.checked = false;
                 }
              });
          });
      </script>
    </div>

    <div class="separation_defautl p-0 mb-3 my-3">

      <div class="row">

        <div class="col-12">
          <h2 class="text-primary page-title text-bold mb-1">Autor de los articulos publicados</h2>
        </div>
        <div class="col-12 col-md-6" id="contribuite_to_original_author_div">
          <h4 class="text-primary text-bold mb-1">Contribuir al autor original</h4>
          <p  class="text-content text-dark mt-1">Si desea contribuir al autor original del articulo, seleccione <b>si contribuir</b> y este sera el autor de los articulos que pida.</p>
          <div class="field-container">
            <select class="field-input" id="contribuite_to_original_author"
            name="contribuite_to_original_author" placeholder=" ">
              <?php

                $optionsOriginalAuthor              = [1 => 'Si contribuir',0 => 'Seleccionar mi propio autor'];
                $contribuiteOriginalAuthor          = get_option('contribuite_to_original_author');
                if (!$contribuiteOriginalAuthor)
                  $contribuiteOriginalAuthor = 0;

                foreach( $optionsOriginalAuthor as $ConKey => $ConLabel ){
                  ?>
                  <option <?php echo ($contribuiteOriginalAuthor == $ConKey) ? 'selected' : '';  ?>
                    value="<?php echo esc_attr($ConKey); ?>"
                  >
                    <?php echo esc_html($ConLabel); ?>
                  </option>
                  <?php
                }

              ?>
            </select>
            <label class="field-placeholder" for="users">Contribuir al autor original</label>
          </div>
        </div>
        <div class="col-12 col-md-6" id="association_user_div">
          <h4 class="text-primary text-bold mb-1">Usuario del sitio</h4>
          <p  class="text-content text-dark mt-1">En caso de no contribuir al autor, seleccione el usuario al que se atribuiran los articulos cuando se aprueben entregas.</p>
          <div class="field-container">
            <select class="field-input" id="association_user" name="association_user" placeholder=" ">
              <?php
              $users = get_users();
              $assocUser = get_option('association_user');
              if( $users ){
                foreach( $users as $user ){
                  ?>
                  <option
                    <?php echo ($assocUser == $user->ID) ? 'selected' : ''; ?>
                    value="<?php echo esc_attr($user->ID); ?>"
                  >
                    <?php echo esc_html($user->display_name); ?>
                  </option>
                  <?php
                }
              }
              ?>
            </select>
            <label class="field-placeholder" for="users">Usuario a utilizar</label>
          </div>
        </div>

      </div>

    </div>


    <div class="separation_defautl">
      <div class="row">
        <div class="col-12">
          <h2 class="text-primary page-title text-bold mb-1">Configuracion de articulos</h2>
        </div>
        <div class="col-12 col-md-6 h-215-box">
          <div>
            <h4 class="text-primary text-bold mb-1">Publicacion automatica</h4>
            <p  class="text-content text-dark mt-1">
              Al crear un post luego de aprobar la entrega de un pedido este aparecera como "publicado" automaticamente en lugar de como un borrador.
            </p>
          </div>
          <div class="field-container">
            <select class="field-input" id="auto_publish_in_approbe" name="auto_publish_in_approbe" placeholder=" ">
              <?php
                $publishOptions = [1 => 'Autopublicar',0 => 'No autopublicar'];
                $currentPublishOption = get_option('auto_publish_in_approbe');
                foreach( $publishOptions as $publishKey => $publishValue ){
                  ?>
                  <option <?php echo ($publishKey == $currentPublishOption) ? 'selected' : ''; ?>
                    value="<?php echo esc_attr($publishKey); ?>"
                  >
                    <?php echo esc_html($publishValue); ?>
                  </option>
                  <?php
                }
              ?>
            </select>
            <label class="field-placeholder" for="auto_publish_in_approbe">Autopublicacion</label>
          </div>
        </div>

        <div class="col-12 col-md-6 h-215-box">
          <div>
            <h4 class="text-primary text-bold text-bold mb-1">Centrar imagenes al crear articulo</h4>
            <p  class="text-content text-dark mt-1">
              Al crear un post luego de aprobar una entrega, el plugin puede centrar automaticamente todas las imagenes que vengan en el
            </p>
          </div>
          <div class="field-container">
            <select class="field-input" id="auto_center_in_publish" name="auto_center_in_publish" placeholder=" ">
              <?php
                $publishOptions = [1 => 'Autocentrar',0 => 'No autocentrar'];
                $currentPublishOption = get_option('auto_center_in_publish');
                foreach( $publishOptions as $publishKey => $publishValue ){
                  ?>
                  <option <?php echo ($publishKey == $currentPublishOption) ? 'selected' : ''; ?>
                    value="<?php echo esc_attr($publishKey); ?>">
                    <?php echo esc_html($publishValue); ?>
                  </option>
                  <?php
                }
              ?>
            </select>
            <label class="field-placeholder" for="auto_center_in_publish">Autocentrado</label>
          </div>
        </div>

        <?php if (false): ?>
          <div class="col-12 h-215-box mb-1">
            <div>
              <h4 class="text-primary text-bold mb-1">¿Apto para patrocinio?</h4>
              <p  class="text-content text-dark mt-1">
                Si tu sitio web es apto para patrocinar algun post de tutopic, los demas clientes dentro de la plataforma pondran seleccionar tu sitio
                al momento de crear una orden y el post final sera publicado en este sitio.
              </p>
            </div>
            <div class="field-container">
              <select class="field-input" id="monetary_value" name="monetary_value" placeholder=" ">
                <?php
                  $monetaryValues = [29 => '29 EUR', 49 => '49 EUR', 79 => '79 EUR'];
                  $currentMonetaryValue = get_option('monetary_value');
                  foreach( $monetaryValues as $key => $value ){
                    ?>
                    <option <?php echo ($key == $currentMonetaryValue) ? 'selected' : ''; ?>
                      value="<?php echo esc_attr($key); ?>"
                    >
                      <?php echo esc_html($value); ?>
                    </option>
                    <?php
                  }
                ?>
              </select>
              <label class="field-placeholder" for="monetary_value">Valor monetario</label>
            </div>
            <div class="separation_defautl w-100 justify-center-sm mt-1">
              <div class="">
                <input
                  type="checkbox"
                  name="on_patronize"
                  id="on_patronize"
                  class="tutopic-normal-checkbox"
                  value="on_patronize"
                  <?php echo get_option('on_patronize') ? 'checked' : ''; ?>
                />
                <label for="on_patronize">
                  ¿Apto para patrocinio?
                </label>
              </div>
            </div>
          </div>
        <?php endif; ?>

      </div>
    </div>


  </div>

  <!-- FOOTER -->
  <div class="col-12 col-sm-7 align-center justify-center-sm">
    <div class="separation_defautl text-normal">
      <label>Estado de vinculo:</label>
      <div class="">
        <?php
          $association_ready = get_option('association_ready');
          if (!$association_ready):
        ?>
          <span class="text-gray">Cuenta de TuTopic no vinculada</span>
        <?php else: ?>
          <span class="text-primary"><b>Cuenta de TuTopic Vinculada</b></span>
        <?php endif; ?>
      </div>
    </div>
  </div>
  <div class="col-12 col-sm-5 align-center ">
    <div class="separation_defautl w-100 footerAction justify-center-sm">
      <button type="submit"  class="btnPrimary btnSendOrder disabled-after-click" href="#">
        Guardar cambios
      </button>
    </div>
  </div>
  <div class="col-12 align-center ">
    <div class="separation_defautl w-100 justify-center-sm">
      <div class="">
        <input
          type="checkbox"
          name="politica_privacidad"
          id="politica_privacidad"
          class="tutopic-normal-checkbox"
          value="politica_privacidad"
          required
          <?php echo get_option('association_ready') ? 'checked' : ''; ?>
        />
        <label for="politica_privacidad">
          Configurando este plugin acepto la
          <a href="https://tutopic.com/es/terminos/privacidad" target="_blank">politica de privacidad</a>
           de TuTopic
        </label>
      </div>
    </div>
  </div>

</form>
