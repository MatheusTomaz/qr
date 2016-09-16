function fecharPalestra(id, evento, nome){
    var r = confirm("Tem certeza que deseja finalizar a palestra \""+nome+"\"?\n (Após finalizar uma palestra, não será possível modificá-la)");
    if(r){
        window.location.assign("?id="+evento+"&fecharPalestra="+id);
    }
}

function excluir(id, eventoId, nome) {
    var r = confirm("Tem certeza que deseja excluir "+nome+"?");
    if (r == true) {
        window.location.assign("?id="+eventoId+"&excluir="+id);
    }
}
