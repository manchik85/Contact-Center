<template>

  <div class="input-group">
    <input type="text" class="form-control" placeholder="Название" v-model="name_gov_role">
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
        name_gov_role: '',
      }
    },

    // mounted() {
    //   this.checkForm();
    // },

    created() {},


    methods: {

      checkForm: function () {

        if(this.name_gov_role!=='' ) {
          axios.post('gov_group_add', { gov_group_name: this.name_gov_role })
            .then((d) => { location.reload(); })
            .catch((e)=>{ console.log(e); });
        }

      },


    },


  }
</script>
