<?php

use App\Model\EquipmentRecord;

/** @var array<EquipmentRecord>|null $records */
/** @var int|null $totalRecords */
/** @var int|null $pageNumber */
/** @var array{by: ?string, value: ?string, page: ?string, status: ?string} $previousSearch */

if (!isset($records) || !isset($totalRecords) || !isset($pageNumber) || !isset($previousSearch)) {
  throw new \InvalidArgumentException('All template variables must be provided in HomeController()');
}

$currentChunk = (int)floor($pageNumber / 5) * 5;
if ($currentChunk < 5) {
  $currentChunk++;
}

$buttonSelectedClass = '-10 flex items-center justify-center px-4 h-10 leading-tight text-blue-600 border border-blue-300 bg-blue-50 hover:bg-blue-100 hover:text-blue-700 dark:border-gray-700 dark:bg-gray-700 dark:text-white';
$buttonUnselectedClass = 'flex items-center justify-center px-4 h-10 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white';
?>

<div class="lg:px-28 px-2 pt-20">
  <h2 class="text-4xl font-extrabold dark:text-white text-blue-600">Equipment Management Platform</h2>
  <p class="text-gray-400">Manage equipment records here</p>
  <form id="search-form" class="space-y-6 mb-3">
    <input name="page" type="hidden" class="hidden" value="<?= $previousSearch['page'] ?>" />
    <div class="flex">
    </div>

    <div class="flex justify-between items-center gap-4">
      <div class="flex">
        <!-- Dropdown Menu Toggle -->
        <button id="dropdownHelperRadioButton" data-dropdown-toggle="dropdownHelperRadio" class="shrink-0 z-10 inline-flex items-center py-2.5 px-4 text-sm font-medium text-center text-gray-900 bg-gray-100 border border-gray-300 rounded-s-lg hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 dark:focus:ring-gray-700 dark:text-white dark:border-gray-600" type="button">Search By <svg class="w-2.5 h-2.5 ms-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4" />
          </svg></button>


        <!-- Dropdown menu -->
        <div id="dropdownHelperRadio" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow-sm w-60 dark:bg-gray-700 dark:divide-gray-600" data-popper-reference-hidden="" data-popper-escaped="" data-popper-placement="top" style="position: absolute; inset: auto auto 0px 0px; margin: 0px; transform: translate3d(522.5px, 6119.5px, 0px);">
          <ul class="p-3 space-y-1 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownHelperRadioButton">
            <li>
              <div class="flex p-2 rounded-sm hover:bg-gray-100 dark:hover:bg-gray-600">
                <div class="flex items-center h-5">
                  <input id="equipment-type-radio" name="by" type="radio" value="type" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500" <?= $previousSearch['by'] === 'type' ? 'checked' : '' ?>>
                </div>
                <div class="ms-2 text-sm">
                  <label for="equipment-type-radio" class="font-medium text-gray-900 dark:text-gray-300">
                    <div>Equipment Type</div>
                    <p id="equipment-type-radio-text" class="text-xs font-normal text-gray-500 dark:text-gray-300">Search By Equipment Type</p>
                  </label>
                </div>
              </div>
            </li>
            <li>
              <div class="flex p-2 rounded-sm hover:bg-gray-100 dark:hover:bg-gray-600">
                <div class="flex items-center h-5">
                  <input id="manufacturer-radio" name="by" type="radio" value="manufacturer" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500" <?= $previousSearch['by'] === 'manufacturer' ? 'checked' : '' ?>>
                </div>
                <div class="ms-2 text-sm">
                  <label for="manufacturer-radio" class="font-medium text-gray-900 dark:text-gray-300">
                    <div>Manufacturer</div>
                    <p id="manufacturer-radio-text" class="text-xs font-normal text-gray-500 dark:text-gray-300">Search By Manufacturer</p>
                  </label>
                </div>
              </div>
            </li>
            <li>
              <div class="flex p-2 rounded-sm hover:bg-gray-100 dark:hover:bg-gray-600">
                <div class="flex items-center h-5">
                  <input id="serial-number-radio" name="by" type="radio" value="serial" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500" <?= $previousSearch['by'] === 'serial' ? 'checked' : '' ?>>
                </div>
                <div class="ms-2 text-sm">
                  <label for="serial-number-radio" class="font-medium text-gray-900 dark:text-gray-300">
                    <div>Serial Number</div>
                    <p id="serial-number-radio-text" class="text-xs font-normal text-gray-500 dark:text-gray-300">Search By Serial Number</p>
                  </label>
                </div>
              </div>
            </li>
          </ul>
        </div>

        <!-- Search Bar -->
        <div class="relative w-96 flex-1">
          <input type="search" name="value" value="<?= $previousSearch['value'] ?? '' ?>" id="search-dropdown" class="block p-2.5 w-full z-20 text-sm text-gray-900 bg-gray-50 rounded-e-lg border-s-gray-50 border-s-2 border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-s-gray-700  dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:border-blue-500" placeholder="Name of the item you want to find..." />
          <button type="submit" class="absolute top-0 end-0 p-2.5 text-sm font-medium h-full text-white bg-blue-700 rounded-e-lg border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
            </svg>
            <span class="sr-only">Search</span>
          </button>
        </div>
      </div>

      <!-- Filter by active/inactive/all -->
      <ul class="items-center w-2/3 text-sm font-medium text-gray-900 bg-white rounded-lg sm:flex dark:bg-gray-700 dark:text-white">
        <li class="w-full border-b border-gray-200 sm:border-b-0 sm:border-r dark:border-gray-600">
          <div class="flex items-center ps-3">
            <input id="horizontal-list-radio-active" type="radio" value="active" name="status" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500" <?= $previousSearch['status'] === 'active' ? 'checked' : '' ?>>
            <label for="horizontal-list-radio-active" class="w-full py-3 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Active</label>
          </div>
        </li>
        <li class="w-full border-b border-gray-200 sm:border-b-0 sm:border-r dark:border-gray-600">
          <div class="flex items-center ps-3">
            <input id="horizontal-list-radio-inactive" type="radio" value="inactive" name="status" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500" <?= $previousSearch['status'] === 'inactive' ? 'checked' : '' ?>>
            <label for="horizontal-list-radio-inactive" class="w-full py-3 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Inactive</label>
          </div>
        </li>
        <li class="w-full border-b border-gray-200 sm:border-b-0 dark:border-gray-600">
          <div class="flex items-center ps-3">
            <input id="horizontal-list-radio-all" type="radio" value="all" name="status" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500" <?= $previousSearch['status'] === 'all' ? 'checked' : '' ?>>
            <label for="horizontal-list-radio-all" class="w-full py-3 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">All</label>
          </div>
        </li>
      </ul>

      <!-- Reset Button -->
      <a id="reset-button" href="/" class="text-white w-24 block bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-sm text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Reset</a>

      <!-- Pagination -->
      <nav aria-label="page-numbers" id="page-numbers">
        <ul class="inline-flex -space-x-px text-base h-10">
          <li>
            <button type="submit" name="page" value="<?= $pageNumber - 1 > 0 ? $pageNumber - 1 : $pageNumber ?>" id="previous-button" class="flex items-center justify-center px-4 h-10 ms-0 leading-tight text-gray-500 bg-white border border-e-0 border-gray-300 rounded-s-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">Previous</button>
          </li>
          <?php for ($i = $currentChunk; $i < $currentChunk + 5; $i++): ?>
            <li>
              <button type="submit" name="page" value="<?= $i ?>" <?= $i === $pageNumber ? 'aria-current="page"' : '' ?> class="<?= $i === $pageNumber ? $buttonSelectedClass : $buttonUnselectedClass ?>">
                <?= $i ?>
              </button>
            </li>
          <?php endfor ?>
          <li>
            <button type="submit" name="page" value="<?= $pageNumber + 1 < $totalRecords ? $pageNumber + 1 : 1 ?>" id="next-button" class="flex items-center justify-center px-4 h-10 leading-tight text-gray-500 bg-white border border-gray-300 rounded-e-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">Next</button>
          </li>
        </ul>
      </nav>

      <!-- Add Button -->
      <a id="add-button" href="/create" class="text-white w-24 block bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-sm text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">+</a>
    </div>
  </form>

  <!-- Table of Equipment Data -->
  <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <table class="w-full text-sm text-left rtl:text-right rounded-lg text-gray-500 dark:text-gray-400">
      <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
        <tr>
          <th scope="col" class="px-6 py-3">
            ID
          </th>
          <th scope="col" class="px-6 py-3">
            Equipment Type
          </th>
          <th scope="col" class="px-6 py-3">
            Manufacturer
          </th>
          <th scope="col" class="px-6 py-3">
            Serial Number
          </th>
          <th scope="col" class="px-6 py-3">
            Disabled
          </th>
          <th scope="col" class="px-6 py-3">
            Options
          </th>
        </tr>
      </thead>
      <tbody>
        <?php if ($records): ?>
          <?php foreach ($records as $record): ?>
            <tr class="dark:bg-gray-800 bg-gray-50 border-b dark:border-gray-700 border-gray-200">
              <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                <?= $record->id; ?>
              </th>
              <td class="px-6 py-4">
                <?= $record->equipmentType; ?>
              </td>
              <td class="px-6 py-4">
                <?= $record->manufacturer; ?>
              </td>
              <td class="px-6 py-4 overflow-ellipsis">
                <?= $record->serialNumber; ?>
              </td>
              <td class="px-6 py-4 overflow-ellipsis">
                <?= $record->disabled ? "TRUE" : "FALSE"; ?>
              </td>
              <td class="px-6 py-4">
                <a class="block text-blue-600 hover:text-blue-800 hover:underline focus:outline-none font-medium rounded-lg text-sm px-5 py-2.5 text-center cursor-pointer" href="/edit?id=<?= $record->id ?>">
                  Edit
                </a>
              </td>
            </tr>
          <?php endforeach ?>
        <?php else: ?>
          <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700 border-gray-200">
            <th scope="row" rowspan="4" class="px-6 py-4 text-xl font-bold text-gray-900 whitespace-nowrap dark:text-white">No results found...</h1>
          </tr>
        <?php endif ?>
      </tbody>
    </table>
  </div>
</div>
