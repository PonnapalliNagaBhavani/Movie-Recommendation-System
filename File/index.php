<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Movie Recommendations</title>
    <link rel="stylesheet" href="styles.css"> 
</head>
<body>
    
    <header>
        <h1>Welcome to Your Personalized Movie Recommendations</h1>
        <nav>
            <a style="color: yellow;" href="about.html">About</a> |
            <a style="color: yellow;" href="services.html">Services</a> |
            <a style="color: yellow;" href="clients.html">Clients</a> |
            <a style="color: yellow;" href="contact.html">Contact</a> |
            <a style="color: yellow;" href="Login.html">Logout</a>

        </nav>
    </header>

    <header>
        <h2>Welcome.</h2>
        <h3>Millions of movies, TV shows, and people to discover. Explore now.</h3>
    </header>

    <main>
        <form method="GET" action="">
            <input type="text" name="search" placeholder="Search for movies, TV shows, or people......" required>
            <button type="submit">Search</button>
        </form>
        <div id="search-results">
            <?php
            function fetchRecommendations($apiKey, $genre, $page = 1) {
                $url = "http://www.omdbapi.com/?s=" . urlencode($genre) . "&apikey=$apiKey&page=$page";
                $response = @file_get_contents($url);
                if ($response === false) return [];
                $data = json_decode($response, true);
                return $data['Search'] ?? [];
            }

            if (isset($_GET['search'])) {
                $searchQuery = $_GET['search'];
                $searchResults = fetchMovies($apiKey, $searchQuery);
                
                if (!empty($searchResults)) {
                    echo "<h2>Search Results for '" . htmlspecialchars($searchQuery) . "'</h2>";
                    echo "<div class='movie-list'>";
                    foreach ($searchResults as $movie) {
                        $poster = isset($movie['Poster']) && $movie['Poster'] !== 'N/A' ? $movie['Poster'] : 'static-poster.jpg'; 
                        echo "<div class='movie-card'>
                                    <img src='" . htmlspecialchars($poster) . "' alt='" . htmlspecialchars($movie['Title']) . "'>
                                    <h3 style='color: white;'>" . htmlspecialchars($movie['Title']) . "</h3>
                                    <p>" . htmlspecialchars($movie['Year']) . "</p>
                                </a>
                              </div>";
                    }
                    echo "</div>";
                } else {
                    echo "<p>No movies found for '" . htmlspecialchars($searchQuery) . "'.</p>";
                }
            }
            ?>
        </div>

        <!-- Trending Movies -->
        <h2>Trending Movies</h2>
        <div id="movie-list">
            <?php
            // Fetch different categories of movies
            $movieCategories = [
                'popular', 
                'movie', 
                'series', 
                'action', 
                'drama', 
                'comedy', 
                'thriller', 
                'romance', 
                'horror',
                'adventure', 
                'fantasy', 
                'documentary', 
                'family', 
                'history', 
                'music' 
            ];

            foreach ($movieCategories as $category) {
                echo "<h3>" . ucfirst($category) . "</h3>";
                echo "<div class='movie-list'>";
                
                $movies = [];
                $page = 1;

                // Fetch movies until we have 15 or more
                while (count($movies) < 15 && $page <= 3) { // Limit to 3 pages
                    $fetchedMovies = fetchMovies($apiKey, $category, $page);
                    $movies = array_merge($movies, $fetchedMovies);
                    $page++;
                }

                // Limit the output to the first 15 movies
                $movies = array_slice($movies, 0, 15);

                if (!empty($movies)) {
                    foreach ($movies as $movie) {
                        $poster = isset($movie['Poster']) && $movie['Poster'] !== 'N/A' ? $movie['Poster'] : 'static-poster.jpg'; 
                        echo "<div class='movie-card'>
                                <a href='movie.php?id=" . htmlspecialchars($movie['imdbID']) . "&user_id=" . urlencode($user_id) . "'>
                                    <img src='" . htmlspecialchars($poster) . "' alt='" . htmlspecialchars($movie['Title']) . "'>
                                    <h3 style='color: yellow;'>" . htmlspecialchars($movie['Title']) . "</h3>
                                    <p>" . htmlspecialchars($movie['Year']) . "</p>
                                </a>
                              </div>";
                    }
                } else {
                    echo "<p>No movies found in this category.</p>";
                }
                echo "</div>";
            }
            ?>
        </div>

        <h2>Recent Regional Movies</h2>
        <?php
        $regionalCategories = [
            'Telugu' => 'telugu',
            'Tamil' => 'tamil',
            'Malayalam' => 'malayalam'
        ];

        foreach ($regionalCategories as $language => $genre) {
            echo "<h3>$language Movies</h3>";
            echo "<div class='movie-list'>";
            
            $movies = [];
            $page = 1;

            // Fetch movies until we have 10 or more
            while (count($movies) < 10 && $page <= 3) { // Limit to 3 pages
                $fetchedMovies = fetchMovies($apiKey, $genre, $page);
                $movies = array_merge($movies, $fetchedMovies);
                $page++;
            }

            // Display Regional Movies
            if (!empty($movies)) {
                foreach ($movies as $movie) {
                    $poster = isset($movie['Poster']) && $movie['Poster'] !== 'N/A' ? $movie['Poster'] : 'static-poster.jpg'; 
                    echo "<div class='movie-card'>
                                <img src='" . htmlspecialchars($poster) . "' alt='" . htmlspecialchars($movie['Title']) . "'>
                                <h3 style='color: yellow;'>" . htmlspecialchars($movie['Title']) . "</h3>
                                <p>" . htmlspecialchars($movie['Year']) . "</p>
                            </a>
                          </div>";
                }
            }
            echo "</div>";
        }
        ?>
    </main>
</body>
</html>