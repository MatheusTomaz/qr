<?

    class ClienteBean{

        private $id, $nome, $cidade, $estado, $pais, $rua, $numeroCasa, $bairro, $complemento, $telefone, $cnpj, $obs, $endereco_id;

        function setId($id){
            $this->id = $id;
        }

        function setNome($nome){
            $this->nome = $nome;
        }

        function setCidade($cidade){
            $this->cidade = $cidade;
        }

        function setEstado($estado){
            $this->estado = $estado;
        }

        function setPais($pais){
            $this->pais = $pais;
        }

        function setRua($rua){
            $this->rua = $rua;
        }

        function setNumeroCasa($numeroCasa){
            $this->numeroCasa = $numeroCasa;
        }

        function setBairro($bairro){
            $this->bairro = $bairro;
        }

        function setComplemento($complemento){
            $this->complemento = $complemento;
        }

        function setTelefone($telefone){
            $this->telefone = $telefone;
        }

        function setCnpj($cnpj){
            $this->cnpj = $cnpj;
        }

        function setObs($obs){
            $this->obs = $obs;
        }

        function setEnderecoId($endereco_id){
            $this->endereco_id = $endereco_id;
        }

        function getId(){
            return $this->id;
        }

        function getNome(){
            return $this->nome;
        }

        function getCidade(){
            return $this->cidade;
        }

        function getEstado(){
            return $this->estado;
        }

        function getPais(){
            return $this->pais;
        }

        function getRua(){
            return $this->rua;
        }

        function getNumeroCasa(){
            return $this->numeroCasa;
        }

        function getBairro(){
            return $this->bairro;
        }

        function getComplemento(){
            return $this->complemento;
        }

        function getTelefone(){
            return $this->telefone;
        }

        function getCnpj(){
            return $this->cnpj;
        }

        function getObs(){
            return $this->obs;
        }

        function getEnderecoId(){
            return $this->endereco_id;
        }

    }

?>
