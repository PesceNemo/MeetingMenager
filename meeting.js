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
        });
    });

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
