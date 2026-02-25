<!--   Core JS Files   -->
<script src="{{ asset('administration/assets/js/core/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('administration/assets/js/core/popper.min.js') }}"></script>
<script src="{{ asset('administration/assets/js/core/bootstrap.min.js') }}"></script>
<!-- chart -->
 <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- jQuery Scrollbar -->
<script src="{{ asset('administration/assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js') }}"></script>

<!-- jQuery Sparkline -->
<script src="{{ asset('administration/assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js') }}"></script>

<!-- Bootstrap Notify -->
<script src="{{ asset('administration/assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js') }}"></script>

<!-- Kaiadmin JS -->
<script src="{{ asset('administration/assets/js/kaiadmin.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Kaiadmin DEMO methods, don't include it in your project! -->
<script src="{{ asset(path: 'administration/assets/js/setting-demo.js') }}"></script>
<!-- Scripts Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    
  // scripte pour la confirmation pour la suppression
  function confirmerSuppression(id) {
        Swal.fire({
            title: 'Êtes-vous sûr ?',
            text: "Cette action est irréversible !",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Oui, supprimer',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('form-supprimer-' + id).submit();
            }
        });
    }
      document.addEventListener('DOMContentLoaded', function () {
        const deleteForms = document.querySelectorAll('.delete-form');

        deleteForms.forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Êtes-vous sûr ?',
                    text: "Cette action est irréversible !",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Oui, supprimer',
                    cancelButtonText: 'Annuler'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
    // gestion de la fenetre modal pour la catégorie
   $(document).ready(function() {
    $('#addCategoryModal form').on('submit', function(e) {
        e.preventDefault();
        let form = $(this);
        let url = form.attr('action');
        let data = form.serialize();

        $.post(url, data)
            .done(function(response) {
                // 1. Fermer la modale
                $('#addCategoryModal').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: 'Catégorie ajoutée !',
                    showConfirmButton: false,
                    timer: 2000
                });

                // 2. Réinitialiser le champ
                $('#nom').val('');
                form[0].reset();

                // 3. Recharge la liste et sélectionne la nouvelle
                $.get("{{ route('categories.list') }}", function(categories) {
                    let select = $('#categorie_id');
                    select.empty().append('<option value="">-- Sélectionner une catégorie --</option>');

                    $.each(categories, function(key, value) {
                        select.append('<option value="'+ value.id +'"'
                            + (value.id === response.id ? ' selected' : '')
                            + '>'+ value.nom +'</option>');
                    });
                });
            })
            .fail(function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: xhr.responseJSON.message || 'Erreur lors de l’ajout'
                });
            });
    });
});
// {{-- JS pour calcul dynamique --}}

document.addEventListener('DOMContentLoaded', function () {
    const quantites = document.querySelectorAll('.quantite');
    const montantTotalEl = document.getElementById('montant-total');
    const btnSubmit = document.getElementById('btn-submit');

    function formatNombre(n) {
        return n.toLocaleString('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function calculerTotal() {
        let total = 0;
        let anyQuantity = false;

        quantites.forEach(input => {
            const prix = parseFloat(input.dataset.prix);
            const quantite = parseInt(input.value) || 0;

            // Calcul sous-total
            const sousTotal = prix * quantite;

            // Afficher sous-total dans la colonne correspondante
            const tdSousTotal = input.closest('tr').querySelector('.sous-total');
            tdSousTotal.textContent = formatNombre(sousTotal);

            total += sousTotal;
            if (quantite > 0) anyQuantity = true;
        });

        montantTotalEl.textContent = formatNombre(total);

        // Activer/désactiver bouton selon présence quantité > 0
        btnSubmit.disabled = !anyQuantity;
    }

    quantites.forEach(input => {
        input.addEventListener('input', () => {
            // Contraindre la quantité à ne pas dépasser le stock
            const max = parseInt(input.max);
            if (parseInt(input.value) > max) input.value = max;
            if (parseInt(input.value) < 0 || isNaN(input.value)) input.value = 0;

            calculerTotal();
        });
    });

    calculerTotal(); 
});

// <!--  Script pour charger dynamiquement les détails de la vente -->

 function openDetailModal(id) {
        $('#modalDetail').modal('show');
        $('#modalDetailContent').html('<div class="text-center p-5">Chargement...</div>');

        $.get(`/ventes/${id}/ajax-detail`, function (data) {
            $('#modalDetailContent').html(data);
        }).fail(function () {
            $('#modalDetailContent').html('<div class="p-5 text-danger">Erreur de chargement</div>');
        });
    }
    
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@stack('scripts')
