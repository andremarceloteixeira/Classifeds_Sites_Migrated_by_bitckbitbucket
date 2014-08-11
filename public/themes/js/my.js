$.extend({
    getUrlVars: function(){
        var vars = [], hash;
        var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
        for(var i = 0; i < hashes.length; i++)
        {
            hash = hashes[i].split('=');
            vars.push(hash[0]);
            vars[hash[0]] = hash[1];
        }
        return vars;
    },
    getUrlVar: function(name){
        return $.getUrlVars()[name];
    }
});
$(document).ready(function () {
    var elements = document.getElementsByTagName("textarea");
    for (var i = 0; i < elements.length; i++) {
        elements[i].oninvalid = function (e) {
            e.target.setCustomValidity("");
            if (!e.target.validity.valid) {
                switch (e.srcElement.name) {
                    case "decricao":
                        e.target.setCustomValidity("Preencha o seu anuncio");
                        break;
                    case "titulo":
                        e.target.setCustomValidity("Preencha o titulo");
                        break;
                }
            }
        };
        elements[i].oninput = function (e) {
            e.target.setCustomValidity("");
        };
    }
    $('.categorias').find('option').first().val('');
    $('.cidades').find('option').first().val('');
    if( $('#fkCategoria').length > 0) {
        $('.categorias').val($('#fkCategoria').val());
    }
    if( $('#fkCidade').length > 0) {
        $('.cidades').val( $('#fkCidade').val());
    }
    $('form').submit(function(){
        // On submit disable its submit button
        $('input[type=submit]', this).attr('disabled', 'disabled');
    });
    if($.getUrlVar('searchSelect')) {
       $('.categories').val($.getUrlVar('searchSelect'));
    }
});