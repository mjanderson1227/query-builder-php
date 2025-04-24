<?php

namespace App\Controller;

use Database\DatabaseManager;
use Framework\Request;
use Framework\Response;

const MAX_PAGE_SIZE = 10000;

class QueryController
{
    /**
     * API route to create a new equipment record.
     * The new equipment record will have the parameters specified in the post.
     *
     * POST /api/equipment/create
     *
     * post params {
     *   "type": string,
     *   "manufacturer": string,
     *   "serial_number": string
     * }
     *
     * @param Request $request A reference to the request object.
     */
    public function create(Request $request): never
    {
        /** @var string|null $type */
        $type = $request->input('type', null);
        /** @var string|null $manufacturer */
        $manufacturer = $request->input('manufacturer', null);
        /** @var string|null $serialNumber */
        $serialNumber = $request->input('serial_number', null);

        if (! $type || ! $manufacturer || ! $serialNumber) {
            Response::send('Request did not provide required parameter', 400);
        }

        $success = DatabaseManager::insertEquipmentRecord($type, $manufacturer, $serialNumber);

        if (! $success) {
            Response::send('Unable to create item in database', 500);
        }

        Response::send('', 201);
    }

    /**
     * API route to show the equipment records.
     * page size affects how much data will be returned.
     * page number will affect what page of data is returned.
     *
     * GET /api/equipment/show?page_size=1&page_number=10
     *
     * returns a json payload in the format:
     * {
     *   ...,
     *   "id": {"equipment_type": "laptop", "manufacturer": "Samsung", "serial_number": "512323130501dsfsfe"},
     *   ...
     * }
     * @param Request $request A reference to the request object.
     */
    public function show(Request $request): never
    {
        /** @var string $pageSize */
        $pageSize = $request->query('page_size', '10');
        $pageSize = intval($pageSize);

        /** @var string $pageNumber */
        $pageNumber = $request->query('page_number', '1');
        $pageNumber = intval($pageNumber);

        if ($pageSize > MAX_PAGE_SIZE) {
            Response::send('Page size is too large', 400);
        }

        $res = [];
        $results = DatabaseManager::selectEquipmentPage($pageNumber, $pageSize);

        if (! $results) {
            Response::send('Error occurred fetching page from the database.', 500);
        }

        foreach ($results as $equipmentRecord) {
            $res["$equipmentRecord->id"] = $equipmentRecord->asArray();
        }

        Response::json($res);
    }

    /**
     * API route to update an equipment record.
     * Will update the item specified by "id".
     *
     * POST /api/equipment/update
     *
     * post params {
     *   "id": string,
     *   "type": string,
     *   "manufacturer": string,
     *   "serial_number": string
     * }
     *
     * @param Request $request A reference to the request object.
     */
    public function update(Request $request): never
    {
        /** @var string|null $id */
        $id = $request->input('id', null);
        $id = intval($id);

        /** @var string|null $type */
        $type = $request->input('type', null);

        /** @var string|null $manufacturer */
        $manufacturer = $request->input('manufacturer', null);

        /** @var string|null $serialNumber */
        $serialNumber = $request->input('serial_number', null);

        if (! $type || ! $manufacturer || ! $serialNumber || ! $id) {
            Response::send('Request did not provide required parameter', 400);
        }

        $success = DatabaseManager::updateEquipmentRecord($id, $type, $manufacturer, $serialNumber);

        if (! $success) {
            Response::send('Unable to update equipment record with the specified id', 500);
        }

        Response::send('', 200);
    }

    /**
     * API route to delete an equipment record.
     * Will delete the item specified by "id".
     *
     * POST /api/equipment/delete
     *
     * post params {
     *   "id": string
     * }
     *
     * @param Request $request A reference to the request object.
     */
    public function delete(Request $request): never
    {
        /** @var string|null $id */
        $id = $request->input('id', null);
        $id = intval($id);

        if (! $id) {
            Response::send('Invalid parameters', 400);
        }

        $success = DatabaseManager::deleteEquipmentRecord($id);

        if (!$success) {
            Response::send('Error occurred deleting the record from the database', 500);
        }

        Response::send('', 200);
    }
}
