<?php

use App\Model\EquipmentRecord;

/** @var EquipmentRecord $record */
/** @var string|null $error */

if (!isset($record)) {
  throw new InvalidArgumentException('Unable to find the equipment record variable in scope');
}

?>

<div tabindex="-1" class="overflow-y-auto flex overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
  <form class="relative p-4 w-full max-w-2xl max-h-full" action="/edit/submit" method="post">
    <!-- content -->
    <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">

      <!-- header -->
      <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
          Edit Record
        </h3>
        <a href="/" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
          <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
          </svg>
          <span class="sr-only">Back to Query Builder</span>
        </a>
      </div>

      <!-- body -->
      <div class="p-4 md:p-5 space-y-4">
        <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400">
          Edit an existing equipment record.
        </p>

        <div class="mb-5 space-y-2">
          <label for="type" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Equipment Type</label>
          <input value="<?= $record->equipmentType ?>" name="type" type="text" id="type" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="computer" pattern="^[a-zA-Z ]+$" required />

          <input id="enable-radio-equipment" type="radio" value="false" name="disableEquipment" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" checked>
          <label for="enable-radio-equipment" class="mr-4 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Enable</label>

          <input id="disable-radio-equipment" type="radio" value="true" name="disableEquipment" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
          <label for="disable-radio-equipment" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Disable</label>
        </div>
        <div class="mb-5 space-y-2">
          <label for="manufacturer" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Manufacturer</label>
          <input value="<?= $record->manufacturer ?>" name="manufacturer" type="text" id="manufacturer" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="IBM" pattern="^[A-Z][a-zA-Z]+$" required />

          <input id="enable-radio-manufacturer" type="radio" value="false" name="disableManufacturer" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" checked>
          <label for="enable-radio-manufacturer" class="mr-4 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Enable</label>

          <input id="disable-radio-manufacturer" type="radio" value="true" name="disableManufacturer" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
          <label for="disable-radio-manufacturer" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Disable</label>
        </div>
        <div class="mb-5">
          <label for="serial" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Serial Number</label>
          <input value="<?= $record->serialNumber ?>" name="serial" type="text" id="serial" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="bd84cbfa647b06847b686b23b04f4bc91dead1824c958cdc96314e5419aa2276" pattern="^[a-fA-F0-9]+$" required />
        </div>
      </div>

      <!-- Error message -->
      <?php if ($error !== null): ?>
        <div class="flex items-center p-4 mx-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
          <svg class="shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
          </svg>
          <span class="sr-only">Info</span>
          <div>
            <span class="font-medium"><?= $error ?>
          </div>
        </div>
      <?php endif ?>

      <!-- footer -->
      <div class="flex justify-between items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
        <button name="id" value="<?= $record->id ?>" type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Edit</button>
        <a type="button" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-red-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-red-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-red-800" href="/delete?id=<?= $record->id ?>">Delete</a>
      </div>
    </div>
  </form>
</div>
