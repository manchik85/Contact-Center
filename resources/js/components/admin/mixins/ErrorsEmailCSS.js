export default {

  data: function () {
    return {
      errors_email: "",
      errors_email_css: {
        'has-error': false,
        'has-confirm': false
      },
    }
  },
  methods: {

    errorsEmailCSS: function (error) {
      this.errors_email_css['has-error'] = true;
      this.errors_email_css['has-confirm'] = false;
      this.errors_email = error;
      $('#email').focus();
    },

  }

}


