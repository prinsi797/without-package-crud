<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Location Autocomplete</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .autocomplete-items {
            border: 1px solid #ddd;
            max-height: 150px;
            overflow-y: auto;
            position: absolute;
            z-index: 1000;
            width: 100%;
            background-color: white;
        }
        .autocomplete-item {
            padding: 10px;
            cursor: pointer;
        }
        .autocomplete-item:hover {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2>Location Autocomplete</h2>
        <div class="form-group mt-3 position-relative">
            <input type="text" id="location-input" class="form-control" placeholder="Start typing Surat city areas...">
            <div id="autocomplete-list" class="autocomplete-items"></div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#location-input').on('input', function () {
                const query = $(this).val();

                if (query.length > 2) { // Fetch suggestions only for 3+ characters
                    $.ajax({
                        url: '{{ route("location.search") }}',
                        method: 'GET',
                        data: { query: query },
                        success: function (data) {
                            let dropdown = $('#autocomplete-list');
                            dropdown.empty();

                            if (data.length > 0) {
                                data.forEach(location => {
                                    const displayName = location.display_name;
                                    const lat = location.lat;
                                    const lon = location.lon;

                                    dropdown.append(`
                                        <div class="autocomplete-item" 
                                             data-lat="${lat}" 
                                             data-lon="${lon}">
                                            ${displayName}
                                        </div>
                                    `);
                                });
                            } else {
                                dropdown.append('<div class="autocomplete-item">No results found</div>');
                            }
                        }
                    });
                } else {
                    $('#autocomplete-list').empty();
                }
            });

            // Handle dropdown click
            $(document).on('click', '.autocomplete-item', function () {
                const selectedText = $(this).text();
                $('#location-input').val(selectedText);
                $('#autocomplete-list').empty();
            });

            // Close dropdown when clicking outside
            $(document).on('click', function (e) {
                if (!$(e.target).closest('#location-input').length) {
                    $('#autocomplete-list').empty();
                }
            });
        });
    </script>
</body>
</html>
