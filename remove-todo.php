<?php

$_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$id = $_GET['id'] ?? '';

$filename = __DIR__ . "/data/todos.json";
if ($id) {
  $todos = json_decode(file_get_contents($filename), true);
  $todoIndex = array_search($id, array_column($todos, 'id'));
  if ($todoIndex !== false) {
    array_splice($todos, $todoIndex, 1);
    file_put_contents($filename, json_encode($todos));
  }
}

// Sanitize GET input
header('Location: /');
