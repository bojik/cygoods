import $ from 'jquery';
import submit from '../form/submit';

$(document).ready(function(){
    submit($('[name="sign_up_form"]'), function(data) {
        console.log(data);
    });
});