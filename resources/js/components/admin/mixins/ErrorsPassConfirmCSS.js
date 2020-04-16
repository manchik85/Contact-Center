export default {

  data: function () {
    return {

      errors_passconfirm: "",
      errors_passconfirm_css: {
        'has-error': false,
        'has-confirm': false
      }

    }
  },

  methods: {

    errorsPassConfirmCSS: function (error) {
      this.errors_passconfirm_css['has-error'] = true;
      this.errors_passconfirm_css['has-confirm'] = false;
      this.errors_passconfirm = error;
      $('newpass').focus();
    },

  }

}


