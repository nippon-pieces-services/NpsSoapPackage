<?php 

/*
 * Copyright (C) 2024 Nippon pieces services
*/

namespace NPServices\NpsSoapPackage;

class CustomerAddressResponse {
    private $societe;
    private $name1;
    private $name2;
    private $street1;
    private $street2;
    private $postalCode;
    private $city;
    private $countryIsoCode;
    private $countryName;

    public function __construct($data) {
        $this->societe = $data->societe;
        $this->name1 = $data->name1;
        $this->name2 = $data->name2;
        $this->street1 = $data->street1;
        $this->street2 = $data->street2;
        $this->postalCode = $data->postalCode;
        $this->city = $data->city;
        $this->countryIsoCode = $data->countryIsoCode;
        $this->countryName = $data->countryName;
    }

    public function getSociete() {
        return $this->societe;
    }

    public function getName1() {
        return $this->name1;
    }

    public function getName2() {
        return $this->name2;
    }

    public function getStreet1() {
        return $this->street1;
    }

    public function getStreet2() {
        return $this->street2;
    }

    public function getPostalCode() {
        return $this->postalCode;
    }

    public function getCity() {
        return $this->city;
    }

    public function getCountryIsoCode() {
        return $this->countryIsoCode;
    }

    public function getCountryName() {
        return $this->countryName;
    }

}