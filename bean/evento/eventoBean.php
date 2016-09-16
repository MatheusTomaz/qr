<?

    class EventoBean{

        public $idEvento;
        public $nomeEvento;
        public $status;
        public $qtdPalestra;
        public $caminhoLogo;
        public $caminhoCracha1;
        public $caminhoCracha2;
        public $tipoCracha;
        public $usuarioId;

        public function getUsuarioId(){
            return $this->usuarioId;
        }

        public function setUsuarioId($usuarioId){
            $this->usuarioId = $usuarioId;
        }

        public function getId(){
            return $this->idEvento;
        }

        public function setId($idEvento){
            $this->idEvento = $idEvento;
        }

        public function getStatus(){
            return $this->status;
        }

        public function setStatus($status){
            $this->status = $status;
        }

        public function getQtdPalestra(){
            return $this->qtdPalestra;
        }

        public function setQtdPalestra($qtdPalestra){
            $this->qtdPalestra = $qtdPalestra;
        }

        public function getNome(){
            return $this->nomeEvento;
        }

        public function setNome($nomeEvento){
            $this->nomeEvento = $nomeEvento;
        }

        public function getCaminhoLogo(){
            return $this->caminhoLogo;
        }

        public function setCaminhoLogo($caminhoLogo){
            $this->caminhoLogo = $caminhoLogo;
        }

        public function getTipoCracha(){
            return $this->tipoCracha;
        }

        public function setTipoCracha($tipoCracha){
            $this->tipoCracha = $tipoCracha;
        }

        public function getCaminhoCracha1(){
            return $this->caminhoCracha1;
        }

        public function getCaminhoCracha2(){
            return $this->caminhoCracha2;
        }

        public function setCaminhoCracha($caminhoCracha1,$caminhoCracha2=" "){
            $this->caminhoCracha1 = $caminhoCracha1;
            $this->caminhoCracha2 = $caminhoCracha2;
        }
    }

?>
