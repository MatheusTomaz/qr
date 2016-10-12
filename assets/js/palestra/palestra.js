function fecharPalestra(id, evento, nome){
    var r = confirm("Tem certeza que deseja finalizar a palestra \""+nome+"\"?\n (Após finalizar uma palestra, não será possível modificá-la)");
    if(r){
        window.location.assign("?id="+evento+"&fecharPalestra="+id);
    }
}

function excluir(id, eventoId, nome) {
    var r = confirm("Tem certeza que deseja excluir "+nome+"?");
    if (r == true) {
        window.location.assign("?id="+eventoId+"&excluirPalestra="+id);
    }
}

function gerarRelatorio(id, eventoId, nome) {
    var r = confirm("Gerar relatório para "+nome+"?");
    if (r == true) {
        window.location.assign("?gerarRelatorio="+id+"&id="+eventoId);
    }
}

function notify(){
    console.log( "ready!" );
    window.webkitNotifications.checkPermission();
    if (havePermission == 0) {
        // 0 is PERMISSION_ALLOWED
        var notification = window.webkitNotifications.createNotification(
            'http://i.stack.imgur.com/dmHl0.png',
            'Chrome notification!',
            'Here is the notification text'
        );

        notification.onclick = function () {
            window.open("http://stackoverflow.com/a/13328397/1269037");
            notification.close();
        }
        notification.show();
    } else {
        window.webkitNotifications.requestPermission();
    }
}
