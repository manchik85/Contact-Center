export default {

  data: function () {
    return {
      oldpass: "",
      errors_oldpass: "",
      errors_oldpass_css: {
        'has-error': false,
        'has-confirm': false
      },
    }
  },
  methods: {

    errorsOldPassCSS: function (error) {
      this.errors_oldpass_css['has-error'] = true;
      this.errors_oldpass_css['has-confirm'] = false;
      this.errors_oldpass = error;
      $('oldpass').focus();
    },

  }

}


