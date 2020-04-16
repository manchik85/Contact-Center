
import $ from 'jquery';
import axios from 'axios';

const Operator = {
    init () {

        $('#operator-aut').on('click', function () {
            const apiUrl = 'api/operator_aut';
            const dataObj = {
                users_id: $(this).attr('user-id'),
                out_desc: $('#justification_out').val(),
            };



            if(dataObj.out_desc !==''){

              console.log(dataObj.out_desc);

              axios.post(apiUrl, dataObj).then((d) => {

                document.location.href='/aut_page';

              }).catch( (error) => {
              });

              //document.location.href='/aut_page';
            }

        });
    },


};

$(document).ready(function () {
    Operator.init();
});

