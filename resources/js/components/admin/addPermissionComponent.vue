<template>
  <div class="container div_c">

    <div class="div px_20 float_n clb"></div>

    <div class="row">
      <div class="col-lg-8 col-xl-6 col-md-10 panel-content">
        <div class="px_10"></div>

        <div class="input-group flex-nowrap" :class="errors_name_css">
          <input id="name" type="text" class="form-control" name="name"required
                 placeholder="Роль"
                 aria-describedby="addon-wrapping-right">

          <div class="input-group-append">
            <span class="input-group-text"><i class="fal fa-lock-alt fs-xl"></i></span>
          </div>

        </div>
        <div v-if="errors_name !== ''" class="alert alert-danger a_c" role="alert">
          {{errors_name}}
        </div>
        <div class="px_10"></div>
        <div class="px_10"></div>
        <div class="btn btn-sm btn-primary pull-right" id="add-batton" @click="checkForm">Добавить
        </div>
      </div>
    </div>


  </div>
</template>
<script>
  import  axios                from 'axios';
  import  ValidName            from './mixins/ValidName';
  import  ErrorsNameCSS        from './mixins/ErrorsNameCSS';

  export default {

    props: [
      'data'
    ],

    data: function () {
      return {
        errors_name: '',
        name: ''
      }
    },

    mixins: [
      ValidName,
      ErrorsNameCSS,
    ],

    methods: {

      checkForm: function (e) {

        let name = $('#name').val();

        this.errors_name = "";

        if (!name) {
          this.errorsNameCSS("Вы не указали Роль!");
          e.preventDefault();
          return false;
        }
        else if (!this.validName(name)) {
          this.errorsNameCSS("Вы не указали Роль!");
          e.preventDefault();
          return false;
        }
        else {
          this.errors_name_css['has-error'] = false;
          this.errors_name_css['has-confirm'] = true;
          this.errors_name = "";


          axios.post('permissions_add', {
            name: name,
          }).then((response) => {

            if (response.data.st > 0) {

              $('#add-batton').removeClass('btn-danger').removeClass('btn-primary').addClass('btn-success').text('Роль добавлена ');

              $('#name').val('');
            }
            else {
              $('#add-batton').removeClass('btn-primary').removeClass('btn-success').addClass('btn-danger').text('Что-то не так пошло (((');
              console.log(response.data.st);
            }
          });

        }
      },
    },


  }
</script>
