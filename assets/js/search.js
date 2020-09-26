import './jquery.instantSearch.js';

$(function () {
    window.alert = function () {
    };
    $('.search-field').instantSearch({
        delay: 100,
    });
});
