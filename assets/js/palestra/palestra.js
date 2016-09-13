function fecharPalestra(id, evento, nome){
    var r = confirm("Tem certeza que deseja finalizar a palestra \""+nome+"\"?\n (Após finalizar uma palestra, não será possível modificá-la)");
    if(r){
        window.location.assign("?id="+evento+"&fecharPalestra="+id);
    }
}
