

const controls = {
  leftArrow: '<i class="fal fa-angle-left" style="font-size: 1.25rem"></i>',
  rightArrow: '<i class="fal fa-angle-right" style="font-size: 1.25rem"></i>'
};

const runDatePicker = function () {
  // range picker
  $('#datepicker-5').datepicker(
    {
      todayHighlight: true,
      language: 'ru',
      format: 'dd.mm.yyyy',
      templates: controls
    });
};

$(document).ready(function () {
  runDatePicker();
});

$('.load-stat').on('click', () => {
  $('#statPage').submit();
});

$('.exel').on('click', () => {
  const data = {
    id: $('#users_id').val(),
    end: $('#end').val(),
    start: $('#start').val(),
  };
  window.open(`/statisticExel?users_id=${data.id }&start=${data.start }&end=${data.end }`);
});


