<template>
  <div>
    <form method="post" enctype="multipart/form-data" ref="sub">
      <input type="hidden" name="user_client_id" id="user_client_id" v-model="user_client_id">
      <div class="row">
        <div class="col-md-6">
          <div class="row">
            <div class="col-xl-2 col-md-4">
              <div class="form-label">Тип обращения</div>
            </div>

            <div class="col-xl-4 col-md-4">
              <div class="frame-wrap">
                <div class="demo">
                  <div class="custom-control custom-radio" @click="adviceTask()">
                    <input type="radio" class="custom-control-input" value="advice_tack" id="advice_tack"
                           name="tack_type"
                           checked="">
                    <label class="custom-control-label" for="advice_tack">Консультация</label>
                  </div>
                  <div class="custom-control custom-radio" @click="requestTask()">
                    <input type="radio" class="custom-control-input" value="request_tack" id="request_tack"
                           name="tack_type">
                    <label class="custom-control-label" for="request_tack">Неисправность (ошибка)</label>
                  </div>
                  <div class="custom-control custom-radio" @click="errorTask()">
                    <input type="radio" class="custom-control-input" value="error_tack" id="error_tack"
                           name="tack_type">
                    <label class="custom-control-label" for="error_tack">Ошибочный звонок</label>
                  </div>
                </div>
              </div>
            </div>

            <div v-show="this.error_task" class="col-xl-6 col-md-4">
              <button class="btn btn-lg btn-success waves-effect waves-themed" type="button" @click="checkFormError()">
                Отправить
              </button>
            </div>

            <div v-show="!this.error_task && !this.advice_task" class="col-xl-6 col-md-4">
              <div class="form-group">
                <label class="form-label" for="priority">Приоритет</label>
                <select class="form-control" name="priority" id="priority" @change="prioritySelect()" v-model="priority">
                  <option value="3">Низкий</option>
                  <option value="2" selected>Средний</option>
                  <option value="1">Высокий</option>
                </select>
              </div>
              <div class="form-group" style="display: none;">
                <label class="form-label" for="term_complete">Сроки решения (рабочих дней)<span class="red">*</span></label>
                <div v-if="errors_term_complete !== ''" class="alert alert-danger a_c" role="alert">
                  {{ errors_term_complete }}
                </div>
                <input type="text" class="form-control" id="term_complete" name="term_complete" v-model="term_complete">
              </div>

              <div class="form-group">
                <label class="form-label" for="date_complete">Предположительная дата решения <span
                        class="red">*</span></label>
                <div v-if="errors_date_complete !== ''" class="alert alert-danger a_c" role="alert">
                  {{ errors_date_complete }}
                </div>
                <input type="text" class="form-control" id="date_complete" name="date_complete" v-model="date_complete">
              </div>
            </div>
          </div>
        </div>
        <div v-if="!this.error_task" class="col-md-6">
          <div class="form-group">
            <label class="form-label" for="process_name">Наименование процесса <span class="red">*</span></label>
            <div v-if="errors_process_name !== ''" class="alert alert-danger a_c" role="alert">
              {{errors_process_name}}
            </div>
            <!--            <input type="text" class="form-control" name="process_name" id="process_name" v-model="process_name">-->
            <select class="form-control" name="process_name" id="process_name" v-model="process_name">
              <option v-for="name of data.names" :value="name.process">{{name.process}}</option>
            </select>
          </div>
        </div>
      </div>
      <hr v-show="!this.error_task" class="my-3">
      <div v-show="!this.error_task" class="row">
        <div class="col-md-6">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="form-label" for="login">ИИН / Логин <span class="red">*</span></label>
                  <div v-if="errors_login !== ''" class="alert alert-danger a_c" role="alert">
                    {{ errors_login }}
                  </div>
                  <div class="input-group">
                    <input type="text" list="login_list" name="client_login" id="login" class="form-control" v-model="login"
                           @keyup.delete="clearUser()"
                           @keypress="getLogin()">
                    <datalist id="login_list">
                      <option :value="login.client_login" v-for="login of this.logins"></option>
                    </datalist>
                    <div class="input-group-append">
                      <button class="btn btn-secondary btn-w-m waves-effect waves-themed" type="button"
                              @click="completeLogin()">
                        выбрать
                      </button>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-6" v-show="!this.advice_task">
                <div class="form-group">
                  <label class="form-label" for="client_pass">Пароль </label>
                  <input type="text" class="form-control" name="client_pass" id="client_pass" v-model="client_pass">
                </div>
              </div>
            </div>
          <div style=""><div class="px_10"></div> <div class="px_10"></div> <div class="px_1"></div> <div class="px_1"></div></div>

          <div class="form-group">
            <label class="form-label" for="gov_name">Гос. орган <span class="red">*</span></label>
            <div v-if="errors_gov !== ''" class="alert alert-danger a_c" role="alert">
              {{ errors_gov }}
            </div>
            <div class="input-group">
              <input type="text" list="gov_name_list" name="gov_name" id="gov_name"  class="form-control"
                     v-model="gov_name" @keypress="getGov()"
                     @keyup.delete="clearGov()">
              <div class="input-group-append" @click="getGov()" >
                <button class="btn btn-primary waves-effect waves-themed" type="button"><i class="fal fa-search"></i></button>
              </div>
            </div>
            <div class="form-group" v-if="get_gov_show">
              <div class="px_5"></div>
              <select class="form-control" id="gov_name_list"  v-model="gov_name_select" @change="complGov()">
                <option :value="organ.gov_name" v-for="organ of this.otgans">{{organ.gov_name}}</option>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label class="form-label" for="fio">ФИО <span class="red">*</span></label>
            <div v-if="errors_fio !== ''" class="alert alert-danger a_c" role="alert">
              {{ errors_fio }}
            </div>
            <input type="text" class="form-control" name="client_fio" id="fio" v-model="fio">
          </div>

          <div class="form-group">
            <label class="form-label" for="spot">Должность <span class="red">*</span></label>
            <select class="form-control" name="client_spot" id="spot" v-model="spot">
              <option value="Кадровик">Кадровик</option>
              <option value="Рядовой пользователь" selected>Рядовой пользователь</option>
              <option value="Иные пользователи">Иные пользователи</option>
            </select>
          </div>
        </div>
        <div class="col-md-6">
          <div class="row" v-show="!this.advice_task">
            <div class="col-md-12">
              <div class="form-group">
                <label class="form-label" for="mail">Эл. почта</label>
                <div v-if="errors_mail !== ''" class="alert alert-danger a_c" role="alert">
                  {{ errors_mail }}
                </div>
                <div class="input-group">
                  <input type="email" list="email_list" class="form-control" name="client_mail" id="mail" v-model="mail"
                         @keyup.delete="clearUserMail()"
                         @keypress="getLoginMail()">
                  <datalist id="email_list">
                    <option :value="mail.client_mail" v-for="mail of this.mails"></option>
                  </datalist>
                  <div class="input-group-append">
                    <button class="btn btn-secondary btn-w-m waves-effect waves-themed" type="button"
                            @click="completeMail()">
                      выбрать
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div v-show="!this.advice_task">
            <div class="px_10"></div>
            <div class="px_10"></div>
            <div class="px_1"></div>
            <div class="px_1"></div>
          </div>

          <div class="form-group">
            <label class="form-label" for="numb">Номер <span class="red">*</span></label>
            <div v-if="errors_numb !== ''" class="alert alert-danger a_c" role="alert">
              {{ errors_numb }}
            </div>
            <input type="text" class="form-control" name="client_phone" id="numb" v-model="numb" @keyup.delete="clearNumb()">

            <div class="form-group" v-if="get_numb_show">
              <div class="px_5"></div>
              <select class="form-control" name="select_client_phone" id="numb_select" v-model="numb_select" @change="complNumb()">
                <option value=""></option>
                <option :value="phone_numb.src" v-for="phone_numb of this.phone_numbs">{{phone_numb.src}}</option>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label class="form-label" for="district">Регион <span class="red">*</span></label>
            <div v-if="errors_task_district !== ''" class="alert alert-danger a_c" role="alert">
              {{errors_task_district}}
            </div>
            <select class="form-control" name="task_district" id="district" v-model="task_district">
              <option value=""></option>
              <option :value="district" v-for="district of this.districts">{{district}}</option>
            </select>
          </div>
        </div>
      </div>

      <hr class="my-3">
      <div v-show="!this.error_task" class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label class="form-label" for="add_problem_request">Описание проблемы</label>
            <textarea class="form-control" id="add_problem_request" name="add_problem_request" rows="5"></textarea>
          </div>
          <div class="form-group"></div>
        </div>

        <div v-show="!this.advice_task" class="col-md-6">
          <div class="form-group">
            <label class="form-label" for="file">Вложения <span class="fw-300">(документы и/или рисунки)</span></label>
            <div class="custom-file">
              <input type="file" name="file[]" id="file" class="custom-file-input" multiple>
              <label class="custom-file-label" for="file"></label>
            </div>
          </div>
        </div>
      </div>

      <hr v-show="!this.error_task" class="my-3">
      <div v-show="!this.error_task" class="a_r">
        <button class="btn btn-lg btn-success waves-effect waves-themed" type="button" @click="checkForm()"> Сохранить
        </button>
      </div>
    </form>
  </div>
</template>
<script>

  import moment from 'moment';
  import ValidName from './mixins/ValidName';
  import ValidEmail from './mixins/ValidEmail';
  import ErrorsNameCSS from './mixins/ErrorsNameCSS';
  import ErrorsEmailCSS from './mixins/ErrorsEmailCSS';

  export default {

    props: [
      'data'
    ],

    mixins: [
      ValidName,
      ValidEmail,
      ErrorsEmailCSS,
      ErrorsNameCSS,
    ],

    // components: [
    //   moment
    // ],


    data: function () {
      return {
        get_gov_show: false,
        get_numb_show: true,
        error_task: false,
        advice_task: true,
        otgans: [],
        logins: [],
        mails: [],
        phone_numbs: this.getPhoneNumb(),
        districts: [
            'город Нур-Султан',
            'город Алматы',
            'город Шымкент',
            'Акмолинская область',
            'Актюбинская область',
            'Алматинская область',
            'Атырауская область',
            'Восточно-Казахстанская область',
            'Жамбылская область',
            'Западно-Казахстанская область',
            'Карагандинская область',
            'Костанайская область',
            'Кызылординская область',
            'Мангистауская область',
            'Павлодарская область',
            'Северо-Казахстанская область',
            'Туркестанская область'
        ],
        task_district: this.data.task_district,
        user_client_id: '',
        numb: this.data.client_phone,
        numb_select: '',
        login: this.data.client_login,
        mail: '',
        spot: '',
        gov_name: '',
        gov_name_select: '',
        priority: 2,
        process_name: '',
        fio: '',
        client_pass: '',
        term_complete: this.data.prior[1].prior,
        date_complete: moment().add('days', 2).format('DD.MM.YYYY'),

        errors_task_district: '',
        errors_process_name: '',
        errors_login: '',
        errors_mail: '',
        errors_numb: '',
        errors_spot: '',
        errors_fio: '',
        errors_gov: '',
        errors_term_complete: '',
        errors_date_complete: '',
      }
    },

    // mounted() {
    //   this.checkForm();
    // },

    created() {

      // console.log(this.data.prior[1].prior);

      // axios.post('api/get_gov_organs', {})
      //   .then((d) => {
      //     this.otgans = d.data;
      //   })
      //   .catch((e) => {
      //     console.log(e);
      //   });
    },


    methods: {

      errorTask() {
        this.error_task = true;
      },

      prioritySelect() {

        if(parseInt(this.priority)===3) {
          this.term_complete = this.data.prior[2].prior;
          this.date_complete = moment().add('days', this.data.prior[2].prior).format('DD.MM.YYYY');
        }
        else if(parseInt(this.priority)===2) {
          this.term_complete = this.data.prior[1].prior;
          this.date_complete = moment().add('days', this.data.prior[1].prior).format('DD.MM.YYYY');
        }
        else if(parseInt(this.priority)===1) {
          this.term_complete = this.data.prior[0].prior;
          this.date_complete = moment().add('days', this.data.prior[0].prior).format('DD.MM.YYYY');
        }

      },

      clearGov() {
        this.otgans = [];
        this.get_gov_show = false;
      },

      clearNumb() {
        if (document.getElementById("numb").value === '') {
            this.get_numb_show = true;
        }
      },

      complGov() {
        this.gov_name = this.gov_name_select;
        this.get_gov_show = false;
      },

      complNumb() {
        this.numb = this.numb_select;
        this.numb_select = '';
        this.get_numb_show = false;
      },

      getGov() {
        console.log(this.gov_name);
        if (this.gov_name.length > 3) {
          axios.post('api/get_gov_organs_vue', {'gov_name': this.gov_name})
            .then((d) => {
              this.otgans = d.data;
              this.get_gov_show = true;
            })
            .catch((e) => {
              console.log(e);
            });
        }
      },

      getPhoneNumb() {
          console.log("start getPhoneNumb");
          console.log("data:", this.data);
          axios.post('api/get_phone_numbs_vue'
            ).then((d) => {
                this.phone_numbs = d.data;
            })
            .catch((e) => {
                console.log(e);
            });
      },

      adviceTask() {
        this.error_task = false;
        this.advice_task = true;
      },

      requestTask() {
        this.error_task = false;
        this.advice_task = false;
      },

      checkForm() {

        this.errors_task_district = '';
        this.errors_process_name = '';
        this.errors_login = '';
        this.errors_mail = '';
        this.errors_numb = '';
        this.errors_spot = '';
        this.errors_fio = '';
        this.errors_term_complete = '';
        this.errors_date_complete = '';

        if (!this.validName(process_name.value)) {
          this.errors_process_name = 'Укажите Наименование процесса!';
          return false;
        } else {
          this.errors_process_name = "";
        }
        if (!this.validName(login.value)) {
          this.errors_login = 'Укажите Логин!';
          return false;
        } else {
          this.errors_login = "";
        }
        // if (!this.validEmail(mail.value)) {
        //   this.errors_mail = 'Укажите Электонную почту!';
        //   return false;
        // } else {
        //   this.errors_mail = "";
        // }
        if (!this.validName(numb.value)) {
          this.errors_numb = 'Укажите номер телефона!';
          return false;
        } else {
          this.errors_numb = "";
        }
        if (!this.validName(spot.value)) {
          this.errors_spot = 'Укажите Должность!';
          return false;
        } else {
          this.errors_spot = "";
        }
        if (!this.validName(fio.value)) {
          this.errors_fio = 'Укажите ФИО!';
          return false;
        } else {
          this.errors_fio = "";
        }

        if (!this.validName(this.task_district)) {
          this.errors_task_district = 'Укажите Регион!';
          return false;
        } else {
          this.errors_task_district = "";
        }
        // if ( !this.term_complete || parseInt(this.term_complete)===0) {
        //   this.errors_term_complete = 'Укажите Сроки решения!';
        //   return false;
        // } else {
        //   this.errors_term_complete = "";
        // }
        if (!this.validName(date_complete.value)) {
          this.errors_date_complete = 'Укажите Предположительную дату решения!';
          return false;
        } else {
          this.errors_date_complete = "";
        }
        if (!this.validName(this.gov_name)) {
          this.errors_gov = 'Укажите Гос. орган!';
          return false;
        } else {
          this.errors_gov = "";
        }

        let client_id = $('#user_client_id').val();

        this.$refs.sub.submit();
        /*
        if (client_id !== '') {

          this.checkUserForId(client_id);

        } else {

          axios.post('api/check_user_for_login', {
            client_login: login.value,
            client_mail: mail.value
          }).then((d) => {

            console.log(d.data);

            if (typeof (d.data[0]) != "undefined" && d.data[0] !== null && parseInt(d.data[0].id) > 0) {
              this.user_client_id = d.data[0].id;

            }

          }).catch((e) => {
            console.log(e);
          });

        }*/
      },

      checkFormError() {
        this.$refs.sub.submit();
      },

      checkUserForId(client_id) {

        axios.post('api/check_user_for_id', {
          id: client_id,
          client_login: login.value,
          client_mail: mail.value
        }).then((d) => {
          if (parseInt(d.data[0].id) > 0) {
            this.user_client_id = d.data[0].id;
          } else {
            this.user_client_id = '';
          }
          this.$refs.sub.submit();
        }).catch((e) => {
          console.log(e);
        });
      },

      clearUser() {
        this.user_client_id = '';
        this.spot = '';
        this.numb = '';
        this.mail = '';
        this.fio = '';
        this.gov_name = '';
        this.client_pass = '';
      },

      clearUserMail() {
        this.user_client_id = '';
        this.spot = '';
        this.numb = '';
        this.fio = '';
        this.login = '';
        this.gov_name = '';
        this.client_pass = '';
      },

      getLogin() {
        axios.post('api/get_login_clients', {client_login: this.login})
          .then((d) => {
            this.logins = d.data;
          })
          .catch((e) => {
            console.log(e);
          });
      },

      getLoginMail() {
        axios.post('api/get_mail_clients', {client_mail: this.mail})
          .then((d) => {
            this.mails = d.data;
          })
          .catch((e) => {
            console.log(e);
          });
      },

      completeLogin() {
        axios.post('api/get_single_clients', {client_login: this.login})
          .then((d) => {

            this.user_client_id = d.data[0].id;
            this.spot = d.data[0].client_spot;
            this.numb = d.data[0].client_phone;
            this.mail = d.data[0].client_mail;
            this.fio = d.data[0].client_fio;
            this.login = d.data[0].client_login;
            this.client_pass = d.data[0].client_pass;
            this.gov_name = d.data[0].gov_name;
            axios.post('api/get_region_name', {telephone: this.numb})
              .then((d) => {

                this.task_district = d.data[0].region;

              })
              .catch((e) => {
                console.log(e);
              });
          })
          .catch((e) => {
            console.log(e);
          });

      },

      completeMail() {
        axios.post('api/get_single_mail_clients', {client_mail: this.mail})
          .then((d) => {

            this.user_client_id = d.data[0].id;
            this.spot = d.data[0].client_spot;
            this.numb = d.data[0].client_phone;
            this.mail = d.data[0].client_mail;
            this.fio = d.data[0].client_fio;
            this.login = d.data[0].client_login;
            this.client_pass = d.data[0].client_pass;
            this.gov_name = d.data[0].gov_name;
          })
          .catch((e) => {
            console.log(e);
          });
      },
    },
  }
</script>
