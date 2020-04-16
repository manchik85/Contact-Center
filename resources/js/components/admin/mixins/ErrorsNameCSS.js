export default {

  data: function () {
    return {
      errors_name: "",
      errors_name_css: {
        'has-error': false,
        'has-confirm': false
      },
    }
  },
  methods: {

    errorsNameCSS: function (error) {
      this.errors_name_css['has-error'] = true;
      this.errors_name_css['has-confirm'] = false;
      this.errors_name = error;
      $('#name').focus();
    },

  }

}


