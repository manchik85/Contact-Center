export default {

  methods: {

    specifications_unit(specific, dimansion) {

      let result = '';

      specific.forEach((element, index) => {

        let ind = '';
        let che = '';


        if( parseInt(dimansion)===1 ){
          ind = 'text';

          if( typeof(this.data.data_filter.text) !== "undefined" && this.data.data_filter.text !== null ){ // console.log(this.data.data_filter.text);
            if( this.data.data_filter.text[element.id_specific+'_'+index] === element.sp_value ){
              che = ' checked';
            }
          }

        }
        else if( parseInt(dimansion)===2 ){
          ind = 'bool';

          if( typeof(this.data.data_filter.bool) !== "undefined" && this.data.data_filter.text !== null ){
            if( this.data.data_filter.text[element.id_specific+'_'+index] === element.sp_value ){
              che = ' checked';
            }
          }

        }
        else if( parseInt(dimansion)===3 ){
          ind = 'int';

          if( typeof(this.data.data_filter.int) !== "undefined" && this.data.data_filter.text !== null ){
            if( this.data.data_filter.text[element.id_specific+'_'+index] === element.sp_value ){
              che = ' checked';
            }
          }

        }

        result += '<div class="pad_0_10"><label class="checkbox">' +
          '<input type="checkbox" name="'+ind+'['+element.id_specific+'_'+index+']" value="'+element.sp_value+'"'+che+'><i></i> ' +
          element.sp_value +
          '</label></div><div class="px_3"></div>';
      });
      return result;
    },

    specifications_unit_int(specific, id_specific) {

      let result     = '';

      let opacity_1  = ' opacity_03';
      let opacity_2  = ' opacity_03';
      let opacity_3  = ' opacity_03';
      let opacity_4  = ' opacity_03';
      let opacity_5  = ' opacity_03';

      let disabled_1 = ' disabled';
      let disabled_2 = ' disabled';
      let disabled_3 = ' disabled';
      let disabled_4 = ' disabled';
      let disabled_5 = ' disabled';

      let che_1 = '';
      let che_2 = '';
      let che_3 = '';
      let che_4 = '';
      let che_5 = '';

      specific.forEach((element, index) => {

        if( typeof(this.data.data_filter.float) !== "undefined" && this.data.data_filter.float !== null ){

          for (let key in this.data.data_filter.float) {

            if(  this.data.data_filter.float[key] === id_specific+'_0_150' ){
              che_1 = ' checked';
            }
            else if( this.data.data_filter.float[key] === id_specific+'_150_300' ){
              che_2 = ' checked';
            }
            else if( this.data.data_filter.float[key] === id_specific+'_300_450' ){
              che_3 = ' checked';
            }
            else if( this.data.data_filter.float[key] === id_specific+'_450_750' ){
              che_4 = ' checked';
            }
            else if( this.data.data_filter.float[key] === id_specific+'_750_100000000000' ){
              che_5 = ' checked';
            }
          }
        }

        if( element.sp_value < 150 ){
          opacity_1 = '';
          disabled_1 = '';
        }
        else if( element.sp_value >= 150 && element.sp_value < 300 ){
          opacity_2 = '';
          disabled_2 = '';
        }
        else if( element.sp_value >= 300 && element.sp_value < 450 ){
          opacity_3 = '';
          disabled_3 = '';
        }
        else if( element.sp_value >= 450 && element.sp_value < 750 ){
          opacity_4 = '';
          disabled_4 = '';
        }
        else if( element.sp_value >= 750 ){
          opacity_5 = '';
          disabled_5 = '';
        }
      });

      const d     = new Date();
      const index = Date.parse(d);

        result += '<div class="pad_0_10'+opacity_1+'">' +
          '<label class="checkbox"><input type="checkbox" name="float[150'+index+']" min="0"   value="'+id_specific+'_0_150"'+disabled_1+che_1+'><i></i>&nbsp; до 150</label>' +
          '</div><div class="px_3"></div>';
        result += '<div class="pad_0_10'+opacity_2+'">' +
          '<label class="checkbox"><input type="checkbox" name="float[300'+index+']" min="150" value="'+id_specific+'_150_300"'+disabled_2+che_2+'><i></i>&nbsp; 150-300</label>' +
          '</div><div class="px_3"></div>';
        result += '<div class="pad_0_10'+opacity_3+'">' +
          '<label class="checkbox"><input type="checkbox" name="float[450'+index+']" min="300" value="'+id_specific+'_300_450"'+disabled_3+che_3+'><i></i>&nbsp; 300-450</label>' +
          '</div><div class="px_3"></div>';
        result += '<div class="pad_0_10'+opacity_4+'">' +
          '<label class="checkbox"><input type="checkbox" name="float[750'+index+']" min="450" value="'+id_specific+'_450_750"'+disabled_4+che_4+'><i></i>&nbsp; 450-750</label>' +
          '</div><div class="px_3"></div>';
        result += '<div class="pad_0_10'+opacity_5+'">' +
          '<label class="checkbox"><input type="checkbox" name="float[100000000000'+index+']" min="750" value="'+id_specific+'_750_100000000000"'+disabled_5+che_5+'><i></i>&nbsp; от 750</label>' +
          '</div><div class="px_3"></div>';

      return result;
    },


  }

}

