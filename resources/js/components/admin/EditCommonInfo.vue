<template>
  <div class="container">
    <input id="userId"    type="hidden" name="userId"    :value="data.id">
    <input id="gov_group" type="hidden" name="gov_group" :value="this.group_complete">
    <input id="gov_group_master" type="hidden" name="gov_group_master" :value="this.group_complete_master">
    <input id="gov_group_root" type="hidden" name="gov_group_root"   :value="this.group_complete_root">

    <div class="px_10"></div>

    <div class="row">
      <div class="col-lg-8 col-xl-6 col-md-10 panel-content">

        <div class="input-group flex-nowrap" v-bind:class="errors_name_css">
          <input id="name" type="text" class="form-control" name="name" placeholder="Имя" value="" required
                 v-model.trim="data.name"
                 aria-describedby="addon-wrapping-right">
          <div class="input-group-append">
            <span class="input-group-text"><i class="fal fa-user fs-xl"></i></span>
          </div>
        </div>
        <div v-if="errors_name !== ''" class="alert alert-danger a_c" role="alert">
          {{errors_name}}
        </div>
        &nbsp;
        <div class="input-group flex-nowrap" v-bind:class="errors_email_css">
          <input id="email" type="text" class="form-control" name="email" placeholder="E-Mail" value="" required
                 v-model.trim="data.email"
                 aria-describedby="addon-wrapping-right">
          <div class="input-group-append">
            <span class="input-group-text"><i class="fal fa-envelope fs-xl"></i></span>
          </div>
        </div>
        <div v-if="errors_email !== ''" class="alert alert-danger a_c" role="alert">
          {{errors_email}}
        </div>
        &nbsp;
        <div class="input-group flex-nowrap" v-bind:class="errors_name_css">
          <input id="users_phone" type="tel" class="form-control" name="users_phone" placeholder="Внутренний номер Оператора"
                 value="" v-mask="['4###']"
                 v-model.trim="data.users_phone"
                 aria-describedby="addon-wrapping-right">
          <div class="input-group-append">
            <span class="input-group-text"><i class="fal fa-phone-square fs-xl"></i></span>
          </div>
        </div>
        <div class="a_r lit">В формате: <b>4###</b></div>

        <div class="px_10"></div>
        <div class="form-group ">
          <div class="input-group flex-nowrap" v-bind:class="errors_name_css">
            <input id="users_cont_phone" type="tel" class="form-control" name="users_cont_phone"
                   placeholder="Контактный телефон"
                   v-model.trim="data.users_cont_phone"
                   value="" v-mask="['(###)###-##-##']" aria-describedby="addon-wrapping-right">
            <div class="input-group-append">
              <span class="input-group-text"><i class="fal fa-phone fs-xl"></i></span>
            </div>
          </div>
          <div class="a_r lit">В формате: <b>(999)999-99-99</b></div>
        </div>


        <div class="panel-container show">
          <div class="panel-content">

            <select class="form-control" name="group" id="group" v-model="group" @change="selectGroup()">
              <option v-for="gr in groups" :value="gr"
                      :selected="gr === group ? 'selected' : ''">{{gr}}
              </option>
            </select>

            &nbsp;
            <div v-if="sl_group_st">
              <select class="form-control" name="sl_group" id="sl_group" v-model="sl_group"
                      @change="selectSlGroup()">

                <optgroup label="Местные исполнительные органы:"></optgroup>
                <option v-for="gr_0 in sl_group_0" :value="gr_0"
                        :selected="gr_0 === sl_group ? 'selected' : ''">{{gr_0}}
                </option>

                <optgroup label="Центральные государственные органы:"></optgroup>
                <option v-for="gr_1 in sl_group_1" :value="gr_1"
                        :selected="gr_1 === sl_group ? 'selected' : ''">{{gr_1}}
                </option>

                <optgroup label="Центральные исполнительные органы:"></optgroup>
                <option v-for="gr_2 in sl_group_2" :value="gr_2"
                        :selected="gr_2 === sl_group ? 'selected' : ''">{{gr_2}}
                </option>
              </select>
            </div>

          </div>
        </div>



      </div>
    </div>
    <div class="footer py-3 pad_20">
      <button class="btn btn-primary" id="add-batton" @click="checkForm">
        <i class="fal fa-check"></i> &nbsp;Сохранить изменения&nbsp;
      </button>
    </div>
    <div class="div px_10 float_n clb"></div>


  </div>
</template>

<script>
  import  axios                from 'axios';
  // import  $                    from 'jquery';
  import  _                    from 'lodash';
  import  ValidName            from './mixins/ValidName';
  import  ValidEmail           from './mixins/ValidEmail';
  import  ErrorsNameCSS        from './mixins/ErrorsNameCSS';
  import  ErrorsEmailCSS       from './mixins/ErrorsEmailCSS';

  export default {

    props: [
      'data'
    ],

    data: function () {
      return {

        sl_group_st: false,
        group: 'Управление технического сопровождения процедур тестирования',
        group_complete: this.data.gov_group,
        group_complete_master: this.data.gov_group_master,
        group_complete_root:   this.data.gov_group_root,

        sl_group: 'Аппарат акима Акмолинской области',
           groups:  [],
        sl_group_0: [
          'Аппарат акима Акмолинской области',
          'Аппарат акима Карагандинской области',
          'Аппарат акима Восточно-Казахстанской области',
          'Аппарат акима Жамбылской области',
          'Аппарат акима Западно-Казахстанской области',
          'Аппарат акима Кызылординской области',
          'Аппарат акима Павлодарской области',
          'Аппарат акима Костанайской области',
          'Аппарат акима Мангистауской области',
          'Аппарат акима Атырауской области',
          'Аппарат акима Северо-Казахстанской области',
          'Аппарат акима Алматинской области',
          'Аппарат акима Актюбинской области',
          'Аппарат акима Туркестанской области',
          'Аппарат акима города Алматы',
          'Аппарат акима города Нур-Султан',
          'Аппарат акима города Шымкента',
        ],
        sl_group_1: [
          'Агентство Республики Казахстан по делам государственной службы',
          'Агентство Республики Казахстан по противодействию коррупции',
        ],
        sl_group_2: [
          'Министерство культуры и спорта РК',
          'Министерство национальной экономики РК',
          'Министерство индустрии и инфраструктурного развития Республики Казахстан',
          'Министерство образования и науки Республики Казахстан',
          'Министерство энергетики Республики Казахстан',
          'Министерство внутренних дел Республики Казахстан',
          'Министерство сельского хозяйства Республики Казахстан',
          'Министерство здравоохранения Республики Казахстан',
          'Министерство юстиции Республики Казахстан',
          'Министерство обороны Республики Казахстан',
          'Министерство иностранных дел Республики Казахстан',
          'Министерство финансов Республики Казахстан',
          'Министерство цифрового развития, инноваций и аэрокосмической промышленности Республики Казахстан',
          'Министерство информации и общественного развития Республики Казахстан',
          'Министерство труда и социальной защиты населения Республики Казахстан',
          'Министерство экологии, геологии и природных ресурсов Республики Казахстан',
        ]

      }
    },

    // mounted() {
    //   this.checkForm();
    // },

    mixins: [
      ValidName,
      ValidEmail,
      ErrorsEmailCSS,
      ErrorsNameCSS,
    ],

    methods: {

      selectGroup() {
        if (this.group === 'Дирекция автоматизированной единой службы управления персоналом') {
          this.sl_group_st    = true;
          this.group_complete = 'Аппарат акима Акмолинской области';
          this.sl_group       = 'Аппарат акима Акмолинской области';
          this.group_complete_root = 'Дирекция автоматизированной единой службы управления персоналом';
          this.group_complete_master = 'Местные исполнительные органы';
        } else {
          this.sl_group_st    = false;
          this.group_complete_master = '';
          this.group_complete_root = '';
          this.group_complete = this.group;
        }
      },

      selectSlGroup() {
        this.group_complete = this.sl_group;
        this.group_complete_root = 'Дирекция автоматизированной единой службы управления персоналом';
        if( _.includes(this.sl_group_0, this.sl_group) ) {
          this.group_complete_master = 'Местные исполнительные органы';
        }
        else if( _.includes(this.sl_group_1, this.sl_group) ) {
          this.group_complete_master = 'Центральные государственные органы';
        }
        else if( _.includes(this.sl_group_2, this.sl_group) ) {
          this.group_complete_master = 'Центральные исполнительные органы';
        }
      },

      checkForm: function (e) {

        this.errors_name = "";
        this.errors_email = "";
        if (!this.data.name) {
          this.errorsNameCSS("Вы не указали Имя");
          e.preventDefault();
          return false;
        }
        else if (!this.validName(this.data.name)) {
          this.errorsNameCSS("Вряд ли существует такое Имя: " + this.data.name);
          e.preventDefault();
          return false;
        }
        else {
          this.errors_name_css['has-error']   = false;
          this.errors_name_css['has-confirm'] = true;
          this.errors_name = "";
        }

        if (!this.data.email) {
          this.errorsEmailCSS("Необходимо указать e-mail");
          e.preventDefault();
          return false;
        }
        else if (!this.validEmail(this.data.email)) {
          this.errorsEmailCSS("Необходимо указать корректный e-mail");
          e.preventDefault();
          return false;
        }
        else {
          axios.post('/unic_email', { mail: this.data.email, id: this.data.id, _token: this.data._token }).then((response) => {
            if (response.data === false) {
              this.errorsEmailCSS("Вы не можете использовать данный e-mail");
              e.preventDefault();
              return false;
            }
            else {
              this.errors_email_css['has-error']   = false;
              this.errors_email_css['has-confirm'] = true;
              this.errors_email = "";
              $("#profile_common_info").submit();
            }
          });
        }
      },
    },

    created() {

      axios.post('/api/get_roles_group', {})
        .then((d) => {
          this.groups = d.data;
        })
        .catch(function (error) {
          console.log(error);
        });

      if(this.data.gov_group!=='Управление технического сопровождения процедур тестирования' &&
        this.data.gov_group !=='Управление технического сопровождения ИИС "Е-қызмет"' &&
        this.data.gov_group !=='') {

        this.group = 'Дирекция автоматизированной единой службы управления персоналом';
        this.sl_group_st = true;
        this.sl_group = this.data.gov_group;

      }
      else if(this.data.gov_group===''){
        this.group          = 'Управление технического сопровождения процедур тестирования';
        this.group_complete = 'Управление технического сопровождения процедур тестирования';
      }
      else {
        this.group = this.data.gov_group;
        this.sl_group_st = false;
      }
    }

  }
</script>
