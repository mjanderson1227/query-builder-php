<?php

namespace App\Model;

class EquipmentRecord
{
    public string $id;

    public string $equipmentType;

    public string $manufacturer;

    public string $serialNumber;

    public bool $disabled;

    public function __construct(string $id, string $equipmentType, string $manufacturer, string $serialNumber, bool $disabled = false)
    {
        $this->id = $id;
        $this->equipmentType = $equipmentType;
        $this->manufacturer = $manufacturer;
        $this->serialNumber = $serialNumber;
        $this->disabled = $disabled;
    }

    /**
     * Return the Equipment record as an associative array
     * @return array<string, string>
     */
    public function asArray(): array
    {
        return [
            'equipment_type' => $this->equipmentType,
            'manufacturer' => $this->manufacturer,
            'serial_number' => $this->serialNumber,
            'disabled' => $this->disabled,
        ];
    }
}
