<?

    class PalestraBean{

        private $idPalestra;
        private $nomePalestra;
        private $tipoPalestra;
        private $qtdParticipante;
        private $eventoId;

        public function getEventoId(){
            return $this->eventoId;
        }

        public function setEventoId($eventoId){
            $this->eventoId = $eventoId;
        }

        public function getTipoPalestra(){
            return $this->tipoPalestra;
        }

        public function setTipoPalestra($tipoPalestra){
            $this->tipoPalestra = $tipoPalestra;
        }

        public function getId(){
            return $this->idPalestra;
        }

        public function setId($idEvento){
            $this->idPalestra = $idPalestra;
        }

        public function getQtdParticipante(){
            return $this->qtdParticipante;
        }

        public function setQtdParticipante($qtdParticipante){
            $this->qtdParticipante = $qtdParticipante;
        }

        public function getNome(){
            return $this->nomePalestra;
        }

        public function setNome($nomePalestra){
            $this->nomePalestra = $nomePalestra;
        }

    }

?>
