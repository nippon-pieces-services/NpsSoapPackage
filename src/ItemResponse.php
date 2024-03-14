<?php 

/*
 * Copyright (C) 2024 Nippon pieces services
*/

namespace NPServices\NpsSoapPackage;

class ItemResponse {
    private $confirmedQuantity;
    private $price;
    private $erpReference;
    private $description;
    private $instruction;
    private $reference;
    private $makerCode;
    private $positionNumber;
    private $requestedQuantity;
    private $isValid = true;

    public function __construct($data) {
        $this->confirmedQuantity = $data->confirmedQuantity;
        $this->price = $data->price;
        $this->erpReference = $data->erpReference;
        $this->description = $data->description;
        $this->instruction = $data->instruction;
        $this->reference = $data->reference;
        $this->makerCode = $data->makerCode;
        $this->positionNumber = $data->positionNumber;
        $this->requestedQuantity = $data->requestedQuantity;
        if($data->erpReference == "XINCONNU") $this->isValid = false;
    }

    public function getConfirmedQuantity() {
        return $this->confirmedQuantity;
    }

    public function getPrice() {
        return $this->price;
    }

    public function getErpReference() {
        return $this->erpReference;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getInstruction() {
        return $this->instruction;
    }

    public function getReference() {
        return $this->reference;
    }

    public function getMakerCode() {
        return $this->makerCode;
    }

    public function getPositionNumber() {
        return $this->positionNumber;
    }

    public function getRequestedQuantity() {
        return $this->requestedQuantity;
    }

    public function isValid() {
        return $this->isValid;
    }

}