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

        $pageNumber = intval($page);

        $records = $by !== null && $value !== null
            ? DatabaseManager::selectEquipmentPageWithQuery($by, $value, $pageNumber)
            : DatabaseManager::selectEquipmentPage($pageNumber);

        $totalRecords = DatabaseManager::getTotalRecordCount();
        if ($totalRecords === false) {
            Response::send('Error occurred counting the records in the database', 500);
        }

        $html = View::render('query-builder', [
            'records' => $records,
            'totalRecords' => $totalRecords
        ]);

        Response::send($html);
    }
}
