/* include the built version of this js file and its css file in base layout base.html.twig */
// imported css will output into a single file
import '../css/app.css';
// Install with "yarn add jquery" command
import $ from 'jquery';
require('bootstrap');

/**
 * Sample votes handler (plus / minus)
 */
let block = $('.votes-block');
block.find('a').on('click', function(e) {
    e.preventDefault();
    let link = $(e.currentTarget);
    $.ajax({
        url: 'abort/sample/api/12/count/' + link.data('sign'),
        method: 'POST'
    }).then(function (data) {
        block.find('.votes-total').text(data.votes);
    }).catch(function (e) {
        console.log(e);
    });
});

/**
 * Substitute action parameter of the Delete Form
 * @param id
 * @param route
 */
window.deleteConfirm = (id, route) => {
    document.getElementById('del_form').setAttribute('action', '/' + route + '/' + id);
    name = document.getElementById('item_name_' + id).innerHTML;
    document.getElementById('item_name').innerHTML = '\" ' + name + ' \"';
};

