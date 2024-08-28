<div class="row">
    <div class="col-md-12">
        <form action="{{url('ajout_place')}}" id="addPlace" method="post">
            @csrf
            <div class="row" >
                <div class="col-md-3">
                    <input type="text" id="place" name="place">
                    <button type="submit" class="btn btn-primary">Valider</button>
                </div>
            </div>
        </form>
    </div>
</div>

    <br>

<div class="row">
    <div class="col-md-6">
        <div id="table-container">
            <table id="produitTable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Nº</th>
                        <th>place</th>
                    </tr>
                </thead>
                <tbody id="table-body">
                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="{{ asset('assets/js/jquery.js') }}"></script>
<script>
    $(document).ready(function() {
        var table = initDataTable('#produitTable',"{{route('getPlace')}}",[
            {"data":"id"},
            {"data":"place"}
        ]);
        $('#addPlace').on('submit',function(event) {
            event.preventDefault(); // Empêche le rechargement de la page

            // Efface les messages d'erreur existants
            $('.error-message').text('');

            $.ajax({
                type: 'POST',
                url: $(this).attr('action'), // URL de l'action du formulaire
                data: $(this).serialize(), // Sérialise les données du formulaire
                dataType: 'json',
                success: function(response) {
                    alert('Place ajouté avec succès.');
                    $('#addPlace')[0].reset(); // Réinitialise le formulaire
                    location.reload(null,false); // Recharge les données, mais vous pouvez supprimer si vous ne voulez pas
                },
                error: function(xhr, status, error) {
                    var errors = xhr.responseJSON.errors; // Récupère les erreurs JSON
                    $.each(errors, function(index, value) {
                        // Affiche les erreurs sous les champs de formulaire correspondants
                        $('#error_' + index).text(value[0]);
                    });
                }
            });
        });
    });
</script>
