<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = trim($_POST['date'] ?? '');
    $tasks = $_POST['tasks'] ?? [];

    // Filtra le attività vuote
    $cleanedTasks = array_filter(array_map('trim', $tasks));

    $meetings = file_exists('meetings.json')
        ? json_decode(file_get_contents('meetings.json'), true)
        : [];

    $newId = (count($meetings) > 0) ? max(array_map('intval', array_keys($meetings))) + 1 : 1;

    $meetings[$newId] = [
        'date' => $date,
        'tasks' => $cleanedTasks
    ];

    file_put_contents('meetings.json', json_encode($meetings, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

    header("Location: index.php");
    exit;
}
?>


<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Nuova Riunione</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.min.css" rel="stylesheet" />
    <link href="index.css" rel="stylesheet" />

    <script>
        let taskCount = 1;

        function aggiungiCampoAttivita() {
            const container = document.getElementById('tasks-container');

            const wrapper = document.createElement('div');
            wrapper.className = 'd-flex align-items-center mb-2';
            wrapper.id = 'task-row-' + taskCount;

            const input = document.createElement('input');
            input.type = 'text';
            input.name = 'tasks[]';
            input.placeholder = 'Scrivi una attività';
            input.className = 'form-control custom-input';
            input.required = true;

            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'btn btn-delete-task ms-2';
            btn.innerHTML = '<i class="bi bi-trash"></i>';
            btn.onclick = () => container.removeChild(wrapper);

            wrapper.appendChild(input);
            wrapper.appendChild(btn);
            container.appendChild(wrapper);

            taskCount++;
        }

        window.onload = () => {
            // Aggiunge un campo attività di default alla prima apertura
            aggiungiCampoAttivita();
        };
    </script>
</head>

<body class="meeting-body">
<div class="container py-5">
    <h2 class="text-primary-blue mb-4"><strong>Nuova Riunione</strong></h2>

    <form method="POST" action="">
        <div class="mb-3">
            <label for="date" class="form-label text-white">Data riunione</label>
            <input type="date" name="date" id="date" class="form-control custom-input" required />
        </div>

        <div class="mb-3">
            <label class="form-label text-white">Attività</label>
            <div id="tasks-container"></div>
            <button type="button" class="btn btn-info mt-2" onclick="aggiungiCampoAttivita()">
                <i class="bi bi-plus-circle"></i> Aggiungi attività
            </button>
        </div>

        <button type="submit" class="btn btn-primary-blue">
            <i class="bi bi-save"></i> Salva
        </button>
        <a href="index.php" class="btn btn-secondary ms-2">Annulla</a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
