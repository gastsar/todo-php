<?php
$filename = __DIR__ . "/data/todos.json";

// Sanitize GET input
$_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$id = $_GET['id'] ?? '';

if ($id) {
  $data = file_get_contents($filename);
  $todos = json_decode($data, true) ?? [];

  if (count($todos)) {
    // Search for the task by its ID
    $todoIndex = array_search($id, array_column($todos, 'id'));

    // Ensure the task was found
    if ($todoIndex !== false) {
      // Toggle the 'done' status
      $todos[$todoIndex]['done'] = !$todos[$todoIndex]['done'];

      // Write the updated list back to the JSON file
      file_put_contents($filename, json_encode($todos));
    }
  }
}

// Redirect back to the homepage
header('Location: /');
