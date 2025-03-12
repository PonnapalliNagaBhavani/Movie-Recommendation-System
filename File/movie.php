
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Details</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Global Styles */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #2c3e50;
            margin: 0;
            padding: 0;
            color: #ecf0f1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            text-align: center;
        }

        h1 {
            font-size: 3rem;
            color: #f39c12;
            margin-top: 20px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
        }

        header {
            margin-bottom: 20px;
        }

        /* Styling for the movie details section */
        main {
            width: 90%;
            max-width: 1000px;
            margin-top: 30px;
            background-color: rgba(0, 0, 0, 0.7);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
        }

        .movie-details {
            text-align: center;
            line-height: 1.6;
            font-size: 1.1rem;
            color: #ecf0f1;
        }

        .movie-details strong {
            color: #f39c12;
            font-weight: bold;
        }

        /* Styling for the movie poster */
        .movie-poster {
            margin: 20px auto;
            max-width: 500px;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.6);
        }


        /* Button Styling */
        button {
            background-color: #e74c3c;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #c0392b;
        }

        /* Additional small elements styling */
        a {
            color: #f39c12;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .ratings-section {
            margin-top: 20px;
            text-align: left;
        }

    </style>
</head>
<body>
    <header>
        <h1>Movie Details</h1>
        <button onclick="redirectToUserReviews('<?php echo htmlspecialchars($imdbID); ?>')">
            User Reviews
        </button>
    </header>

    <main>
        <?php
        function fetchMovieDetails($apiKey, $imdbID) {
            $url = "http://www.omdbapi.com/?i=$imdbID&apikey=$apiKey";
            $response = file_get_contents($url);
            return json_decode($response, true);
        }
        
        // Fetch movie details
        $movieDetails = fetchMovieDetails($apiKey, $imdbID);

        // Display movie details if available
        if (!empty($movieDetails) && $movieDetails['Response'] == 'True') {
            echo "<h2>" . htmlspecialchars($movieDetails['Title']) . " (" . htmlspecialchars($movieDetails['Year']) . ")</h2>";
            echo "<img class='movie-poster' src='" . htmlspecialchars($movieDetails['Poster']) . "' alt='" . htmlspecialchars($movieDetails['Title']) . "'>";
            echo "<div class='movie-details'>";
            echo "<p><strong>Plot Summary:</strong> " . htmlspecialchars($movieDetails['Plot']) . "</p>";
            echo "<p><strong>Rating:</strong> " . htmlspecialchars($movieDetails['Rated']) . "</p>";
            echo "<p><strong>Release Date:</strong> " . htmlspecialchars($movieDetails['Released']) . "</p>";
            echo "<p><strong>Runtime:</strong> " . htmlspecialchars($movieDetails['Runtime']) . "</p>";
            echo "<p><strong>Genre(s):</strong> " . htmlspecialchars($movieDetails['Genre']) . "</p>";
            echo "<p><strong>Director(s):</strong> " . htmlspecialchars($movieDetails['Director']) . "</p>";
            echo "<p><strong>Writer(s):</strong> " . htmlspecialchars($movieDetails['Writer']) . "</p>";
            echo "<p><strong>Actor(s):</strong> " . htmlspecialchars($movieDetails['Actors']) . "</p>";
            echo "<p><strong>Language(s):</strong> " . htmlspecialchars($movieDetails['Language']) . "</p>";
            echo "<p><strong>Country/Countries:</strong> " . htmlspecialchars($movieDetails['Country']) . "</p>";
            echo "<p><strong>Awards Won:</strong> " . htmlspecialchars($movieDetails['Awards']) . "</p>";
            echo "<p><strong>Movie Poster:</strong> <a style='color: yellow;' href='" . htmlspecialchars($movieDetails['Poster']) . "'>View Image</a></p>";
            echo "</p>";
            echo "<p><strong>Metascore:</strong> " . htmlspecialchars($movieDetails['Metascore']) . "</p>";
            echo "<p><strong>IMDb Rating:</strong> " . htmlspecialchars($movieDetails['imdbRating']) . "/10</p>";
            echo "<p><strong>Type:</strong> " . htmlspecialchars($movieDetails['Type']) . "</p>";
            if (isset($movieDetails['BoxOffice'])) {
                echo "<p><strong>Box Office:</strong> " . htmlspecialchars($movieDetails['BoxOffice']) . "</p>";
            }
        
            // Check if Production field exists before displaying
            if (isset($movieDetails['Production'])) {
                echo "<p><strong>Production:</strong> " . htmlspecialchars($movieDetails['Production']) . "</p>";
            }
        
            // Check if Website field exists before displaying
            if (isset($movieDetails['Website'])) {
                echo "<p><strong>Website:</strong> <a href='" . htmlspecialchars($movieDetails['Website']) . "' target='_blank'>" . htmlspecialchars($movieDetails['Website']) . "</a></p>";
            }
            echo "</div>";

        } else {
            echo "<p>Movie details not found!</p>";
        }
        $searchQuery = urlencode($movieDetails['Title'] . " trailer");

        echo "<h3>Watch Now</h3>";
        echo "<p><a href='https://www.youtube.com/results?search_query=$searchQuery' target='_blank'>Search for Movie Trailers</a></p>";
        echo "</div>";
        ?>
    </main>
</body>
</html>
