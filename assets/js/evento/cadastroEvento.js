
function selecionaModelo(){
    $('#crachaEvento')[0].removeAttribute("required")
    $('#cabecalhoCrachaEvento')[0].removeAttribute("required")
    $('#rodapeCrachaEvento')[0].removeAttribute("required")
    if($(".modeloCracha")[0].checked == true){
        $('#crachaEvento')[0].setAttribute("required","true")
    }
    if($(".modeloCracha")[1].checked == true){
        $('#cabecalhoCrachaEvento')[0].setAttribute("required","true")
        $('#rodapeCrachaEvento')[0].setAttribute("required","true")
    }
}
