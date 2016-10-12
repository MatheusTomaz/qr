function selecionaModelo(){
    $('#cabecalhoCrachaEvento')[0].removeAttribute("required")
    $('#rodapeCrachaEvento')[0].removeAttribute("required")
    if($(".modeloCracha")[0].checked == true){
        $('#cabecalhoCrachaEvento')[0].setAttribute("required","true")
        $('#rodapeCrachaEvento')[0].setAttribute("required","true")
    }
}

function readRodape(input) {

    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#previaRodape').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}

$("#rodapeCrachaEvento").change(function(){
    readRodape(this);
    $("#previaRodape").css("display","block")
});

function readCabecalho(input) {

    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#previaCabecalho').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}

$("#cabecalhoCrachaEvento").change(function(){
    readCabecalho(this);
    $("#previaCabecalho").css("display","block")
});

var largura = $(".thumbnail").width()
$("#previaCabecalho").css("width",largura)
$("#previaRodape").css("width",largura)
