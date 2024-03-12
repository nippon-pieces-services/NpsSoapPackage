<?php 

/*
 * Copyright (C) 2024 Nippon pieces services
*/

namespace NPServices\NpsSoapPackage;
use NPServices\NpsSoapPackage\ListOfMakerCodes;

class ListOfMakerCodes {
    private $makerCodes;

    public function __construct($data) {
        foreach ($data as $entry) {
            $this->makerCodes[] = new MakerCode($entry);
        }
    }

    public function getMakerCodes() {
        return $this->makerCodes;
    }
}