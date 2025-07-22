<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dateInput = trim($_POST['date'] ?? '');
    $dateObject = DateTime::createFromFormat('Y-m-d', $dateInput);

    $mesi = [
        '01' => 'Gennaio', '02' => 'Febbraio', '03' => 'Marzo',
        '04' => 'Aprile', '05' => 'Maggio', '06' => 'Giugno',
        '07' => 'Luglio', '08' => 'Agosto', '09' => 'Settembre',
        '10' => 'Ottobre', '11' => 'Novembre', '12' => 'Dicembre'
    ];

    if ($dateObject) {
        $giorno = $dateObject->format('d');
        $mese = $dateObject->format('m');
        $anno = $dateObject->format('Y');
        $meseItaliano = $mesi[$mese];
        $date = "$giorno $meseItaliano $anno";
    }

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

        window.onload = () => {
    // Aggiunge un campo attività di default alla prima apertura
    aggiungiCampoAttivita();

    const voiceBtn = document.getElementById('voice-record-btn');
    if (!voiceBtn) return;

    // Controlla supporto API
    if (!('webkitSpeechRecognition' in window) && !('SpeechRecognition' in window)) {
        voiceBtn.disabled = true;
        voiceBtn.textContent = 'Registrazione non supportata';
        return;
    }

    const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
    const recognition = new SpeechRecognition();
    recognition.lang = 'it-IT'; // italiano
    recognition.interimResults = false;
    recognition.maxAlternatives = 1;

    voiceBtn.onclick = () => {
        recognition.start();
        voiceBtn.textContent = 'Ascolto... clicca per fermare';
        voiceBtn.disabled = true;
    };

    recognition.onresult = (event) => {
        const transcript = event.results[0][0].transcript.trim();
            if (transcript) {
                // Crea nuovo campo attività con trascrizione
                const container = document.getElementById('tasks-container');

                const wrapper = document.createElement('div');
                wrapper.className = 'd-flex align-items-center mb-2';

                const input = document.createElement('input');
                input.type = 'text';
                input.name = 'tasks[]';
                input.placeholder = 'Scrivi una attività';
                input.className = 'form-control custom-input';
                input.required = true;
                input.value = transcript;

                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'btn btn-delete-task ms-2';
                btn.innerHTML = '<i class="bi bi-trash"></i>';
                btn.onclick = () => container.removeChild(wrapper);

                wrapper.appendChild(input);
                wrapper.appendChild(btn);
                container.appendChild(wrapper);
            }
            voiceBtn.textContent = 'Registra attività';
            voiceBtn.disabled = false;
        };

        recognition.onerror = (event) => {
            console.error('Errore riconoscimento vocale:', event.error);
            voiceBtn.textContent = 'Registra attività';
            voiceBtn.disabled = false;
        };

        recognition.onend = () => {
            voiceBtn.textContent = 'Registra attività';
            voiceBtn.disabled = false;
        };
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
            <button type="button" class="btn btn-success mt-2 ms-2" id="voice-record-btn">
                <i class="bi bi-mic-fill"></i> Registra attività
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
