/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 20);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/admin/tasks_list_dash.js":
/*!***********************************************!*\
  !*** ./resources/js/admin/tasks_list_dash.js ***!
  \***********************************************/
/*! no static exports found */
/***/ (function(module, exports) {

var controls = {
  leftArrow: '<i class="fal fa-angle-left" style="font-size: 1.25rem"></i>',
  rightArrow: '<i class="fal fa-angle-right" style="font-size: 1.25rem"></i>'
};

var runDatePicker = function runDatePicker() {
  // range picker
  $('#datepicker-0').datepicker({
    todayHighlight: true,
    language: 'ru',
    format: 'dd.mm.yyyy',
    templates: controls
  }); // range picker

  $('#datepicker-5').datepicker({
    todayHighlight: true,
    language: 'ru',
    format: 'dd.mm.yyyy',
    templates: controls
  });
  $('#datepicker-6').datepicker({
    todayHighlight: true,
    language: 'ru',
    format: 'dd.mm.yyyy',
    templates: controls
  }); // range picker

  $('#datepicker-51').datepicker({
    todayHighlight: true,
    language: 'ru',
    format: 'dd.mm.yyyy',
    templates: controls
  });
  $('#datepicker-61').datepicker({
    todayHighlight: true,
    language: 'ru',
    format: 'dd.mm.yyyy',
    templates: controls
  });
};

$('.load-stat-dash-priority').click(function () {
  var idPriority = $(this).data('priority'); //записываем в поле приоритета для POST

  $('#priority').val(idPriority); //запишем обозначаение того чтобы кликнули по диаграмке

  $('#is_dashboard').val(1); //вытаскиваем заданные даты и записываем их в поля для POST
  // но для записи даты придется вытащить все input-ы с айдишником
  // потому что у операторов две формочки с одиннаковыми id input-а.
  // это конечно же не правильно, но исправлять не стал так как
  // трудозатратно. пока костыль.

  var listDateInput = $('input[name*="date_task_start"]');

  if (listDateInput && listDateInput.length > 0) {
    listDateInput.each(function () {
      this.value = $('#date_task_start-0').val();
    });
  }

  var listDateInput = $('input[name*="date_task_end"]');

  if (listDateInput && listDateInput.length > 0) {
    listDateInput.each(function () {
      this.value = $('#date_task_end-0').val();
    });
  }

  $('#statPageTask').submit();
});
$('.load-stat-dash-complete').click(function () {
  var idComplete = $(this).data('complete'); //записываем в поле стадии работы для POST

  $('#complete').val(idComplete); //запишем обозначаение того чтобы кликнули по диаграмке

  $('#is_dashboard').val(1); //вытаскиваем заданные даты и записываем их в поля для POST
  // но для записи даты придется вытащить все input-ы с айдишником
  // потому что у операторов две формочки с одиннаковыми id input-а.
  // это конечно же не правильно, но исправлять не стал так как
  // трудозатратно. пока костыль.

  var listDateInput = $('input[name*="date_task_start"]');

  if (listDateInput && listDateInput.length > 0) {
    listDateInput.each(function () {
      this.value = $('#date_task_start-0').val();
    });
  }

  var listDateInput = $('input[name*="date_task_end"]');

  if (listDateInput && listDateInput.length > 0) {
    listDateInput.each(function () {
      this.value = $('#date_task_end-0').val();
    });
  }

  $('#statPageTask').submit();
});
var ListTasksDash = {
  init: function init() {
    $('.load-stat-all').on('click', function () {
      $('#statPage-all').submit();
    });
    $('.load-stat').on('click', function () {
      $('#statPage').submit();
    });
    $('.exel').on('click', function () {
      $('#load_exel').val(1);
      $('#statPage').submit();
    });
    $('.load-stat-task').on('click', function () {
      $('#statPageTask').submit();
    });
    $('.exel-task').on('click', function () {
      $('#load_task_exel').val(1);
      $('#statPageTask').submit();
    });
  }
};
$(document).ready(function () {
  ListTasksDash.init();
  runDatePicker();
});

/***/ }),

/***/ 20:
/*!*****************************************************!*\
  !*** multi ./resources/js/admin/tasks_list_dash.js ***!
  \*****************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! E:\xampp\htdocs\call-center\resources\js\admin\tasks_list_dash.js */"./resources/js/admin/tasks_list_dash.js");


/***/ })

/******/ });