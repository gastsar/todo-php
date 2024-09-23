<?php
const ERROR_REQUIRED = 'Veuillez renseigner ce champ';
const ERROR_MAX_LENGTH = 'Veuillez entrer moins de 50 caractères';
const ERROR_MIN_LENGTH = 'Veuillez entrer au moins 5 caractères';
$filename = __DIR__ . "/data/todos.json";
$error = '';
$todo = '';
$todos = [];

if (file_exists($filename)) {
  $data = file_get_contents($filename);
  $todos = json_decode($data, true) ?? [];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  $_POST = filter_input_array(INPUT_POST, [
    "todo" => [
      "filter" => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
      "flags" => FILTER_FLAG_NO_ENCODE_QUOTES | FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_BACKTICK
    ]
  ]);
  $todo = $_POST['todo'] ?? '';

  if (!$todo) {
    $error = ERROR_REQUIRED;
  } elseif (mb_strlen($todo) < 5) {
    $error = ERROR_MIN_LENGTH;
  } elseif (mb_strlen($todo) > 50) {
    $error = ERROR_MAX_LENGTH;
  }
  if (!$error) {
    $todos = [...$todos, [
      'name' => $todo,
      'done' => false,
      'id' => time(),
    ]];
    file_put_contents($filename, json_encode($todos, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    header('Location: /');
  }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php require_once 'includes/head.php' ?>
  <title>TODO</title>

</head>

<body>
  <?php require_once 'includes/header.php' ?>
  <main id="content">
    <div class="container">
      <h1>TODO List</h1>
      <form action="/" method="POST" id="searchForm">
        <label for="todo">
          <input type="text" id="todo" name="todo" value="<?= $todo ?>" placeholder="Ajouter une tâche">
          <button id="addButton" type="submit"><span class="material-symbols-outlined">
              add_task
            </span></button>
        </label>

      </form>
      <?php if ($error): ?>
      <p class="error"><?= $error ?></p>
      <?php endif; ?>
      <ul class="todo-list">
        <?php foreach ($todos as $t): ?>
        <li class=" todo-item <?= $t['done'] ? ' completed' : '' ?>">
          <span class="name"><?= $t['name'] ?></span>

          <span class="actions">
            <a href="/edit-todo.php?id=<?= $t['id'] ?>">
              <button class="modify btn-small">
                <span class="material-symbols-outlined">
                  edit
                </span>
              </button></a>

            <a href="/status-todo.php?id=<?= $t['id'] ?>">
              <button class="complete btn-small">
                <?= $t['done'] ? '<span class="complete material-symbols-outlined">playlist_remove</span>' : '<span class="material-symbols-outlined">playlist_add_check</span>' ?>
              </button>
            </a>

            <a href="/remove-todo.php?id=<?= $t['id'] ?>">
              <button class="delete btn-small">
                <span class="material-symbols-outlined">
                  delete
                </span>
              </button>
            </a>
          </span>
        </li>
        <?php endforeach; ?>
      </ul>
    </div>

  </main>

  <?php require_once 'includes/footer.php' ?>
</body>

</html>