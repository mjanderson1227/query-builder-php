<?php

namespace App\Controller;

use Database\DatabaseManager;
use Framework\Request;
use Framework\Response;
use Framework\View;

class HomeController
{
    public function show(Request $request): never
    {
        /** @var string|null $by */
        $by = $request->query('by', null);
        /** @var string|null $value */
        $value = $request->query('value', null);
        /** @var string $page */
        $page = $request->query('page', '1');
        /** @var string $status */
        $status = $request->query('status', 'active');

        $pageNumber = intval($page);

        $records = $by && $value
            ? DatabaseManager::selectEquipmentPageWithQuery($by, $value, $status, $pageNumber)
            : DatabaseManager::selectEquipmentPage($status, $pageNumber);

        if ($records === false) {
            Response::send('Failed to retrieve records from the database', 500);
        }

        $totalRecords = DatabaseManager::getTotalRecordCount();
        if ($totalRecords === false) {
            Response::send('Error occurred counting the records in the database', 500);
        }

        $html = View::render('query-builder', [
            'records' => $records,
            'totalRecords' => $totalRecords,
            'pageNumber' => $pageNumber,
            'previousSearch' => [
                'by' => $by,
                'value' => $value,
                'page' => $page,
                'status' => $status,
            ],
        ]);

        Response::send($html);
    }

    public function showCreate(Request $request)
    {
        $error = $request->query('error', null);

        $html = View::render('create', [
            'error' => $error
        ]);

        Response::send($html);
    }

    public function handleCreate(Request $request)
    {
        $type = $request->input('type', null);
        $manufacturer = $request->input('manufacturer', null);
        $serial = $request->input('serial', null);

        $disableEquipment = $request->input('disableEquipment', 'false');
        $disableEquipment = filter_var($disableEquipment, FILTER_VALIDATE_BOOL);

        $disableManufacturer = $request->input('disableManufacturer', 'false');
        $disableManufacturer = filter_var($disableManufacturer, FILTER_VALIDATE_BOOL);

        if (!$type || !$manufacturer || !$serial) {
            Response::send('Unable to process request due to missing attribute', 400);
        }

        try {
            $success = DatabaseManager::createEquipmentRecord(
                strip_tags($type),
                strip_tags($manufacturer),
                strip_tags($serial)
            );

            if (!$success) {
                $msg = 'An unknown error occurred updating the record in the database';
                Response::redirect("/create?error=$msg");
            }

            $success = DatabaseManager::updateEquipmentTypeStatus($type, $disableEquipment);
            if (!$success) {
                $msg = 'Failed to update equipment type status';
                Response::redirect("/create?error=$msg");
            }

            $success = DatabaseManager::updateManufacturerStatus($manufacturer, $disableManufacturer);
            if (!$success) {
                $msg = 'Failed to update manufacturer status';
                Response::redirect("/create?error=$msg");
            }

            Response::redirect('/');
        } catch (\Throwable $th) {
            $msg = "Unable to update record: {$th->getMessage()}";
            Response::redirect("/create?error=$msg");
        }
    }

    public function showUpdate(Request $request)
    {
        $id = $request->query('id', null);
        $error = $request->query('error', null);

        if (!$id) {
            Response::redirect('/');
        }

        $id = intval($id);

        $record = DatabaseManager::getEquipmentRecord($id);
        if (!$record) {
            Response::send('Unable to find record with specified id');
        }

        $html = View::render('edit', [
            'record' => $record,
            'error' => $error
        ]);

        Response::send($html);
    }

    public function handleUpdate(Request $request)
    {
        $id = $request->input('id', null);
        $type = $request->input('type', null);
        $manufacturer = $request->input('manufacturer', null);
        $serial = $request->input('serial', null);

        $disableEquipment = $request->input('disableEquipment', 'false');
        $disableEquipment = filter_var($disableEquipment, FILTER_VALIDATE_BOOL);

        $disableManufacturer = $request->input('disableManufacturer', 'false');
        $disableManufacturer = filter_var($disableManufacturer, FILTER_VALIDATE_BOOL);

        if (!$id || !$type || !$manufacturer || !$serial) {
            Response::send('Unable to process request due to missing attribute', 400);
        }

        $id = intval($id);

        try {
            $success = DatabaseManager::updateEquipmentRecord(
                $id,
                strip_tags($type),
                strip_tags($manufacturer),
                strip_tags($serial)
            );
            if (!$success) {
                $msg = 'An unknown error occurred updating the record in the database';
                Response::redirect("/edit?id=$id&error=$msg");
            }

            $success = DatabaseManager::updateEquipmentTypeStatus($type, $disableEquipment);
            if (!$success) {
                $msg = 'Failed to update equipment type status';
                Response::redirect("/edit?id=$id&error=$msg");
            }

            $success = DatabaseManager::updateManufacturerStatus($manufacturer, $disableManufacturer);
            if (!$success) {
                $msg = 'Failed to update manufacturer status';
                Response::redirect("/edit?id=$id&error=$msg");
            }

            Response::redirect('/');
        } catch (\Throwable $th) {
            $msg = "Unable to update record: {$th->getMessage()}";
            Response::redirect("/edit?id=$id&error=$msg");
        }
    }

    public function showDelete(Request $request)
    {
        $id = $request->query('id', null);

        if (!$id) {
            Response::redirect('/');
        }

        $id = intval($id);

        $html = View::render('delete', [
            'id' => $id,
        ]);

        Response::send($html);
    }

    public function handleDelete(Request $request)
    {
        $id = $request->query('id', null);

        if (!$id) {
            Response::send('Unable to process request due to missing attribute id', 400);
        }

        $id = intval($id);

        $success = DatabaseManager::deleteEquipmentRecord($id);

        if (!$success) {
            Response::send('An error occurred updating the record in the database', 500);
        }

        Response::redirect('/');
    }
}
