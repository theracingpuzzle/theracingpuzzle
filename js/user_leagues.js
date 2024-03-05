<script>
$(document).ready(function() {
    // Function to fetch user's leagues and populate the table
    function fetchUserLeagues() {
        $.ajax({
            url: 'fetch_user_leagues.php', // Replace with the actual URL of your PHP script
            type: 'GET', // Assuming you have a PHP script to fetch leagues using GET method
            dataType: 'json',
            success: function(response) {
                // Clear existing table rows
                $('#userLeaguesTable').empty();

                // Populate the table with fetched data
                response.forEach(function(league) {
                    $('#userLeaguesTable').append(
                        '<tr>' +
                        '<td>' + league.league_name + '</td>' +
                        '<td>' + league.league_code + '</td>' +
                        '</tr>'
                    );
                });
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                // Handle error
                alert('An error occurred while fetching user leagues. Please try again.');
            }
        });
    }

    // Call fetchUserLeagues function when the page loads
    fetchUserLeagues();
});
</script>
