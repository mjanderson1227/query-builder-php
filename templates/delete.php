<?php

use App\Model\EquipmentRecord;

/** @var EquipmentRecord $id */
if (!isset($id)) {
  throw new InvalidArgumentException('Unable to find the equipment record variable in scope');
}

?>

<div tabindex="-1" class="overflow-y-auto flex overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
  <div class="w-full max-w-sm bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
    <div class="flex justify-center p-6">
      <div class="flex flex-col items-center">
        <h5 class="mb-1 text-xl font-medium text-gray-900 dark:text-white">Confirm Deletion</h5>
        <span class="text-sm text-gray-500 dark:text-gray-400">Are you sure you want to delete this record?</span>
        <div class="flex mt-4 md:mt-6 gap-8">
          <a href="/delete/submit?id=<?= $id ?>" class="inline-flex items-center px-4 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Delete</a>
          <a href="/" class="py-2 px-4 ms-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Cancel</a>
        </div>
      </div>
    </div>
  </div>
</div>
