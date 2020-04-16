<template>
  <div id="widget-grid">
    <div class="row">
      <div class="col-md-12 panel panel-default pad_0">
        <div role="content">
          <div class="widget-body row">
            <div class="col-md-4">
              <div class="pad_10">
                <div class="px_10"></div>
                <div class="pad_0_10"><b>Аккаунт</b>
                  <hr>
                </div>
                <div class="panel-container show">
                  <div class="panel-content" id="account_add_users">

                    <div class="input-group flex-nowrap" v-bind:class="errors_name_css">
                      <input id="name" type="text" class="form-control" name="name" placeholder="Имя" value="" required
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
                      <input id="email" type="text" class="form-control" name="email" placeholder="E-Mail" value=""
                             required
                             aria-describedby="addon-wrapping-right">
                      <div class="input-group-append">
                        <span class="input-group-text"><i class="fal fa-envelope fs-xl"></i></span>
                      </div>
                    </div>
                    <div v-if="errors_email !== ''" class="alert alert-danger a_c" role="alert">
                      {{errors_email}}
                    </div>
                    &nbsp;
                    <div class="input-group flex-nowrap" v-bind:class="errors_newpass_css">
                      <input id="new-password" type="password" class="form-control" name="new-password"
                             placeholder="Пароль" value="" required
                             @keyup="complexityPassword"
                             v-model.trim="newpass"
                             aria-describedby="addon-wrapping-right">
                      <div class="input-group-append">
                        <span class="input-group-text"><i class="fal fa-lock fs-xl"></i></span>
                      </div>
                    </div>
                    <div class="progress" v-if="progress_bar">
                      <div class="progress-bar progress-bar-striped bg-warning" role="progressbar"
                           style="width: 20%"
                           id="progress-bar"
                           aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div v-if="errors_newpass !== ''" class="alert alert-danger a_c" role="alert">
                      {{errors_newpass}}
                    </div>
                    <template v-if="progress_bar">
                      &nbsp;
                      <div class="input-group flex-nowrap" v-bind:class="errors_passconfirm_css">
                        <input class="form-control" id="password-confirm" type="password" name="password-confirm"
                               placeholder="Подтверждение" value="" required
                               v-model.trim="passconfirm"
                               aria-describedby="addon-wrapping-right">
                        <div class="input-group-append">
                          <span class="input-group-text"><i class="fal fa-lock fs-xl"></i></span>
                        </div>
                      </div>
                      <div v-if="errors_passconfirm !== ''" class="alert alert-danger a_c" role="alert">
                        {{errors_passconfirm}}
                      </div>
                    </template>

                  </div>
                </div>
              </div>
            </div>
            <div v-if="data.status>=5" class="col-md-4">

              <div class="pad_10">
                <div class="px_10"></div>
                <div class="pad_0_10"><b>Группа</b>
                  <hr>
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

                <div class="px_10"></div>
                <div class="px_10"></div>
                <div class="px_10"></div>
                <div class="pad_0_10"><b>Уровень доступа</b>
                <hr>
                </div>
                <div class="pad_0_20">

                  <div v-for="user in this.user_group" class="fl_l">
                    <div class="custom-control custom-radio pad_0_20">
                      <input type="radio" class="custom-control-input" :id="`user_${user.group_id}`" name="level_user"
                             :value="user.group_id" :checked="user.checked">
                      <label class="custom-control-label" :for="`user_${user.group_id}`">{{user.group}}</label>
                    </div> &nbsp;
                  </div>

                </div>
              </div>

            </div>
            <div class="col-md-4">
              <div class="txt-color-darken todo-group-title"></div>
              <div class="pad_10">
                <div class="px_10"></div>
                <div class="pad_0_10"><b>Дополнительная информация</b>
                  <hr>
                </div>
                <div class="px_1"></div>
                <div class="px_7"></div>
                <div class="form-group ">
                  <div class="input-group flex-nowrap" v-bind:class="errors_name_css">
                    <input id="users_phone" type="tel" class="form-control" name="users_phone"
                           placeholder="Внутренний номер Оператора"
                           value="" v-mask="['4###']" aria-describedby="addon-wrapping-right">
                    <div class="input-group-append">
                      <span class="input-group-text"><i class="fal fa-phone-square fs-xl"></i></span>
                    </div>
                  </div>
                  <div class="a_r lit">В формате: <b>4###</b></div>
                </div>
                <div class="form-group ">
                  <div class="input-group flex-nowrap" v-bind:class="errors_name_css">
                    <input id="users_cont_phone" type="tel" class="form-control" name="users_cont_phone"
                           placeholder="Контактный телефон"
                           value="" v-mask="['(###)###-##-##']" aria-describedby="addon-wrapping-right">
                    <div class="input-group-append">
                      <span class="input-group-text"><i class="fal fa-phone fs-xl"></i></span>
                    </div>
                  </div>
                  <div class="a_r lit">В формате: <b>(999)999-99-99</b></div>
                </div>
                <div class="form-group">
                  <div class="input-group" id="description">
                    <textarea class="form-control pad_10" rows="3" id="users_deck" name="users_deck"
                              placeholder="Комментарий"></textarea>
                  </div>
                </div>
              </div>
            </div>
            <div class="clb"></div>
          </div>
          <div class="footer a_r pad_20">
            <button class="btn btn-primary" id="add-batton" @click="checkForm"> Отправить</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
  import axios from 'axios';
  import  _  from 'lodash';

  import ValidName from './mixins/ValidName';
  import ValidEmail from './mixins/ValidEmail';
  import ValidPass from './mixins/ValidPass';
  import ErrorsNameCSS from './mixins/ErrorsNameCSS';
  import ErrorsEmailCSS from './mixins/ErrorsEmailCSS';
  import ErrorsPassConfirmCSS from './mixins/ErrorsPassConfirmCSS';
  import ErrorsNewPassCSS from './mixins/ErrorsNewPassCSS';
  import ComplexityPassword from './mixins/ComplexityPassword';

  export default {
    props: [
      'data'
    ],

    data: function () {
      return {
        errors_name: "",
        errors_email: "",
        progress_bar: false,
        group: 'Управление технического сопровождения процедур тестирования',
        group_complete: 'Управление технического сопровождения процедур тестирования',
        group_complete_master: '',
        group_complete_root: '',
        sl_group: 'Аппарат акима Акмолинской области',
        user_group: [],

        sl_group_st: false,

        new_users_status: false,
        passconfirm: "",

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

    created() {
      axios.post('/api/get_permission_group', {})
        .then((d) => {
          this.user_group = d.data;
        })
        .catch(function (error) {
          console.log(error);
        });


      axios.post('/api/get_roles_group', {})
        .then((d) => {
          this.groups = d.data;
        })
        .catch(function (error) {
          console.log(error);
        });
    },

    mixins: [
      ValidName,
      ValidEmail,
      ValidPass,
      ErrorsPassConfirmCSS,
      ErrorsNewPassCSS,
      ErrorsEmailCSS,
      ErrorsNameCSS,
      ComplexityPassword,
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


      checkForm(e) {

        let name  = $('#name').val();
        let email = $('#email').val();

        let level_user       = $("input[type=radio]:checked").val();
        let users_phone      = $('#users_phone').val();
        let users_cont_phone = $('#users_cont_phone').val();
        let users_deck       = $('#users_deck').val();

        this.errors_name  = "";
        this.errors_email = "";
        this.errors_passconfirm = "";

        if (!name) {
          this.errorsNameCSS("Вы не указали Имя");
          e.preventDefault();
          return false;
        } else if (!this.validName(name)) {
          this.errorsNameCSS("Вряд ли существует такое Имя: " + name);
          e.preventDefault();
          return false;
        } else {
          this.errors_name_css['has-error'] = false;
          this.errors_name_css['has-confirm'] = true;
          this.errors_name = "";
        }

        if (!email) {
          this.errorsEmailCSS("Необходимо указать e-mail");
          e.preventDefault();
          return false;
        } else if (!this.validEmail(email)) {
          this.errorsEmailCSS("Необходимо указать корректный e-mail");
          e.preventDefault();
          return false;
        } else {
          axios.post('/unic_email', {mail: email, id: null}).then((response) => {
            if (response.data === false) {
              this.errorsEmailCSS("Вы не можете использовать данный e-mail");
              e.preventDefault();
              return false;
            } else {
              this.errors_email_css['has-error'] = false;
              this.errors_email_css['has-confirm'] = true;
              this.errors_email = "";
              // $("#profile_common_info").submit();
            }
          });
        }

        if (!this.newpass) {
          this.errorsNewPassCSS("Необходимо указать Пароль");
          e.preventDefault();
          return false;
        } else if (!this.validPass(this.newpass)) {
          this.errorsNewPassCSS("8-25 знаков - латинские буквы, цифры и символы: !@#$%^&*()_-+=|/.,:;[]{}");
          e.preventDefault();
          return false;
        }

        if (this.newpass !== this.passconfirm) {

          this.errors_passconfirm_css['has-error'] = true;
          this.errors_passconfirm_css['has-confirm'] = false;

          this.errorsPassConfirmCSS("Пароль и подтверждение должны совпадать!");
          e.preventDefault();
          return false;
        } else {
          this.errors_passconfirm_css['has-error'] = false;
          this.errors_passconfirm_css['has-confirm'] = true;
          this.errors_newpass_css['has-error'] = false;
          this.errors_newpass_css['has-confirm'] = true;
          this.errors_passconfirm = "";


          console.log({
            name: name,
            email: email,
            level_user: level_user,
            password: this.newpass,
            password_confirmation: this.passconfirm,
            users_phone: users_phone,
            users_cont_phone: users_cont_phone,
            gov_group_master : this.group_complete_master ,
            gov_group_root: this.group_complete_root,
            gov_group: this.group_complete,
            users_deck: users_deck
          });

          axios.post('add_users', {
            name: name,
            email: email,
            level_user: level_user,
            password: this.newpass,
            password_confirmation: this.passconfirm,
            users_phone: users_phone,
            users_cont_phone: users_cont_phone,
            gov_group_master : this.group_complete_master ,
            gov_group_root: this.group_complete_root,
            gov_group: this.group_complete,
            users_deck: users_deck
          }).then((response) => {

            if (response.data.st > 0) {

              $('#add-batton').removeClass('btn-danger').removeClass('btn-primary').addClass('btn-success').text(' Сотрудник добавлен ');

              $('#name').val('');
              $('#email').val('');
              $('#users_phone').val('');
              $('#users_cont_phone').val('');
              $('#users_deck').val('');
              $('#new-password').val('');
              $('#password-confirm').val('');

            } else {
              $('#add-batton').removeClass('btn-primary').removeClass('btn-success').addClass('btn-danger').text('Что-то не так пошло (((');

              console.log(response.data.st);

            }
          });
        }
      },
    },


  }
</script>
