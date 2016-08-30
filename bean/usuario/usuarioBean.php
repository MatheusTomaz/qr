                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <?

    class UsuarioBean{

        private $login, $senha, $grupo, $id;

        function setId($id){
            $this->id = $id;
        }

        function setLogin($login){
            $this->login = $login;
        }

        function setSenha($senha){
            $this->senha = $senha;
        }

        function setGrupo($grupo){
            $this->grupo = $grupo;
        }

        function setNomeCliente($nomeCliente){
            $this->nomeCliente = $nomeCliente;
        }

        function setCnpjCliente($cnpjCliente){
            $this->cnpjCliente = $cnpjCliente;
        }

        function getId(){
            return $this->id;
        }

        function getLogin(){
            return $this->login;
        }

        function getSenha(){
            return $this->senha;
        }

        function getGrupo(){
            return $this->grupo;
        }

        function getNomeCliente(){
            return $this->nomeCliente;
        }

        function getCnpjCliente(){
            return $this->cnpjCliente;
        }

    }

?>
