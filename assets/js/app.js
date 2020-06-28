/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import '../css/app.css';

// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
// import $ from 'jquery';

console.log('Hello Webpack Encore! Edit me in assets/js/app.js');

import $ from 'jquery';

/**
 * Sample votes handler (plus / minus)
 */
let block = $('.votes-block');
block.find('a').on('click', function(e) {
    e.preventDefault();
    let link = $(e.currentTarget);
    $.ajax({
        url: 'sample/api/12/count/' + link.data('sign'),
        method: 'POST'
    }).then(function (data) {
        block.find('.votes-total').text(data.votes);
    }).catch(function (e) {
        console.log(e);
    });
});




