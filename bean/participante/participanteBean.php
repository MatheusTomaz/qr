<?php

    class ParticipanteBean{

        private $id;
        private $nome;
        private $idPalestra;
        private $cpf;
        private $eventoId;

        public function getEventoId(){
            return $this->eventoId;
        }

        public function setEventoId($eventoId){
            $this->eventoId = $eventoId;
        }

        public function getPalestraId(){
            return $this->idPalestra;
        }

        public function setPalestraId($idPalestra){
            $this->idPalestra = $idPalestra;
        }

        public function getCpf(){
            return $this->cpf;
        }

        public function setCpf($cpf){
            $this->cpf = $cpf;
        }

        public function getId(){
            return $this->id;
        }

        public function setId($id){
            $this->id = $id;
        }

        public function getNome(){
            return $this->nome;
        }

        public function setNome($nome){
            $this->nome = $nome;
        }

    }

?>
