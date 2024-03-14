<?php 

/*
 * Copyright (C) 2024 Nippon pieces services
*/

namespace NPServices\NpsSoapPackage;

class MakerCode {
    private $makerCode;
    private $name;

    public function __construct($data) {
        $this->makerCode = $data->makerCode;
        $this->name = $data->name;
    }

    public function getMakerCode() {
        return $this->makerCode;
    }

    public function getName() {
        return $this->name;
    }

}