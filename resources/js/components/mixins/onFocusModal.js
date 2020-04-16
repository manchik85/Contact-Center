export default {

  methods: {

    onFocus(label, group = 'phone_group') {
      $('#' + label).addClass('focused').removeClass('opacity_03');
      $('#'+group).removeClass('error_input');
    },
    offFocus(label, fild) {

      let val = $('#' + fild).val();

      if (val === '') {
        $('#' + label).removeClass('focused').removeClass('opacity_03');
      }
      else {
        $('#' + fild).css({'border-color': '#8c0701'});
        $('#' + label).addClass('opacity_03');
      }
    },


  }

}

