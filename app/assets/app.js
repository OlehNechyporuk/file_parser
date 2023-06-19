/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/global.scss';
import './styles/style.scss';

const $ = require('jquery');
global.$ = global.jQuery = $;

let checked_db = [];
let jobs = [];

$('.select-control').on('change', function() {
    checked_db = [];
    $('input.select-control:checked').each(function (e) {
        checked_db.push($(this).val())
    });

    $('.files').html(checked_db.join(', '));

    if(checked_db.length > 0) {
        $('.controlls').show();
    } else {
        $('.controlls').hide();
    }
});

$('.load-all').on('click', function(e) {
    e.preventDefault();

    let type = $(this).data('type'),
        url  = $(this).attr('href');

    if(checked_db.length == 0) {
        alert('Choose file!');
        return;
    }

    $.ajax({
        url: url,
        method: 'post', 
        data: {
            files: checked_db.join('_'),
            type
        },
        success: function(data){
            if(data.status == 'ok') {
                let tpl = `<tr>
                
                    <td>`+data.id+`</td>
                    <td>`+data.files+`</td>
                    <td>
                        <div class="spinner-border text-warning" id="spinner-`+data.id+`" role="status">
                            <span class="sr-only"></span>
                        </div>
                        <div class="hide" id="controlls-`+data.id+`">
                            <a class="btn btn-success" href="`+data.donwload_url+`" target="_blank">Download</a>
                            <a href="`+data.delete_url+`" class="delete-btn btn btn-danger">Delete</a>
                        </div>
                    </td>
                    
                </tr>`;

                $('#result').append(tpl);

                jobs.push(data.id);

                checked_db = [];

                $('input.select-control:checked').each(function (e) {
                    $(this).prop('checked', false);
                });

                $('.controlls').hide();
            }
        }
    });
});

$('#result').on('click', '.delete-btn', function(e) {
    e.preventDefault();

    let parent = $(this).parents('tr');

    $.ajax({
        url: $(this).attr('href'),
        method: 'GET',
        success: function(data){
           parent.remove();
        }
    });
});

setInterval(check, 500);

function check() {
   if(jobs.length > 0) {
    $.ajax({
        url: $('#result').data('url'),
        method: 'POST',
        success: function(data){
            for(let key in data) {
                if(data[key]['status'] == 'done') {
                    jobs = jobs.filter(function(e) { return e !== key });
                    $('#spinner-' + key).hide();
                    $('#controlls-' + key).show();
                    
                }
            }

        }
    });
   }
}