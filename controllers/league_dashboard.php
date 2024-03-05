<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>League Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">League Dashboard</h1>
        <div class="row mt-3">
            <div class="col-md-6">
                <h3>League Information</h3>
                <p>League Name: <span id="leagueName"></span></p>
                <p>League Code: <span id="leagueCode"></span></p>
                <!-- Add more league information here -->
            </div>
            <div class="col-md-6">
                <h3>League Members</h3>
                <ul id="leagueMembers" class="list-group">
                    <!-- League members will be dynamically loaded here -->
                </ul>
            </div>
        </div>
    </div>

    <!-- Include jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        // Fetch league information and members using AJAX
        $(document).ready(function() {
            $.ajax({
                url: 'fetch_league_info.php', // PHP script to fetch league information
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    // Update league information
                    $('#leagueName').text(response.leagueName);
                    $('#leagueCode').text(response.leagueCode);

                    // Update league members
                    var membersHtml = '';
                    response.members.forEach(function(member) {
                        membersHtml += '<li class="list-group-item">' + member.username + '</li>';
                    });
                    $('#leagueMembers').html(membersHtml);
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    alert('An error occurred while fetching league information. Please try again.');
                }
            });
        });
    </script>
</body>
</html>
