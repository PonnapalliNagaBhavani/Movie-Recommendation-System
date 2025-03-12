const reviewForm = document.getElementById('reviewForm');
const reviewInput = document.getElementById('reviewInput');
const reviewFeed = document.getElementById('reviewFeed');

let sentimentData = {
    positive: 0,
    negative: 0,
    neutral: 0,
    reviews: []
};

// Function to update charts
let pieChart, barGraph;

function updateCharts() {
    console.log("Updating charts with data:", sentimentData);

    if (pieChart) pieChart.destroy();
    if (barGraph) barGraph.destroy();

    pieChart = new Chart(pieChartCtx, {
        type: 'pie',
        data: {
            labels: ['Positive', 'Negative', 'Neutral'],
            datasets: [{
                data: [sentimentData.positive, sentimentData.negative, sentimentData.neutral],
                backgroundColor: ['#36A2EB', '#FF6384', '#FFCE56']
            }]
        }
    });

    barGraph = new Chart(barGraphCtx, {
        type: 'bar',
        data: {
            labels: ['Positive', 'Negative', 'Neutral'],
            datasets: [{
                label: '# of Reviews',
                data: [sentimentData.positive, sentimentData.negative, sentimentData.neutral],
                backgroundColor: ['#36A2EB', '#FF6384', '#FFCE56']
            }]
        }
    });
}



// Function to fetch and update reviews and sentiment data
async function fetchReviews() {
    try {
        const response = await fetch(`http://localhost:5000/api/reviews?imdb_id=${imdbID}`);
        const reviews = await response.json();
        console.log("Fetched reviews:", reviews);

        sentimentData = { positive: 0, negative: 0, neutral: 0, reviews: [] }; // Reset sentiment data

        // Clear existing reviews
        reviewFeed.innerHTML = '';

        // Process reviews and update sentiment data
        reviews.forEach(review => {
            const reviewElement = document.createElement('div');
            reviewElement.className = 'review';
            reviewElement.innerHTML = `
                <p>${review.text} (Sentiment: ${review.sentiment})</p>
                <button class="like-button" data-id="${review.id}">Like</button>
                <button class="dislike-button" data-id="${review.id}">Dislike</button>
                <span class="like-count">${review.likes} Likes</span>
                <span class="dislike-count">${review.dislikes} Dislikes</span>
            `;
            reviewFeed.appendChild(reviewElement);

            // Update sentiment counts
            sentimentData[review.sentiment]++;
            sentimentData.reviews.push(review);
        });

        // Update charts only if the data has changed
        updateCharts();
    } catch (error) {
        console.error("Error fetching reviews:", error);
    }
}


// Update review submission
reviewForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    const reviewText = reviewInput.value;

    try {
        await fetch('http://localhost:5000/api/reviews', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ text: reviewText, imdb_id: imdbID }),
        });

        fetchReviews();
        reviewInput.value = '';
    } catch (error) {
        console.error("Error posting review:", error);
    }
});


// Like/Dislike functionality
reviewFeed.addEventListener('click', async (e) => {
    if (e.target.classList.contains('like-button') || e.target.classList.contains('dislike-button')) {
        const reviewId = e.target.dataset.id;
        const action = e.target.classList.contains('like-button') ? 'like' : 'dislike';

        try {
            await fetch(`http://localhost:5000/api/reviews/${reviewId}/${action}`, { method: 'POST' });
            fetchReviews(); // Re-fetch reviews after updating like/dislike
        } catch (error) {
            console.error(`Error with ${action} action:`, error);
        }
    }
});

// Initial fetch when the page loads
fetchReviews();
