document.addEventListener('DOMContentLoaded', function() {
    // Gestione delle attività completate
    const taskCheckboxes = document.querySelectorAll('.task-checkbox');
    taskCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const taskText = checkbox.nextElementSibling;
            if (checkbox.checked) {
                taskText.style.textDecoration = 'line-through'; // Barrato quando completato
            } else {
                taskText.style.textDecoration = 'none'; // Rimuovi il barrato
            }

            // Verifica se tutte le attività sono fatte
            const allTasksDone = [...taskCheckboxes].every(checkbox => checkbox.checked);

            // Se tutte le attività sono fatte, archiviamo la riunione
            if (allTasksDone) {
                archivioRiunione();
            }
        });
    });

    // Funzione per archiviare la riunione
    function archivioRiunione() {
        const meetingId = document.getElementById('meeting-id').value; // ID della riunione (assicurati che sia disponibile nel DOM)

        fetch('archive_meeting.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id: meetingId })  // Passa l'ID della riunione da archiviare
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Puoi scegliere di aggiornare la UI o fare un redirect
                window.location.href = 'archived_meetings.php'; // Redirige alle riunioni archiviate
            } else {
                console.error('Errore nell\'archiviazione della riunione:', data.message);
            }
        })
        .catch(error => {
            console.error('Errore nella richiesta di archiviazione:', error);
        });
    }

    // Funzione per confermare l'eliminazione della riunione
    window.confermaEliminazione = function(id) {
        // Salva l'ID della riunione da eliminare
        window.meetingIdToDelete = id;

        // Mostra il modal di conferma
        const modal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
        modal.show();
    };

    // Funzione per eliminare la riunione, associata al bottone "Conferma eliminazione" nel modale
    document.getElementById('confirmDeleteButton')?.addEventListener('click', function() {
        const id = window.meetingIdToDelete; // Ottieni l'ID salvato

        if (id) {
            fetch('delete_meeting.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id: id })  // Passa l'ID della riunione da eliminare
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Nessun alert, fai solo redirect o aggiorna pagina
                    window.location.href = 'index.php';  // Torna alla lista delle riunioni
                } else {
                    // Puoi loggare l'errore senza alert
                    console.error('Errore nell\'eliminazione della riunione:', data.message);
                    // Eventualmente aggiorna la UI per mostrare errore senza alert
                }
            })
            .catch(error => {
                console.error('Errore nella richiesta di eliminazione:', error);
                // Gestisci errore senza alert
            });
        }
    });

    
});
