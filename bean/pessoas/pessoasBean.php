<?php

    class PessoasBean{

        private $idPessoa;
        private $nomePessoa;
        private $grupoPessoa;
        private $cpfPessoa;
        private $eventoId;

        public function getEventoId(){
            return $this->eventoId;
        }

        public function setEventoId($eventoId){
            $this->eventoId = $eventoId;
        }

        public function getGrupoPessoa(){
            return $this->grupoPessoa;
        }

        public function setGrupoPessoa($grupoPessoa){
            $this->grupoPessoa = $grupoPessoa;
        }

         public function getCpfPessoa(){
            return $this->cpfPessoa;
        }

        public function setCpfPessoa($cpfPessoa){
            $this->cpfPessoa = $cpfPessoa;
        }

        public function getId(){
            return $this->idPessoa;
        }

        public function setId($idPessoa){
            $this->idPessoa = $idPessoa;
        }

        public function getNome(){
            return $this->nomePessoa;
        }

        public function setNome($nomePessoa){
            $this->nomePessoa = $nomePessoa;
        }

    }

?>
