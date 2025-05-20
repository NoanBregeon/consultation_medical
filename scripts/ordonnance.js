$(document).ready(function() {
    // Initialisation de Select2 pour les patients
    $('.select2-patient').select2({
        language: "fr",
        placeholder: "Rechercher un patient...",
        allowClear: true,
        width: 'resolve',
        minimumInputLength: 3,
        ajax: {
            url: '../controllers/patientController.php?action=search',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term
                };
            },
            processResults: function (data) {
                return { results: data };
            },
            cache: true
        }
    });
    
    // Initialisation de Select2 pour les médicaments
    $('.select2-medicament').select2({
        language: "fr",
        placeholder: "Rechercher un médicament...",
        allowClear: true,
        width: 'resolve',
        minimumInputLength: 3,
        ajax: {
            url: '../controllers/MedicamentController.php?action=search',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term
                };
            },
            processResults: function (data) {
                return { results: data };
            },
            cache: true
        }
    });
    
    // Permettre la suppression des médicaments (sauf le premier)
    $('#medicaments').on('click', '.btn-remove', function() {
        $(this).closest('.medicament-item').remove();
    });
});

// Fonction pour initialiser Select2 sur les nouveaux éléments
function initializeSelect2() {
    $('.select2-medicament').select2({
        language: "fr",
        placeholder: "Rechercher un médicament...",
        allowClear: true,
        width: 'resolve',
        dropdownAutoWidth: true,
        minimumInputLength: 3,
        ajax: {
            url: '../controllers/MedicamentController.php?action=search',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term
                };
            },
            processResults: function (data) {
                return { results: data };
            },
            cache: true
        }
    });
}

function ajouterMedicament() {
    const container = document.getElementById('medicaments');
    const newItem = document.createElement('div');
    newItem.className = 'medicament-item';

    // Créer un nouvel élément HTML pour le médicament
    newItem.innerHTML = `
        <select name="medicament[]" class="select2-medicament" required>
            <option value=""></option>
        </select>
        <input name="posologie[]" placeholder="Posologie (ex: 1 comprimé matin et soir)" required>
        <button type="button" class="btn-remove"><i class="fas fa-times"></i></button>
    `;

    // Ajouter le nouvel élément au conteneur
    container.appendChild(newItem);

    // Réinitialiser Select2 pour le nouvel élément
    $(newItem).find('.select2-medicament').select2({
        language: "fr",
        placeholder: "Rechercher un médicament...",
        allowClear: true,
        width: 'resolve',
        dropdownAutoWidth: true,
        minimumInputLength: 3,
        ajax: {
            url: '../controllers/MedicamentController.php?action=search',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term
                };
            },
            processResults: function (data) {
                return { results: data };
            },
            cache: true
        }
    });
}