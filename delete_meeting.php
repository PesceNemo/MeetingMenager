<?php
// Recupera l'ID della riunione da eliminare
$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'] ?? null;

if ($id) {
    // Carica i dati delle riunioni attive e archiviate
    $meetings = json_decode(file_get_contents('meetings.json'), true);
    $archivedMeetings = json_decode(file_get_contents('archived_meetings.json'), true);

    // Se la riunione esiste, trasferiscila negli archivi e rimuovila dalla lista
    if (isset($meetings[$id])) {
        // Aggiungi la riunione agli archivi
        $archivedMeetings[$id] = $meetings[$id];

        // Rimuovi la riunione dal file meetings.json
        unset($meetings[$id]);

        // Salva i dati aggiornati nei file
        file_put_contents('meetings.json', json_encode($meetings, JSON_PRETTY_PRINT));
        file_put_contents('archived_meetings.json', json_encode($archivedMeetings, JSON_PRETTY_PRINT));

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Riunione non trovata']);
    }
}
?> 