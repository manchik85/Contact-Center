$('body').prepend('<div id="shim" align="center"><div id="modal"><div id="close"><img src="/js/plugin/photogalery/img/lightbox/test/close.png"></div> <table id="table_gal" cellpadding="0" cellspacing="0" border="0" align="center"><tr><td id="left_str" width="100" align="center"><table id="left_str_active"><tr><td align="center"></td></tr></table></td><td id="for_image" align="center"><img src=""></td><td id="right_str" width="100" align="center"><table id="right_str_active"><tr><td align="center"></td></tr></table></td></tr><tr><td width="100"></td><td id="description"><div id="date"></div><div id="description_text"></div></td><td width="100"></td></tr></table></div></div>');

$('#left_str_active td').mouseover(function() {$('#left_str_active').animate({'opacity' : '1'}, 150);});
$('#left_str_active td').mouseout(function() {$('#left_str_active').animate({'opacity' : '0.3'}, 150);});

$('#right_str_active td').mouseover(function() {$('#right_str_active').animate({'opacity' : '1'}, 150);});
$('#right_str_active td').mouseout(function() {$('#right_str_active').animate({'opacity' : '0.3'}, 150);});

$('#close').mouseover(function() {$(this).animate({'opacity' : '1'}, 150);});
$('#close').mouseout(function() {$(this).animate({'opacity' : '0.3'}, 150);});

$('.gallery a').click(function(event) {

  event.preventDefault(); //предотвращаем переход по ссылке

  var speed = 0; //задержка при смене изображений
  var index = $('.gallery a').index(this); //получаем индекс текущего элемента
  var index_primary = 0; //получаем индекс первого элемента
  var index_final = $('.gallery a').length -1; //получаем индекс последнего элемента

  $('body').css({'overflow' : 'hidden'}); //замораживаем фон
  $('#date').html($(this).attr('title').replace(/\n/g, '<br />')); //вставляем дату
  $('#description_text').html($(this).attr('alt').replace(/\n/g, "<br />")); //вставляем описание
  $('#shim').css({'display' : 'block'}).animate({'opacity' : '1'}, 150);   //делаем галлерею видимой
  $('#right_str_active, #left_str_active').fadeOut(0); //скрываем обе стрелки
  if(index!=index_final) {$('#right_str_active').fadeIn(0);} //показываем правую стрелку
  if(index!=index_primary) {$('#left_str_active').fadeIn(0);}  //показываем левую стрелку
  $('#for_image img').attr('src', $(this).attr('href')); //подставляем src нужной картинки

  /*меняем изображение при клике на правую стрелку*/
  $('#right_str_active').click(function() { //клик на правую стрелку
    $('#for_image img').animate({'opacity' : '0'}, speed); //скрываем текущее изображение
    $('#left_str_active').fadeIn(speed); //показываеем левую стрелку
    if(index+1==index_final) {$('#right_str_active').fadeOut(0);} //скрываем правую стрелку, если последний элемент
    setTimeout(function() {
      $('#date').html($('.gallery a').eq(index+1).attr('title').replace(/\n/g, "<br />")); //вставляем дату
      $('#description_text').html($('.gallery a').eq(index+1).attr('alt').replace(/\n/g, "<br />")); //вставляем описание
      var new_src = $('.gallery a').eq(index+1).attr('href'); //получаем путь для следующей картинки
      $('#for_image img').attr('src', new_src).animate({'opacity' : '1'}, speed); //подставляем новый путь и делаем картинку видимой
      index = index+1;}, speed);
  })
  /*меняем изображение при клике на правую стрелку конец*/

  /*меняем изображение при клике на левую стрелку*/
  $('#left_str_active').click(function() {
    $('#for_image img').animate({'opacity' : '0'}, speed); //скрываем текущее изображение
    $('#right_str_active').fadeIn(speed); //показываеем правую стрелку
    if(index-1==index_primary) {$('#left_str_active').fadeOut(0);} //скрываем стрелку, если последний элемент
    setTimeout(function() {
      $('#date').html($('.gallery a').eq(index-1).attr('title').replace(/\n/g, "<br />")); //вставляем дату
      $('#description_text').html($('.gallery a').eq(index-1).attr('alt').replace(/\n/g, "<br />")); //вставляем описание
      var new_src = $('.gallery a').eq(index-1).attr('href'); //получаем путь для предыдущей картинки
      $('#for_image img').attr('src', new_src).animate({'opacity' : '1'}, speed);//подставляем новый путь и делаем картинку видимой
      index = index-1;}, speed); //меняем index на номер предыдущей картинки
  })
  /*меняем изображение при клике на левую стрелку конец*/
});

/*закрытие галлереи*/
$('#close').click(function() {
  $('body').css({'overflow' : 'auto'}); //размораживаем фон
  $('#shim').animate({'opacity' : '0'}, 250);
  setTimeout(function() {$('#shim').css({'display' : 'none'});}, 150); //устанавливаем задержку
});
/*закрытие галлереи конец*/
