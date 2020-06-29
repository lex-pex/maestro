/*
 * include the built version of this js file
 * and its css file in base layout base.html.twig
 */

// imported css will output into a single file
import '../css/app.css';

// Installed with "yarn add jquery"
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




