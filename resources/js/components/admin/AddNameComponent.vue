<template>

  <div class="input-group">
    <input type="text" class="form-control" placeholder="Название" v-model="name_form">
    <div class="input-group-append">
      <button class="btn btn-primary waves-effect waves-themed" type="button" @click="checkName">Добавить!</button>
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
        name_form: '',
      }
    },

    // mounted() {
    //   this.checkForm();
    // },

    created() {},


    methods: {

      checkName: function () {

        if(this.name_form!=='' ) {
          axios.post('/process_names', { process: this.name_form })
            .then((d) => {
              location.reload();
            })
            .catch((e)=>{ console.log(e); });
        }
      },


    },


  }
</script>
