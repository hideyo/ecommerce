/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
require('../../../node_modules/bootbox/bootbox.js');
require('../../../node_modules/datatables/media/js/jquery.dataTables.js');
require('../../../node_modules/select2/dist/js/select2.js');

require('../../../node_modules/jstree/dist/jstree.min.js');
require('../../../node_modules/bootstrap-datepicker/js/bootstrap-datepicker.js');
require('../../../node_modules/bootstrap-validator/dist/validator.js');
require('../../../node_modules/summernote/dist/summernote.js');
require('../../../node_modules/datatables-bootstrap3-plugin/media/js/datatables-bootstrap3.js');


function recalculate(el){

    var si = $('.tax-rate :selected').text();
    var tax = parseFloat(si);

    el.value = el.value.replace(",",".");
    var percentage  = 1 + (tax / 100);

    switch (el.name) {
        case 'price_inc_tax':
            if(el.value) {
                var result = parseFloat(el.value) / percentage;
                result = Math.round(result*1000)/1000;
                $('.price').val(result.toFixed(4));
            } else {
                $('.price').val(0);
            }
            break;
        default:
        case 'price':
            if(el.value) {
                var result = parseFloat(el.value) * percentage;
                result = Math.round(result*100)/100;
                $('.price_inc_tax').val(result.toFixed(2));
            } else {
                $('.price_inc_tax').val(0);
            }
            break;
    }
}

$(document).ready(function() {
    
    var bootbox = require('bootbox');

    $("body").on("submit", ".delete-button", function(e){
        event.preventDefault();
        var $this = $(this);       

        bootbox.confirm({
            message: "Delete item: " + $this.attr('data-title') + "?",
            buttons: {
                confirm: {
                    label: 'Yes',
                    className: 'btn-success'
                },
                cancel: {
                    label: 'No',
                    className: 'btn-danger'
                }
            },
            callback: function (result) {
                if(result) {
                    $this.submit();
                }  
            }
        });
    });


    $("body").on("change", ".print-input-select, .print-input-date", function(e){
       $('body .print-form').submit();
    });

    $("body").on("submit", ".print-form", function(e){
        var $this = $(this);
        e.preventDefault();
        var url = $(this).attr('data-url');
        $.post($this.attr('action'), $this.serialize(), function (data) {


            $.ajax({
                url: url,
                type: 'get',
                dataType: 'html',
                success: function (data) {
                    $('.selected-orders').html(data);
              
                }

            });
        });
    });


    if($( ".load-order-status-template" )) {
        var template = $( ".load-order-status-template" );
        var url = template.attr('data-url');

        if(url) {
            var orderStatusEmailTemplateId = $( ".order_status_email_template_id" );

            $.get(url + orderStatusEmailTemplateId.val(), {
          
            }, function(data){
                template.html(data.content);
            }, 'json');

        }

    }

    $("body").on("change", ".order_status_email_template_id", function(e){
        var template = $( ".load-order-status-template" );
        var url = template.attr('data-url');

        if(url) {
            var orderStatusEmailTemplateId = $( ".order_status_email_template_id" );



        $.get(url + orderStatusEmailTemplateId.val(), {
          
        }, function(data){
            template.html(data.content);
        }, 'json');

        }

    });

    $("body").on("click", ".change-active", function(e){
     
        var $this = $(this);
        var url = $(this).attr('data-url');


        $.get(url, {
          
        }, function(data){
        $('#datatable').DataTable().ajax.reload();
        }, 'html');

    });


    $("body").on("change", ".change-amount", function(e){
     
        var $this = $(this);

        if ($(this).val() === '') {
          return false;
        }

        var amount = $(this).val();

        var url = $(this).attr('data-url') + '/' + amount;
        $.get(url, {
          
        }, function(data){
        $('#datatable').DataTable().ajax.reload();
        }, 'html');

    });


    $("body").on("change", ".change-rank", function(e){
     
        var $this = $(this);

        if ($(this).val() === '') {
          return false;
        }

        var amount = $(this).val();

        var url = $(this).attr('data-url') + '/' + amount;
        $.get(url, {
          
        }, function(data){
        $('#datatable').DataTable().ajax.reload(null, false);
        }, 'html');

    });



    $("body").on("change", ".change-amount-product-attribute", function(e){
     
        var $this = $(this);

        if ($(this).val() === '') {
          return false;
        }

        var amount = $(this).val();

        var url = $(this).attr('data-url') + '/' + amount;
        $.get(url, {
          
        }, function(data){
        $('#datatable').DataTable().ajax.reload();
        }, 'html');

    });


    if(document.getElementById('codeeditor')){
        var editords = CodeMirror.fromTextArea(document.getElementById('codeeditor'), {
        lineNumbers: true
        });
    }


    $('.summernote').summernote({
      height: 300,                 // set editor height
      minHeight: null,             // set minimum height of editor
      maxHeight: null,             // set maximum height of editor
      focus: true                // set focus to editable area after initializing summernote
    });

    $('.price, .price_inc_tax, .tax-rate').keyup(function() {
        recalculate(this);
    });

    $('.price').trigger('keyup');

    $(".select2").select2();

    $('.datepicker').datepicker({

        format: 'dd/mm/yyyy'

    });


    
});

