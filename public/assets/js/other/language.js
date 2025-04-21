let languages_url="/language/language/update/value"
function copyTranslation() {
    $('.lang_key').each(function(index) {
        var _this = $(this); // "this" ni saqlaymiz
        var currentStatus = _this.text();

        setTimeout(function() {
            $.post(languages_url, {
                _token: $('input[name=_token]').val(),
                id: index,
                code: document.getElementById("language_code").value,
                status: currentStatus
            }, function(data) {
                console.log(data);
                _this.siblings('.lang_value').find('input').val(data);

            });
        }, 444 * index);
    });
}

function sort_keys(el) {
    // formni submit qilishni oldini olish
    el.preventDefault();
    $('#sort_keys').submit();
}
