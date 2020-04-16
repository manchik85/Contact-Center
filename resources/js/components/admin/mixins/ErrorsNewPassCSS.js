export default {

  data: function () {
    return {
      errors_newpass: "",
      errors_newpass_css: {
        'has-error': false,
        'has-confirm': false
      },
    }
  },
  methods: {

    errorsNewPassCSS: function (error) {
      this.errors_newpass_css['has-error'] = true;
      this.errors_newpass_css['has-confirm'] = false;
      this.errors_newpass = error;
      $('newpass').focus();
    },

  }

}


