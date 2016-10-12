var checkflag = "false";
function checkPart() {
    for (var i=0;i<document.formCrachaParticipantes.elements.length;i++) {
        var x = document.formCrachaParticipantes.elements[i];
        if (x.name == 'listaTudo[]' && x.checked == true) {
            checkflag = "true";
            break;
        }
    }
    if (checkflag == "false") {
        for (var i=0;i<document.formCrachaParticipantes.elements.length;i++) {
            var x = document.formCrachaParticipantes.elements[i];
            if (x.name == 'listaTudo[]') {
                x.checked = true;
            }
        }
        checkflag = "true";
    }
    else {
        for (var i=0;i<document.formCrachaParticipantes.elements.length;i++) {
            var x = document.formCrachaParticipantes.elements[i];
            if (x.name == 'listaTudo[]') {
                x.checked = false;
            }
        }
        checkflag = "false";
    }
}

var flagCheck = "false";
function check() {
    for (var i=0;i<document.formCrachaPessoas.elements.length;i++) {
        var x = document.formCrachaPessoas.elements[i];
        if (x.name == 'listaTudo[]' && x.checked == true) {
            flagCheck = "true";
            break;
        }
    }
    if (flagCheck == "false") {
        for (var i=0;i<document.formCrachaPessoas.elements.length;i++) {
            var x = document.formCrachaPessoas.elements[i];
            if (x.name == 'listaTudo[]') {
                x.checked = true;
            }
        }
        flagCheck = "true";
    }
    else {
        for (var i=0;i<document.formCrachaPessoas.elements.length;i++) {
            var x = document.formCrachaPessoas.elements[i];
            if (x.name == 'listaTudo[]') {
                x.checked = false;
            }
        }
        flagCheck = "false";
    }
}

var selCrachaPessoas = Array();
var selCrachaParticipantes = Array();
function gerarCracha(eventoId){
    var j = 0;
    for (var i=0;i<document.formCrachaPessoas.elements.length;i++) {
        var x = document.formCrachaPessoas.elements[i];
        if (x.name == 'listaTudo[]' && x.checked == true) {
            selCrachaPessoas[j] = x.value;
            j++;
        }
    }
    var u = 0;
    for (var i=0;i<document.formCrachaParticipantes.elements.length;i++) {
        var x = document.formCrachaParticipantes.elements[i];
        if (x.name == 'listaTudo[]' && x.checked == true) {
            selCrachaParticipantes[u] = x.value;
            u++;
            j++;
        }
    }
    if(j == 0){
        alert("Selecione um ou mais participantes!");
    }else{
        var r = confirm("Gerar crachá para "+j+" inscritos?");
        if (r == true) {
            window.location.assign("?id="+eventoId+"&crachaPessoas="+selCrachaPessoas+"&crachaParticipantes="+selCrachaParticipantes);
        }
    }
}
