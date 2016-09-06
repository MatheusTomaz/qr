function excluir(matricula, nome) {
    var r = confirm("Tem certeza que deseja excluir "+nome+"?");
    if (r == true) {
        window.location.assign("?excluir="+matricula);;
    }
}
