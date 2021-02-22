/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';

// // Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
import $ from 'jquery';
import 'bootstrap';



$('.type-select').change(function (){

    if($(this).val()=="qcm"){
        $(".other-answers").show();
        $(".btn-add-answer").show();
    }else{
        $(".added-answers").html('');
        $(".other-answers").hide();
        $(".btn-add-answer").hide();
    }});


let i=1;
$('.btn-add-answer').click(function(){
    i++;
    $('.added-answers').append("<label>Other answer "+i+" :</label>" +
        "<input name='answer"+i+"' type='text' class='form-control'>"
    );
});