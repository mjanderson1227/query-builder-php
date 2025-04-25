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
     * @param string $status The status of the query e.g. "active", "inactive", "all".
     * @param  int  $pageNumber  The page number to request.
     * @param int $pageSize The size of each page.
     *
     * @return array<EquipmentRecord>|false An array of equipment data.
     */
    public static function selectEquipmentPage(string $status = "active", int $pageNumber = 1, int $pageSize = 10): array|false
    {
        switch ($status) {
            case "active":
                $sql = <<<"SQL"
                    SELECT
                        equipment.id AS id,
                        equipment_types.name AS equipment_type,
                        manufacturers.name AS manufacturer,
                        serial_number, equipment_types.disabled AS type_disabled,
                        manufacturers.disabled AS manufacturer_disabled
                    FROM equipment
                    INNER JOIN equipment_types
                    ON equipment_types.id = equipment.type_id
                    INNER JOIN manufacturers
                    ON manufacturers.id = equipment.manufacturer_id
                    WHERE equipment_types.disabled = 0
                    AND manufacturers.disabled = 0
                    ORDER BY equipment.id
                    LIMIT ?
                    OFFSET ?
                SQL;
                break;
            case "inactive":
                $sql = <<<"SQL"
                    SELECT
                        equipment.id AS id,
                        equipment_types.name AS equipment_type,
                        manufacturers.name AS manufacturer,
                        serial_number, equipment_types.disabled AS type_disabled,
                        manufacturers.disabled AS manufacturer_disabled
                    FROM equipment
                    INNER JOIN equipment_types
                    ON equipment_types.id = equipment.type_id
                    INNER JOIN manufacturers
                    ON manufacturers.id = equipment.manufacturer_id
                    WHERE equipment_types.disabled = 1
                    OR manufacturers.disabled = 1
                    ORDER BY equipment.id
                    LIMIT ?
                    OFFSET ?
                SQL;
                break;
            case "all":
                $sql = <<<"SQL"
                    SELECT
                        equipment.id AS id,
                        equipment_types.name AS equipment_type,
                        manufacturers.name AS manufacturer,
                        serial_number, equipment_types.disabled AS type_disabled,
                        manufacturers.disabled AS manufacturer_disabled
                    FROM equipment
                    INNER JOIN equipment_types
                    ON equipment_types.id = equipment.type_id
                    INNER JOIN manufacturers
                    ON manufacturers.id = equipment.manufacturer_id
                    ORDER BY equipment.id
                    LIMIT ?
                    OFFSET ?
                SQL;
                break;
        }

        $query = self::$equipment_conn->prepare($sql);
        if (!$query) {
            error_log(__FUNCTION__ . ': Unable to prepare SQL statement.');
            return false;
        }

        $offset = abs(($pageNumber - 1) * $pageSize);

        $query->bind_param('ii', $pageSize, $offset);
        $query->execute();

        $result = $query->get_result();
        if (!$result) {
            error_log(__FUNCTION__ . ': An error occurred getting the result of the SQL query.');
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
     * @param string $status The status of the query e.g. "active", "inactive", "all".
     * @param  int  $pageNumber  The page number to request.
     * @param  int  $pageSize  The number of elements that are present in a page.
     * @return array<EquipmentRecord>|false An array of equipment data.
     */
    public static function selectEquipmentPageWithQuery(string $by, string $value, string $status = "active", int $pageNumber = 1, int $pageSize = 10): array|false
    {
        $orderBy = match ($by) {
            'type' => 'equipment_types.name',
            'manufacturer' => 'manufacturers.name',
            'serial' => 'serial_number',
            default => 'equipment_types.name'
        };

        switch ($status) {
            case "active":
                $sql = <<<"SQL"
                    SELECT
                        equipment.id AS id,
                        equipment_types.name AS equipment_type,
                        manufacturers.name AS manufacturer,
                        serial_number, equipment_types.disabled AS type_disabled,
                        manufacturers.disabled AS manufacturer_disabled
                    FROM equipment
                    INNER JOIN equipment_types
                    ON equipment_types.id = equipment.type_id
                    INNER JOIN manufacturers
                    ON manufacturers.id = equipment.manufacturer_id
                    WHERE $orderBy = ?
                    AND equipment_types.disabled = 0
                    AND manufacturers.disabled = 0
                    ORDER BY equipment.id
                    LIMIT ?
                    OFFSET ?
                SQL;
                break;
            case "inactive":
                $sql = <<<"SQL"
                    SELECT
                        equipment.id AS id,
                        equipment_types.name AS equipment_type,
                        manufacturers.name AS manufacturer,
                        serial_number, equipment_types.disabled AS type_disabled,
                        manufacturers.disabled AS manufacturer_disabled
                    FROM equipment
                    INNER JOIN equipment_types
                    ON equipment_types.id = equipment.type_id
                    INNER JOIN manufacturers
                    ON manufacturers.id = equipment.manufacturer_id
                    WHERE $orderBy = ?
                    AND (
                        equipment_types.disabled = 1
                        OR manufacturers.disabled = 1
                    )
                    ORDER BY equipment.id
                    LIMIT ?
                    OFFSET ?
                SQL;
                break;
            case "all":
                $sql = <<<"SQL"
                    SELECT
                        equipment.id AS id,
                        equipment_types.name AS equipment_type,
                        manufacturers.name AS manufacturer,
                        serial_number, equipment_types.disabled AS type_disabled,
                        manufacturers.disabled AS manufacturer_disabled
                    FROM equipment
                    INNER JOIN equipment_types
                    ON equipment_types.id = equipment.type_id
                    INNER JOIN manufacturers
                    ON manufacturers.id = equipment.manufacturer_id
                    WHERE $orderBy = ?
                    ORDER BY equipment.id
                    LIMIT ?
                    OFFSET ?
                SQL;
                break;
        }

        $query = self::$equipment_conn->prepare($sql);
        if (!$query) {
            error_log(__FUNCTION__ . ': Unable to prepare SQL statement.');
            return false;
        }

        $offset = abs(($pageNumber - 1) * $pageSize);

        $query->bind_param('sii', $value, $pageSize, $offset);
        $query->execute();

        $result = $query->get_result();
        if (!$result) {
            error_log(__FUNCTION__ . ': An error occurred getting the result of the SQL query.');
            return false;
        }

        $acc = [];
        while ($row = $result->fetch_assoc()) {
            $acc[] = new EquipmentRecord(
                (string)$row['id'],
                (string)$row['equipment_type'],
                (string)$row['manufacturer'],
                (string)$row['serial_number'],
                $row['type_disabled'] || $row['manufacturer_disabled'],
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
            error_log(__FUNCTION__ . ': Unable to prepare SQL statement.');
            return false;
        }

        $query->bind_param('sss', $equipmentType, $manufacturer, $serialNumber);
        $query->execute();

        $result = $query->get_result();
        if (!$result) {
            error_log(__FUNCTION__ . ': An error occurred getting the result of the SQL query.');
            return false;
        }

        return true;
    }

    private static function fetchManufacturerId(string $manufacturer): int|false
    {
        $sql = <<<'SQL'
            INSERT INTO manufacturers (name)
            VALUES (?)
            ON DUPLICATE KEY UPDATE id = LAST_INSERT_ID(id);
        SQL;

        $query = self::$equipment_conn->prepare($sql);
        if (!$query) {
            return false;
        }

        $query->bind_param('s', $manufacturer);
        $query->execute();

        $lastRow = $query->insert_id;
        if (!is_int($lastRow)) {
            return false;
        }

        return $lastRow;
    }

    private static function fetchEquipmentTypeId(string $equipmentType): int|false
    {
        $sql = <<<'SQL'
            INSERT INTO equipment_types (name)
            VALUES (?)
            ON DUPLICATE KEY UPDATE id = LAST_INSERT_ID(id);
        SQL;

        $query = self::$equipment_conn->prepare($sql);
        if (!$query) {
            return false;
        }

        $query->bind_param('s', $equipmentType);
        $query->execute();

        $lastRow = $query->insert_id;
        if (!is_int($lastRow)) {
            return false;
        }

        return $lastRow;
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
        $equipmentTypeId = self::fetchEquipmentTypeId($equipmentType);
        $manufacturerId = self::fetchManufacturerId($manufacturer);
        if (!$equipmentTypeId || !$manufacturerId) {
            return false;
        }

        $sql = 'UPDATE equipment SET type_id = ?, manufacturer_id = ?, serial_number = ? WHERE id = ?';

        $query = self::$equipment_conn->prepare($sql);
        if (!$query) {
            error_log(__FUNCTION__ . ': Unable to prepare SQL statement.');
            return false;
        }

        $query->bind_param('iisi', $equipmentTypeId, $manufacturerId, $serialNumber, $id);

        return $query->execute();
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
            error_log(__FUNCTION__ . ': Unable to prepare SQL statement.');
            return false;
        }

        $query->bind_param('i', $id);
        $query->execute();

        $result = $query->get_result();
        if (!$result) {
            error_log(__FUNCTION__ . ': An error occurred getting the result of the SQL query.');
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
        $sql = 'SELECT COUNT(*) AS count FROM equipment';

        $result = self::$equipment_conn->query($sql);

        if (is_bool($result)) {
            error_log(__FUNCTION__ . ': Invalid result returned from SQL query.');
            return false;
        }

        $row = $result->fetch_assoc();
        if (!$row) {
            error_log(__FUNCTION__ . ': An error occurred getting the result of the SQL query.');
            return false;
        }

        $count = $row['count'];
        if (!is_string($count)) {
            error_log(__FUNCTION__ . ': Invalid result returned from SQL query.');
            return false;
        }

        return intval($count);
    }

    /**
     * @param string $equipmentType The type of the equipment record.
     * @param string $manufacturer The manufacturer of the equipment record.
     * @param string $serialNumber The manufacturer of the equipment record.
     * @return bool Whether or not the creation was successful
     */
    public static function createEquipmentRecord(string $equipmentType, string $manufacturer, string $serialNumber)
    {
        $equipmentTypeId = self::fetchEquipmentTypeId($equipmentType);
        $manufacturerId = self::fetchManufacturerId($manufacturer);
        if (!$equipmentTypeId || !$manufacturerId) {
            return false;
        }

        $sql = 'INSERT INTO equipment (type_id, manufacturer_id, serial_number) VALUES (?, ?, ?)';

        $query = self::$equipment_conn->prepare($sql);

        $query->bind_param('sss', $equipmentTypeId, $manufacturerId, $serialNumber);

        return $query->execute();
    }

    /**
     * Get a piece of equipment by its id
     * @param int $id the id of the equipment to get.
     * @return EquipmentRecord|false
     */
    public static function getEquipmentRecord(int $id): EquipmentRecord|false
    {
        $sql = <<<'SQL'
            SELECT equipment.id as id, equipment_types.name as equipment_type, manufacturers.name as manufacturer, serial_number
            FROM equipment
            INNER JOIN equipment_types
            ON equipment_types.id = equipment.type_id
            INNER JOIN manufacturers
            ON manufacturers.id = equipment.manufacturer_id
            WHERE equipment.id = ?
            LIMIT 1
        SQL;

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

        $row = $result->fetch_assoc();
        if ($row === false || $row === null) {
            return false;
        }

        return new EquipmentRecord(
            (string)$row['id'],
            (string)$row['equipment_type'],
            (string)$row['manufacturer'],
            (string)$row['serial_number']
        );
    }

    public static function updateManufacturerStatus(string $manufacturer, bool $shouldDisable): bool
    {
        $sql = 'UPDATE manufacturers SET disabled = ? WHERE name = ?';

        $query = self::$equipment_conn->prepare($sql);

        if (!$query) {
            return false;
        }

        $shouldDisable = (int) $shouldDisable;

        $query->bind_param('is', $shouldDisable, $manufacturer);

        return $query->execute();
    }

    public static function updateEquipmentTypeStatus(string $equipmentType, bool $shouldDisable = false): bool
    {
        $sql = 'UPDATE equipment_types SET disabled = ? WHERE name = ?';

        $query = self::$equipment_conn->prepare($sql);

        if (!$query) {
            return false;
        }

        $shouldDisable = (int) $shouldDisable;

        $query->bind_param('is', $shouldDisable, $equipmentType);

        return $query->execute();
    }
}
