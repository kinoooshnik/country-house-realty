$(document).on('click', '.dropdown-item-text', function (e) {
    e.stopPropagation();
});

$(document).on('keyup change paste', '.price', function () {
    var input = $(this).val().replace(/[\D\s\._\-]+/g, "");
    input = input ? parseInt(input, 10) : 0;
    input = input > 10000000000 ? 10000000000 : input;
    $(this).val(function () {
        return (input === 0) ? "" : input.toLocaleString('ru-RU');
    });
});

$(function () {
    $('#additFiltButt').click(function () {
        $('#additionalFilters').slideToggle();
    });
});