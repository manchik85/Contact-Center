

const controls = {
  leftArrow:  '<i class="fal fa-angle-left"  style="font-size: 1.25rem"></i>',
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
    gov_group: $('#gov_group').val(),
    end: $('#end').val(),
    start: $('#start').val(),
  };
  window.open(`/exel_kpi?start=${data.start }&end=${data.end }&gov_group=${data.gov_group }`);
});

$('.statistic_user').on('click', function () {
  $('#statUserId').val($(this).attr('id-user'));
  $('#statPageUnit').submit();
});
