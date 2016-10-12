function excluir(id, eventoId, nome) {
    var r = confirm("Tem certeza que deseja excluir "+nome+"?");
    if (r == true) {
        window.location.assign("?id="+eventoId+"&excluir="+id);
    }
}

var checkflag = "false";
function check() {
    for (var i=0;i<document.cadastro.elements.length;i++) {
        var x = document.cadastro.elements[i];
        if (x.name == 'palestrasParticipante[]' && x.checked == true) {
            checkflag = "true";
            break;
        }
    }
    if (checkflag == "false") {
        for (var i=0;i<document.cadastro.elements.length;i++) {
            var x = document.cadastro.elements[i];
            if (x.name == 'palestrasParticipante[]') {
                x.checked = true;
            }
        }
        checkflag = "true";
    }
    else {
        for (var i=0;i<document.cadastro.elements.length;i++) {
            var x = document.cadastro.elements[i];
            if (x.name == 'palestrasParticipante[]') {
                x.checked = false;
            }
        }
        checkflag = "false";
    }
}

var checkflagPart = "false";
function checkPart() {
    for (var i=0;i<document.formParticipantes.elements.length;i++) {
        var x = document.formParticipantes.elements[i];
        if (x.name == 'listaParticipante[]' && x.checked == true) {
            checkflagPart = "true";
            break;
        }
    }
    if (checkflagPart == "false") {
        for (var i=0;i<document.formParticipantes.elements.length;i++) {
            var x = document.formParticipantes.elements[i];
            if (x.name == 'listaParticipante[]') {
                x.checked = true;
            }
        }
        checkflagPart = "true";
    }
    else {
        for (var i=0;i<document.formParticipantes.elements.length;i++) {
            var x = document.formParticipantes.elements[i];
            if (x.name == 'listaParticipante[]') {
                x.checked = false;
            }
        }
        checkflagPart = "false";
    }
}

var selExcluir = Array();
function excluirAll(eventoId){
    var j = 0;
    for (var i=0;i<document.formParticipantes.elements.length;i++) {
        var x = document.formParticipantes.elements[i];
        if (x.name == 'listaParticipante[]' && x.checked == true) {
            selExcluir[j] = x.value;
            j++;
        }
    }
    if(j == 0){
        alert("Selecione um ou mais participantes!");
    }else{
        var r = confirm("Tem certeza que deseja excluir "+j+" participantes?");
        if (r == true) {
            window.location.assign("?id="+eventoId+"&excluirAll="+selExcluir);;
        }
    }
}

function getContent( timestamp )
{
    var queryString = { 'timestamp' : timestamp };
    $.get ( '/sisqrcode/server.php' , queryString , function ( data )
    // $.get ( 'http://192.168.1.107:8081/GLCMonitor/getUsuario.jsp' , queryString , function ( data )
    {
        var obj = jQuery.parseJSON( data );
        for (var k in obj)
        {
            notify(obj[k]);
            atualizarParticipante(obj[k]);
            console.log(obj[k].nome);
            $( '#response' ).append( obj[k].nome );
        }

        // reconecta ao receber uma resposta do servidor
        getContent( timestamp );
    });
}

$( document ).ready( function ()
{
    getContent();
});

function atualizarParticipante(obj){
    if(obj.presenca == 1){
        var element = $("#pt"+obj.id);
        element.removeClass("label-danger");
        element.addClass("label-success");
        element.html("Presente");
    }else{
        var element = $("#pt"+obj.id);
        element.removeClass("label-success");
        element.addClass("label-danger");
        element.html("Ausente");
    }
}

function presenca(id,evento,palestra){
    window.location.assign("?id="+evento+"&palestra="+palestra+"&participante="+id);
}

function notify(obj) {
  if(!window.Notification) {
    console.log('Este browser não suporta Web Notifications!');
    return;
  }

  if (Notification.permission === 'default') {
    Notification.requestPermission(function() {
      console.log('Usuário não falou se quer ou não notificações. Logo, o requestPermission pede a permissão pra ele.');
    });
  } else if (Notification.permission === 'granted') {
    console.log('Usuário deu permissão');

    if(obj.presenca == 1){
        var notification = new Notification('Cadastro de Evento', {
         body: 'Participante '+obj.nome+' presente!',
         tag: 'string única que previne notificações duplicadas',
        });
    }else{
        var notification = new Notification('Cadastro de Evento', {
         body: 'Participante '+obj.nome+' ausente!',
         tag: 'string única que previne notificações duplicadas',
        });
    }
    notification.onshow = function() {
     console.log('onshow: evento quando a notificação é exibida')
    },
    notification.onclick = function() {
     console.log('onclick: evento quando a notificação é clicada')
    },
    notification.onclose = function() {
     console.log('onclose: evento quando a notificação é fechada')
    },
    notification.onerror = function() {
     console.log('onerror: evento quando a notificação não pode ser exibida. É disparado quando a permissão é defualt ou denied')
    }

  } else if (Notification.permission === 'denied') {
    console.log('Usuário não deu permissão');
  }

};
