<template>

  <div class="pad_10">
    <div class="row pad_10">

      <div class="col-lg-3">

        <div class="form-group">
          <label class="form-label" for="low">Низкий </label>
          <input type="text" class="form-control" name="low" id="low" v-model="low">
        </div>

      </div>
      <div class="col-lg-3">

        <div class="form-group">
          <label class="form-label" for="mid">Средний </label>
          <input type="text" class="form-control" name="mid" id="mid" v-model="mid">
        </div>
      </div>
      <div class="col-lg-3">

        <div class="form-group">
          <label class="form-label" for="big">Высокий </label>
          <input type="text" class="form-control" name="big" id="big" v-model="big">
        </div>
      </div>
      <div class="col-lg-3">
        <div class="px_10"></div>
        <button class="btn btn-lg btn-primary waves-effect waves-themed" type="button" @click="checkForm()"> Отправить
        </button>
      </div>

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
        low: this.data[2].prior,
        mid: this.data[1].prior,
        big: this.data[0].prior,
      }
    },


    created() {
      console.log(this.data);
    },

    methods: {

      checkForm: function () {

        if(this.low>0 && this.mid>0 && this.big>0 ) {

          const Data = {
            low: this.low,
            mid: this.mid,
            big: this.big,
          };

          axios.post('/prior_days', Data)
            .then((d) => {
              location.reload();
            })
            .catch((e)=>{ console.log(e); });
        }
      },


    },


  }
</script>
