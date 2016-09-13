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
