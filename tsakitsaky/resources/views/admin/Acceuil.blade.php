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
                        $('#place').val('');
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
                    console.error("Tsa mety", error);
                }
            });
        }

        function appendPlace(place) {
            var row = '<tr>' +
                      '<td>' + place.id + '</td>' +
                      '<td>' + place.name + '</td>' +
                      '</tr>';
            $('#table-body').append(row);
        }
    });
    </script>
