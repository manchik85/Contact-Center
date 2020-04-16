<template>

  <div class="input-group">
    <input type="text" class="form-control" placeholder="IP" v-model="ip">
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
        ip: ''
      }
    },

    // mounted() {
    //   this.checkForm();
    // },

    created() {},


    methods: {

      checkForm: function () {
        axios.post('white_ip_add', { ip: this.ip })
          .then((d) => { location.reload(); })
          .catch((e)=>{ console.log(e); });
      },


    },


  }
</script>
