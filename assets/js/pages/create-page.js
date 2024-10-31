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


console.log('create-page.js loaded!');
// Inicializado JQuery
jQuery(document).ready(function($) {

  /*RECALCULAR PRECIOS Y TIEMPO*/
    // Elementos
    const daysLessInput       = $('#days_less');
    const wordsNumberInput    = $('#words_number');
    const qualityLevelInput   = $('#quality_level_needed');
    const needCorrectorInput  = $('#need_corrector');

    // Funciones
    function recalcularPrecio() {

      console.log('Recalculando precio');

      // Obtenemos el valor del numero de palabras y comprobamos que sea un numero
      const words_number_str = wordsNumberInput.val();
      if (!words_number_str || isNaN(words_number_str)) return 0;

      const words_number = parseInt(words_number_str);

      const days_less = parseInt(daysLessInput.val());
      const quality_level_needed = qualityLevelInput.val();

      const recargoPercentage = TuTopicCalculatorPost.getRecargoPercentage(days_less, quality_level_needed);
      const cost = TuTopicCalculatorPost.getPostCost(words_number, quality_level_needed);

      const costoTotalSinAdicional = TuTopicCalculatorPost.getRecargoCost(cost, recargoPercentage);

      console.log('Sin costes adicionales: ', costoTotalSinAdicional);

      // Calcular costes adicionales
      let   costoAdicional = TuTopicCalculatorPost.getExtraCostCorrector(needCorrectorInput.prop('checked'), quality_level_needed);

      console.log('Costes adicionales: ', costoAdicional);

      // Costo Total
      const costoTotal = costoTotalSinAdicional + costoAdicional;

      console.log('Costes totales: ', costoTotal);

      return TuTopicCalculatorPost.redondear(costoTotal);

    }

    function recalcularDiasDeTrabajo() {

      console.log('Recalculando dias de trabajo');

      const days_less = parseInt(daysLessInput.val());
      const quality_level_needed = qualityLevelInput.val();

      return TuTopicCalculatorPost.getDaysToWork(quality_level_needed, days_less);

    }

    function recalcularTodo() {
      const precio = recalcularPrecio();
      $('#preview_cost').html(precio+' EUR');

      const tiempo = recalcularDiasDeTrabajo();
      $('#preview_days_to_work').html(tiempo+' días.');
    }

    // Callbacks
    daysLessInput.change(() => recalcularTodo());
    wordsNumberInput.change(() => recalcularTodo());
    qualityLevelInput.change(() => recalcularTodo());
    needCorrectorInput.change(() => recalcularTodo());

  // Select Urgente
  $("#is_urgent").on('change', (evt) => {
    if ($(evt.target).val() == 1) {
      // Si es urgente
      $( "#days_less_field" ).removeClass( "d-none" )
    }else{
      // Si no es urgente
      daysLessInput.val("0");
      recalcularTodo();
      $( "#days_less_field" ).addClass( "d-none" )
    }
  });

  // Select Calidad
  $("#quality_level_needed").on('change', (evt) => {
    if ($(evt.target).val() === 'junior') {
      // El corrector si es opcional
      $( "#need_corrector_div" ).removeClass( "d-none" )
    }else{
      // No es opcional el corrector
      $( "#need_corrector_div" ).addClass( "d-none" )
    }
  });

  // Select Tematica
  $("#competency_id").on('change', (evt) => {
    if ($(evt.target).val() == 'custom') {
      // Si es personalizada
      $( "#custom_competency_field" ).removeClass( "d-none" )
      //$( "#custom_competency" ).attr( "required", true )
    }else{
      // Si no es personalizada
      $( "#custom_competency_field" ).addClass( "d-none" )
      //$( "#custom_competency" ).attr( "required", false )
    }
  });

  /*PALABRAS DESEADAS E INDESEADAS*/
  // DECLARACIONES:

    //# Listas <li>
    const deseadasList      = $('#yesWords_list');
    const indeseadasList    = $('#noWords_list');
    //# Inputs para escribir <input type="text">
    const deseadasInput     = $('#word');
    const indeseadasInput   = $('#noword');
    //# Boton para agregar <div class="btnBlueIcon btnWidthIconBlue">
    const deseadasButton     = $('#deseadasButton');
    const indeseadasButton   = $('#indeseadasButton');
    //# Inputs hidden <input type="hidden">
    const keywordsInput     = $('#keywords');
    const avoidWordsInput   = $('#avoid_words');

  // FUNCIONES

    /*Recibe como argumento el INPUT y la palabra a agregar*/
    function addKeyword(hiddenInput, keyword) {

      // Agregamos las keywords
      var keywords = JSON.parse(hiddenInput.val());
      keywords.push(keyword);
      hiddenInput.val(JSON.stringify(keywords));

      // Renderizamos las keywords
      keywordsRender();

    }

    /*Recibe como argumento el INPUT y el INDEX de la keyword a eliminar*/
    function removeKeyword(hiddenInput, index) {

      // Eliminamos la keyword
      var keywords = JSON.parse(hiddenInput.val());
      keywords.splice(index, 1);
      hiddenInput.val(JSON.stringify(keywords));

      // Renderizamos las keywords
      keywordsRender();

    }

    /*Renderizar palabras deseadas e indeseadas*/
    // Debe ser llamado siempre que se agregue una nueva palabra
    function keywordsRender() {

      // Borramos todos los elementos actuales
      deseadasList.html('');
      indeseadasList.html('');

      // Obtenemos los datos
      const keywords        = JSON.parse(keywordsInput.val());
      const avoid_keywords  = JSON.parse(avoidWordsInput.val());

      // Los creamos
      $.each(keywords,        ( index, keyword ) => {

        const element       = $( "<li></li>" ).append( "<span>" + keyword + "</span>" );
        const closeButton   = $('<div class="btnSecondaryIcon"><i class="fa fa-close"></i></div>');

        closeButton.attr('index',index);
        closeButton.click((evt) => {
          const btnIndex = closeButton.attr('index');
          removeKeyword(keywordsInput, btnIndex);
        });

        element         .append(closeButton);
        deseadasList    .append(element);

      });
      $.each(avoid_keywords,  ( index, keyword ) => {

        const element       = $( "<li></li>" ).append( "<span>" + keyword + "</span>" );
        const closeButton   = $('<div class="btnSecondaryIcon"><i class="fa fa-close"></i></div>');

        closeButton.attr('index',index);
        closeButton.click((evt) => {
          const btnIndex = closeButton.attr('index');
          removeKeyword(avoidWordsInput, btnIndex);
        });

        element         .append(closeButton);
        indeseadasList  .append(element);

      });

    }

    // Agregar palabras deseadas cuando se haga click en el boton
    deseadasButton.click((evt) => {

      if (!deseadasInput.val()) return;

      addKeyword(keywordsInput, deseadasInput.val());
      deseadasInput.val('');

    });

    // Agregar palabras indeseadas cuando se haga click en el boton
    indeseadasButton.click((evt) => {

      if (!indeseadasInput.val()) return;

      addKeyword(avoidWordsInput, indeseadasInput.val());
      indeseadasInput.val('');

    });



});
