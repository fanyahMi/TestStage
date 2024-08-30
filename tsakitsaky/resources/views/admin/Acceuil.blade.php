<div class="row">
    <div class="col-md-12">
        <form action="{{ url('/ajout_place') }}" id="addPlace" method="post">
            @csrf
            <div class="row">
                <div class="col-md-3">
                    <input type="text" id="place" name="place" placeholder="Entrez une place">
                    <button type="submit" class="btn btn-primary">Valider</button>
                </div>
            </div>
        </form>
        <div id="error-message" class="alert alert-danger" style="display:none;"></div>
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
                        <th>Place</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="table-body">
                </tbody>
            </table>
        </div>
    </div>
</div>


<div class="modal fade" id="editPlaceModal" tabindex="-1" role="dialog" aria-labelledby="editPlaceModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPlaceModalLabel">Modifier Place</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editPlaceForm">
                    @csrf
                    <input type="hidden" id="edit-place-id">
                    <div class="form-group">
                        <label for="edit-place">Place</label>
                        <input type="text" id="edit-place" name="place" class="form-control" placeholder="Entrez une place">
                    </div>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    $(document).ready(function() {
        loadPlaces();

        $('#addPlace').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: '{{ url("/ajout_place") }}',
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.status === 'success') {
                        appendPlace(response.place);
                        $('#place').val(''); // Clear the input field
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 400) {
                        var errors = xhr.responseJSON.errors;
                        var errorMessage = '';
                        if (errors.place) {
                            errorMessage += errors.place[0] + '<br>';
                        }
                        $('#error-message').html(errorMessage).show();
                    }
                }
            });
        });

        $(document).on('click', '.delete-btn', function() {
            var id = $(this).data('id_place');

            if (confirm('Êtes-vous sûr de vouloir supprimer cette place ?')) {
                $.ajax({
                    url: '/supPlace/' + id,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            alert(response.message);
                            loadPlaces(); // Recharge les données après suppression
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Erreur lors de la suppression : ", error);
                    }
                });
            }
        });

        $(document).on('click', '.edit-btn', function() {
            var id = $(this).data('id_place');
            var place = $(this).data('place');

            $('#edit-place-id').val(id);
            $('#edit-place').val(place);
            $('#editPlaceModal').modal('show'); // Ouvre le modal
        });

        $('#editPlaceForm').on('submit', function(e) {
            e.preventDefault();

            var id = $('#edit-place-id').val();
            var place = $('#edit-place').val();

            $.ajax({
                url: '/updatePlace/' + id,
                type: 'PUT',
                data: {
                    _token: '{{ csrf_token() }}',
                    place: place
                },
                success: function(response) {
                    if (response.status === 'success') {
                        $('#editPlaceModal').modal('hide'); // Ferme le modal
                        loadPlaces(); // Recharge les données
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 400) {
                        var errors = xhr.responseJSON.errors;
                        var errorMessage = '';
                        if (errors.place) {
                            errorMessage += errors.place[0] + '<br>';
                        }
                        $('#error-message').html(errorMessage).show();
                    }
                }
            });
        });

        function loadPlaces() {
            $.ajax({
                url: '{{ url("/get_places") }}',
                type: 'GET',
                success: function(data) {
                    $('#table-body').empty();
                    data.forEach(function(place) {
                        appendPlace(place);
                    });
                },
                error: function(xhr, status, error) {
                    console.error("Error loading places:", error);
                }
            });
        }

        function appendPlace(place) {
            var row = '<tr>' +
                    '<td>' + place.id_place + '</td>' +  // Use place.id_place to get the ID
                    '<td>' + place.place + '</td>' +
                    '<td><button class="btn btn-danger btn-sm delete-btn" data-id_place="' + place.id_place + '">Supprimer</button></td>' +
                    '<td><button class="btn btn-warning btn-sm edit-btn" data-id_place="' + place.id_place + '" data-place="' + place.place + '">Modifier</button></td>' +
                    '</tr>';
            $('#table-body').append(row);
        }
    });

</script>
