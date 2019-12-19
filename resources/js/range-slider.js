// range-slider
// use range slider for rangeFilters
$('.players__rangeFilters').each(function () {
  $inputGroup = $(this).find('.input-group');
  const sliderID = $inputGroup.find('div:eq(0)').attr('id');
  const filterFrom = $inputGroup.find('input:eq(0)').attr('name');
  const filterTo = $inputGroup.find('input:eq(1)').attr('name');;
  
  rangeSlider(`#${sliderID}`, `input[name="${filterFrom}"]`, `input[name="${filterTo}"]`);
})


$('.transfers__rangeFilters').each(function () {
  $inputGroup = $(this).find('.input-group');
  const sliderID = $inputGroup.find('div:eq(0)').attr('id');
  const filterFrom = $inputGroup.find('input:eq(0)').attr('name');
  const filterTo = $inputGroup.find('input:eq(1)').attr('name');;
  
  rangeSlider(`#${sliderID}`, `input[name="${filterFrom}"]`, `input[name="${filterTo}"]`);
})

function rangeSlider($sliderClass, $from, $to) {

  $($sliderClass).slider({
    range: true,
    min: 0,
    max: 10,
    values: [ 0, 10 ],
    slide: function( event, ui ) {
      $($from).val(ui.values[ 0 ]);
      $($to).val(ui.values[ 1 ]);
      min_value = ui.values[ 0 ];
      max_value = ui.values[ 1 ];
      // ajax
      $(this).parents('form').trigger('submit');
      
    }
  });


  $($from).val($($sliderClass).slider( "values", 0 ));
  $($to).val($($sliderClass).slider( "values", 1 ));


  $($to).on('keyup', function () {
      if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
        this.value = this.value.replace(/[^0-9\.]/g, '');
      }

    var value = this.value;
    var min = $(this).attr('min');
    var max = $(this).attr('max');
    if (parseInt(value) < min) {
      $(this).val(min);
      $($sliderClass).slider("values", 1, parseInt(value));
      return false;
    }
    else if (parseInt(value) > max) {
      $(this).val(max);
    }
    else {
      value = this.value;    
      $($sliderClass).slider("values", 1, parseInt(value));
    }
  });


  $($from).on('keyup', function () {    
    if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
        this.value = this.value.replace(/[^0-9\.]/g, '');
    }
    var value = this.value;
    var min = $(this).attr('min');
    var max = $(this).attr('max');
    if (parseInt(value) > $(this).attr('max')) {
      $(this).val(max);
      $($sliderClass).slider("values", 0, parseInt(value));
      return false;
    }
    else if (parseInt(value) < min) {
      $(this).val(min);
    }
    else {
      value = this.value;    
      $($sliderClass).slider("values", 0, parseInt(value));
    }
  });
}