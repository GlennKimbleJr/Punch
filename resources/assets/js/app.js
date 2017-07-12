
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

$("a[href='#punch']").click(function (e){
    e.preventDefault();

    $(this).trigger('punch');
});

$("a[href='#punch']").on('punch', function() {
    punch();
    refreshPunchHistory();
});

function punch() {
    $.post('/clock/punch', function() {
        toggleTimeClockButton();
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
    // 
}
