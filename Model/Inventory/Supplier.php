<?php

class Supplier {

    protected $supplierId;
    protected $supplierName;
    protected $contactName;
    protected $contactEmail;
    protected $contactPhone;
    protected $address;
    protected $city;
    protected $state;
    protected $postalCode;
    protected $country;
    protected $website;
    protected $rating;
    protected $lastOrderDate;
    protected $paymentTerms;
    protected $deliveryTime;
    protected $supplierNotes;

    public function __construct($supplierId, $supplierName, $contactName, $contactEmail,
            $contactPhone, $address, $city, $state, $postalCode,
            $country, $website, $rating = null, $lastOrderDate = null,
            $paymentTerms = null, $deliveryTime = null, $supplierNotes = null) {
        $this->supplierId = $supplierId;
        $this->supplierName = $supplierName;
        $this->contactName = $contactName;
        $this->contactEmail = $contactEmail;
        $this->contactPhone = $contactPhone;
        $this->address = $address;
        $this->city = $city;
        $this->state = $state;
        $this->postalCode = $postalCode;
        $this->country = $country;
        $this->website = $website;
        $this->rating = $rating;
        $this->lastOrderDate = $lastOrderDate;
        $this->paymentTerms = $paymentTerms;
        $this->deliveryTime = $deliveryTime;
        $this->supplierNotes = $supplierNotes;
    }
    
    // Getters and setters for Supplier attributes
    public function getSupplierId() {
        return $this->supplierId;
    }

    public function getSupplierName() {
        return $this->supplierName;
    }

    public function getContactName() {
        return $this->contactName;
    }

    public function getContactEmail() {
        return $this->contactEmail;
    }

    public function getContactPhone() {
        return $this->contactPhone;
    }

    public function getAddress() {
        return $this->address;
    }

    public function getCity() {
        return $this->city;
    }

    public function getState() {
        return $this->state;
    }

    public function getPostalCode() {
        return $this->postalCode;
    }

    public function getCountry() {
        return $this->country;
    }

    public function getWebsite() {
        return $this->website;
    }

    public function getRating() {
        return $this->rating;
    }

    public function getLastOrderDate() {
        return $this->lastOrderDate;
    }

    public function getPaymentTerms() {
        return $this->paymentTerms;
    }

    public function getDeliveryTime() {
        return $this->deliveryTime;
    }

    public function getSupplierNotes() {
        return $this->supplierNotes;
    }
}
