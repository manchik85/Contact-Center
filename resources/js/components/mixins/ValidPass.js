export default {

  methods: {

    validPass (pass) {
      let re = /^([\s\S]{4,25})+$/;
      return re.test(pass);
    },

  }

}


