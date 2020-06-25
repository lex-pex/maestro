/**
 * Sample votes handler (plus / minus)
 */
var $block = $('.votes-block');
$block.find('a').on('click', function(e) {
    e.preventDefault();
    var $link = $(e.currentTarget);
    $.ajax({
        url: '/api/12/count/' + $link.data('direction'),
        method: 'post'
    }).then(function (data) {
        $block.find('.votes-total').text(data.votes);
    }).catch(function (e) {
        console.log(e);
    });
});

