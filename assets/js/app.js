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

/**
 * Get and Substitute parameter of the Delete Form
 * Set name, name property and delete path (routeName) of the item
 * @param id - item id
 * @param name - name property of the item
 * @param item - item name
 */
window.deleteConfirm = function(id, name, item) {
    // Get plural route-name for Resource Controller
    var routeName = '';
    if(item[item.length - 1] === 'y') {
        item[item.length - 1] = 'i';
        routeName = item.substr(0, item.length - 1);
        routeName += 'ies';
    } else {
        routeName = item + 's';
    }
    // Capitalize the item name
    item = item.charAt(0).toUpperCase() + item.slice(1);
    // Set the form action route
    document.getElementById('del_form').setAttribute('action', '/' + routeName + '/' + id);
    // Set Title of the modal
    document.getElementById('del_modal_title').innerHTML = 'Delete ' + item;
    // Display the name of the item
    document.getElementById('item').innerHTML = item;
    // Display the name property of the item
    document.getElementById('item_name').innerHTML = '\" ' + name + ' \"';
};




