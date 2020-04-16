

const controls = {
  leftArrow: '<i class="fal fa-angle-left" style="font-size: 1.25rem"></i>',
  rightArrow: '<i class="fal fa-angle-right" style="font-size: 1.25rem"></i>'
};

const runDatePicker = function () {
  // range picker
  $('#datepicker-0').datepicker(
    {
      todayHighlight: true,
      language: 'ru',
      format: 'dd.mm.yyyy',
      templates: controls
    });
  // range picker
  $('#datepicker-5').datepicker(
    {
      todayHighlight: true,
      language: 'ru',
      format: 'dd.mm.yyyy',
      templates: controls
    });
  $('#datepicker-6').datepicker(
    {
      todayHighlight: true,
      language: 'ru',
      format: 'dd.mm.yyyy',
      templates: controls
    });
  // range picker
  $('#datepicker-51').datepicker(
    {
      todayHighlight: true,
      language: 'ru',
      format: 'dd.mm.yyyy',
      templates: controls
    });
  $('#datepicker-61').datepicker(
    {
      todayHighlight: true,
      language: 'ru',
      format: 'dd.mm.yyyy',
      templates: controls
    });
};

$('.load-stat-dash-priority').click(function() {
    var idPriority = $(this).data('priority');

    //записываем в поле приоритета для POST
    $('#priority').val(idPriority);
    //запишем обозначаение того чтобы кликнули по диаграмке
    $('#is_dashboard').val(1);
    //вытаскиваем заданные даты и записываем их в поля для POST
    // но для записи даты придется вытащить все input-ы с айдишником
    // потому что у операторов две формочки с одиннаковыми id input-а.
    // это конечно же не правильно, но исправлять не стал так как
    // трудозатратно. пока костыль.
    var listDateInput = $('input[name*="date_task_start"]');
    if (listDateInput && listDateInput.length > 0) {
        listDateInput.each(function () {
            this.value = $('#date_task_start-0').val();
        });
    }
    var listDateInput = $('input[name*="date_task_end"]');
    if (listDateInput && listDateInput.length > 0) {
        listDateInput.each(function () {
            this.value = $('#date_task_end-0').val();
        });
    }

    $('#statPageTask').submit();
});

$('.load-stat-dash-complete').click(function() {
    var idComplete = $(this).data('complete');

    //записываем в поле стадии работы для POST
    $('#complete').val(idComplete);
    //запишем обозначаение того чтобы кликнули по диаграмке
    $('#is_dashboard').val(1);
    //вытаскиваем заданные даты и записываем их в поля для POST
    // но для записи даты придется вытащить все input-ы с айдишником
    // потому что у операторов две формочки с одиннаковыми id input-а.
    // это конечно же не правильно, но исправлять не стал так как
    // трудозатратно. пока костыль.
    var listDateInput = $('input[name*="date_task_start"]');
    if (listDateInput && listDateInput.length > 0) {
        listDateInput.each(function () {
            this.value = $('#date_task_start-0').val();
        });
    }
    var listDateInput = $('input[name*="date_task_end"]');
    if (listDateInput && listDateInput.length > 0) {
        listDateInput.each(function () {
            this.value = $('#date_task_end-0').val();
        });
    }

    $('#statPageTask').submit();
});

const ListTasksDash = {
  init: function () {


    $('.load-stat-all').on('click', () => {
      $('#statPage-all').submit();
    });

    $('.load-stat').on('click', () => {
      $('#statPage').submit();
    });

    $('.exel').on('click', () => {
      $('#load_exel').val(1);
      $('#statPage').submit();
    });

    $('.load-stat-task').on('click', () => {
      $('#statPageTask').submit();
    });

    $('.exel-task').on('click', () => {
      $('#load_task_exel').val(1);
      $('#statPageTask').submit();
    });
  },
};

$(document).ready(function () {
  ListTasksDash.init();
  runDatePicker();
});
