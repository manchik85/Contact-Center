<template>

  <div class="input-group">
    <input type="text" class="form-control" placeholder="Название" v-model="name_gov">
    <input type="text" class="form-control" placeholder="Транслит" v-model="alias_gov">
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
        alias_gov: ''
      }
    },

    // mounted() {
    //   this.checkForm();
    // },

    created() {},


    methods: {

      checkForm: function () {

        axios.post('form_add', { form_name: this.name_gov, form_alias: this.alias_gov })
             .then((d) => { location.reload(); })
             .catch((e)=>{ console.log(e); });


      },


    },


  }
</script>
