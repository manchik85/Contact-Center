export default {

  methods: {

    validName: function (name) {
      let re = /^([\s\S]{2,25})+$/;
      return re.test(name);
    },

  }

}


