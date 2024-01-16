let languages_url="/language/language/update/value"
function copyTranslation() {
    $('.lang_key').each(function(index) {
        // let _this = $(this)
        console.log($(this).text());
        console.log($('input[name=_token]').val());

        // alert('213123')
        // console.log(document.getElementById("language_code").value);    
            // var key=document.getElementsByClassName("checkboxDivPerewvod").inner;
            // console.log(key);
            // console.log();

            // $(tr).find('.value').val($(tr).find('.key').text());
            var _this = $(this)

            $.post(languages_url, {
                _token: $('input[name=_token]').val(),
                id: index,
                code: document.getElementById("language_code").value,
                status: $(this).text()
            }, function(data) {
                console.log(data);
                // alert(data)
                
                const tsestQ = document.getElementsByClassName("value");
                // _this.tsestQ.value = data;
                // console.log(tsestQ);
                // tsestQ.value=data;
                // console.log(tsestQ);
                _this.siblings('.lang_value').find('input').val(data);
                // $('.value').val(data);

            });
        });
    }

    function sort_keys(el) {
        $('#sort_keys').submit();
    }
