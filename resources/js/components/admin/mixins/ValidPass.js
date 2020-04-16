export default {

  methods: {

    validPass (pass) {
      let re = /(?=.*[0-9])(?=.*[!@#$%^&*])(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z!@#$%^&*]{8,25}/g;
      return re.test(pass);
    },

  }

}


