<?php 

/*
 * Copyright (C) 2024 Nippon pieces services
*/

namespace NPServices\NpsSoapPackage;
use NPServices\NpsSoapPackage\ItemResponse;

class AvailabilityResponse {
    private $id;
    private $customerId;
    private $reference;
    private $createdAt;
    private $entries = [];

    public function __construct($data) {
        $this->id = $data->id;
        $this->customerId = $data->customerId;
        $this->reference = $data->reference;
        $this->createdAt = $data->createdAt;
        foreach ($data->entries as $entry) {
            $this->entries[] = new ItemResponse($entry);
        }
    }
    
    public function getId() {
        return $this->id;
    }
    
    public function getCustomerId() {
        return $this->customerId;
    }
    
    public function getReference() {
        return $this->reference;
    }
    
    public function getCreatedAt() {
        return $this->createdAt;
    }
    
    public function getEntries() {
        return $this->entries;
    }
}