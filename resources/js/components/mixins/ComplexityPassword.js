export default {

  data: function () {
    return {
      progress_bar: false,
      newpass:      "",
    }
  },
  methods: {

    complexityPassword: function (e) {
      let statusNewPass = this.checkComplexityPassword(this.newpass);
      let color = 'bg-warning';
      let width = 20;

      if (statusNewPass.simbols === false) {
        this.progress_bar = false;

        this.errorsNewPassCSS("<div class='pad_10_17'>Недопустимый символ! <br>Только латинские буквы, цифры и символы: !@#$%^&*()_-+=|/.,:;[]{}</div>");
        e.preventDefault();
        return false;
      }
      else {
        this.errors_newpass = "";
      }

      if (statusNewPass.length > 3) {
        this.progress_bar = true;
      }
      else {
        this.progress_bar = false;
      }

      if (statusNewPass.slowly === 1) {
        color = 'bg-warning';
        width = 5 * statusNewPass.length;

        if (width > 33) width = 33;
      }
      else if (statusNewPass.slowly === 2) {
        color = 'bg-info';
        width = (10 * statusNewPass.rat) + (5 * statusNewPass.length);
        if (width > 66) width = 66;
      }
      else if (statusNewPass.slowly === 3) {
        color = 'bg-success';
        width = (10 * statusNewPass.rat) + (5 * statusNewPass.length);
        if (width < 67) width = 70;
        else if (width > 100) width = 100;
      }

      $('#progress-bar').removeClass('bg-warning').removeClass('bg-info').removeClass('bg-success').addClass(color).css('width', width + '%');
    },

    checkComplexityPassword: function (newpass) {

      let simbols = true; // Получаем пароль из формы
      let password = newpass; // Получаем пароль из формы
      let s_letters = "qwertyuiopasdfghjklzxcvbnm"; // Буквы в нижнем регистре
      let b_letters = "QWERTYUIOPLKJHGFDSAZXCVBNM"; // Буквы в верхнем регистре
      let digits = "0123456789"; // Цифры
      let specials = "!@#$%^&*()_-+=\|/.,:;[]{}";  // Спецсимволы
      let is_s = false; // Есть ли в пароле буквы в нижнем регистре
      let is_b = false; // Есть ли в пароле буквы в верхнем регистре
      let is_d = false; // Есть ли в пароле цифры
      let is_sp = false; // Есть ли в пароле спецсимволы

      for (let i = 0; i < password.length; i++) {
        /* Проверяем каждый символ пароля на принадлежность к тому или иному типу */
        if (!is_s && s_letters.indexOf(password[i]) !== -1) is_s = true;
        else if (!is_b && b_letters.indexOf(password[i]) !== -1) is_b = true;
        else if (!is_d && digits.indexOf(password[i]) !== -1) is_d = true;
        else if (!is_sp && specials.indexOf(password[i]) !== -1) is_sp = true;
        else if (
          s_letters.indexOf(password[i]) === -1 &&
          b_letters.indexOf(password[i]) === -1 &&
          digits.indexOf(password[i]) === -1 &&
          specials.indexOf(password[i]) === -1
        ) simbols = false;
      }
      let slowly = 0;
      let rating = 0;
      let text = "";
      if (is_s) rating++; // Если в пароле есть символы в нижнем регистре, то увеличиваем рейтинг сложности
      if (is_b) rating++; // Если в пароле есть символы в верхнем регистре, то увеличиваем рейтинг сложности
      if (is_d) rating++; // Если в пароле есть цифры, то увеличиваем рейтинг сложности
      if (is_sp) rating++; // Если в пароле есть спецсимволы, то увеличиваем рейтинг сложности
      /* Далее идёт анализ длины пароля и полученного рейтинга, и на основании этого готовится текстовое описание сложности пароля */
      if (password.length < 6 && rating <= 3) {
        text = "Простой";
        slowly = 1;
      }
      else if (password.length >= 6 && rating === 1) {
        text = "Простой";
        slowly = 1;
      }
      else if (password.length <= 6 && rating > 3) {
        text = "Средний";
        slowly = 2;
      }
      else if (password.length >= 8 && rating < 3) {
        text = "Средний";
        slowly = 2;
      }
      else if (password.length >= 6 && rating > 1 && rating < 4) {
        text = "Средний";
        slowly = 2;
      }
      else if (password.length >= 8 && rating >= 3) {
        text = "Сложный";
        slowly = 3;
      }
      else if (password.length >= 6 && rating === 4) {
        text = "Сложный";
        slowly = 3;
      }

      return {
        slowly: slowly,
        rat: rating,
        password: password,
        simbols: simbols,
        text: text,
        length: password.length
      };
    }

  }

}


