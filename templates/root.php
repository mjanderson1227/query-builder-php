<?php

/** @var string|null $page */
if (! $page) {
  throw new InvalidArgumentException('Unable to find the template variable $page');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="/static?file=query.js"></script>
  <title>Query Builder</title>
  <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4" defer></script>
  <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js" defer></script>
</head>

<body class="dark:bg-gray-900 bg-white">
  <?= $page ?>
</body>

</html>
