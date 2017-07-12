
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

$(function() {
    refreshPunchHistory();
});

$("a[href='#punch']").click(function (e) {
    e.preventDefault();

    $(this).trigger('punch');
});


$(document).on("click", "[id$='_button']", function(e){
    e.preventDefault();

    var parentId = $(this).parent().parent().attr('id');

    $(`.${parentId}_punch`).toggleClass('hide');
    $(this).text(
        $(this).text() === 'hide' ? 'show' : 'hide'
    );
});


$("a[href='#punch']").on('punch', punch);

function punch() {
    $.post('/clock/punch', function() {
        toggleTimeClockButton();

        refreshPunchHistory();
    });
}

function toggleTimeClockButton()
{
    $('#status').toggleClass('punched-in punched-out');

    $('#status a').text(
        $('#status a').text() === 'Clock In' ? 'Clock Out' : 'Clock In'
    );
}

function refreshPunchHistory()
{
    $.post('/clock/report', function(data) {
        loadTemplate('content', 'timesheets-template', {data: data});
    })
}

function loadTemplate(id, template, data)
{
    var source   = $("#" + template).html();
    var template = Handlebars.compile(source);
    var html    = template(data);

    $('#' + id).html(html);
}

