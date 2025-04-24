<?php

namespace Database;

use App\Model\EquipmentRecord;
use mysqli;

class DatabaseManager
{
    private static mysqli $equipment_conn;

    /**
     * Connect to the database.
     *
     * @param  string  $url  URL of the database to connect to.
     * @param  string  $user  Name of the user to connect to the database with
     * @param  string  $password  Name of the user's password to connect to the database with
     * @return bool Whether or not connection was successful
     */
    public static function connect(string $url, string $user, string $password): bool
    {
        $conn = mysqli_connect($url, $user, $password, 'equipment');
        if (!$conn) {
            return false;
        }

        self::$equipment_conn = $conn;

        return true;
    }

    /**
     * Request a page of equipment records from the database (100 items per page)
     *
     * @param  int  $pageNumber  The page number to request.
     * @return array<EquipmentRecord>|false An array of equipment data.
     */
    public static function selectEquipmentPage(int $pageNumber = 1, int $pageSize = 10): array|false
    {
        $sql = <<<'SQL'
            SELECT equipment.id as id, equipment_types.name as equipment_type, manufacturers.name as manufacturer, serial_number
            FROM equipment
            INNER JOIN equipment_types
            ON equipment_types.id = equipment.type_id
            INNER JOIN manufacturers
            ON manufacturers.id = equipment.manufacturer_id
            ORDER BY equipment.id
            LIMIT ?
            OFFSET ?
        SQL;

        $query = self::$equipment_conn->prepare($sql);
        if (!$query) {
            return false;
        }

        $offset = abs(($pageNumber - 1) * $pageSize);

        $query->bind_param('ii', $pageSize, $offset);
        $query->execute();

        $result = $query->get_result();
        if (!$result) {
            return false;
        }

        $acc = [];
        while ($row = $result->fetch_assoc()) {
            $acc[] = new EquipmentRecord(
                (string)$row['id'],
                (string)$row['equipment_type'],
                (string)$row['manufacturer'],
                (string)$row['serial_number']
            );
        }

        return $acc;
    }

    /**
     * Request a page of equipment records from the database (100 items per page)
     *
     * @param  string  $by  The equipment type to search by.
     * @param  string  $value  The name of the query.
     * @param  int  $pageNumber  The page number to request.
     * @param  int  $pageSize  The number of elements that are present in a page.
     * @return array<EquipmentRecord>|false An array of equipment data.
     */
    public static function selectEquipmentPageWithQuery(string $by, string $value, int $pageNumber = 1, int $pageSize = 10): array|false
    {
        $orderBy = match ($by) {
            'type' => 'equipment_type',
            'manufacturer' => 'manufacturer',
            'serial' => 'serial_number',
            default => 'equipment_type'
        };

        $sql = <<<'SQL'
            SELECT equipment.id as id, equipment_types.name as equipment_type, manufacturers.name as manufacturer, serial_number
            FROM equipment
            INNER JOIN equipment_types
            ON equipment_types.id = equipment.type_id
            INNER JOIN manufacturers
            ON manufacturers.id = equipment.manufacturer_id
            WHERE ? = ?
            ORDER BY equipment.id
            LIMIT ?
            OFFSET ?
        SQL;

        $query = self::$equipment_conn->prepare($sql);
        if (!$query) {
            return false;
        }

        $offset = abs(($pageNumber - 1) * $pageSize);

        $query->bind_param('ssii', $orderBy, $value, $pageSize, $offset);
        $query->execute();

        $result = $query->get_result();
        if (!$result) {
            return false;
        }

        $acc = [];
        while ($row = $result->fetch_assoc()) {
            $acc[] = new EquipmentRecord(
                (string)$row['id'],
                (string)$row['equipment_type'],
                (string)$row['manufacturer'],
                (string)$row['serial_number']
            );
        }

        return $acc;
    }

    /**
     * @param  string  $equipmentType  The equipment type of the new record.
     * @param  string  $manufacturer  The manufacturer of the new record.
     * @param  string  $equipmentType  The serial number of the new record.
     * @return bool Whether or not the insert was successful.
     */
    public static function insertEquipmentRecord(string $equipmentType, string $manufacturer, string $serialNumber): bool
    {
        $sql = 'INSERT INTO equipment (equipment_type, manufacturer, serial_number) VALUES (?, ?, ?)';

        $query = self::$equipment_conn->prepare($sql);
        if (!$query) {
            return false;
        }

        $query->bind_param('sss', $equipmentType, $manufacturer, $serialNumber);
        $query->execute();

        $result = $query->get_result();
        if (!$result) {
            return false;
        }

        return true;
    }

    /**
     * @param  int  $id  The id of the record to update.
     * @param  string  $equipmentType  The equipment type of the updated record.
     * @param  string  $manufacturer  The manufacturer of the updated record.
     * @param  string  $equipmentType  The serial number of the updated record.
     * @return bool Whether or not the insert was successful.
     */
    public static function updateEquipmentRecord(int $id, string $equipmentType, string $manufacturer, string $serialNumber): bool
    {
        $sql = 'UPDATE equipment SET equipment_type = ?, manufacturer = ?, serial_number = ? WHERE id = ?';

        $query = self::$equipment_conn->prepare($sql);
        if (!$query) {
            return false;
        }

        $query->bind_param('sssi', $equipmentType, $manufacturer, $serialNumber, $id);
        $query->execute();

        $result = $query->get_result();
        if (!$result) {
            return false;
        }

        return true;
    }

    /**
     * @param  int  $id  The id of the record to delete.
     * @return bool Whether or not the insert was successful.
     */
    public static function deleteEquipmentRecord(int $id): bool
    {
        $sql = 'DELETE FROM equipment WHERE id = ?';

        $query = self::$equipment_conn->prepare($sql);
        if (!$query) {
            return false;
        }

        $query->bind_param('i', $id);
        $query->execute();

        $result = $query->get_result();
        if (!$result) {
            return false;
        }

        return true;
    }

    /**
     * Get the total number of records present in the database
     * @return int|false The number of records or false if the query failed.
     */
    public static function getTotalRecordCount(): int|false
    {
        $sql = 'SELECT COUNT(*) as count FROM equipment';

        $result = self::$equipment_conn->query($sql);

        if (is_bool($result)) {
            return false;
        }

        $row = $result->fetch_row();
        if (!$row) {
            return false;
        }

        $count = $row['count'];
        if (!is_int($count)) {
            return false;
        }

        return $count;
    }
}
