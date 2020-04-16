<template>

  <div class="input-group">
    <input type="text" class="form-control" placeholder="Название" v-model="name_gov">
    <input type="text" class="form-control" placeholder="Email" v-model="mail_gov">
    <div class="input-group-append">
      <button class="btn btn-primary waves-effect waves-themed" type="button" @click="checkForm">Добавить</button>
    </div>
  </div>

</template>
<script>

  export default {

    props: [
      'data'
    ],

    data: function () {
      return {
        name_gov: '',
        mail_gov: ''
      }
    },

    // mounted() {
    //   this.checkForm();
    // },

    created() {},


    methods: {

      checkForm: function () {

        if(this.name_gov!=='' ) {
          axios.post('gov_add', { gov_name: this.name_gov, gov_mail: this.mail_gov })
            .then((d) => {
              location.reload();
            })
            .catch((e)=>{ console.log(e); });
        }

      },


    },


  }
</script>
