from flask import Flask, request, jsonify
from flask_cors import CORS
import mysql.connector

app = Flask(__name__)
CORS(app)


# MySQL Connection
db = mysql.connector.connect(
    host='localhost',
    user='root',
    password='',
    database='sentiment_dashboard'
)

def analyze_sentiment(review):
    positive_words = ['good', 'great', 'excellent', 'amazing', 'positive', 'love', 'like']
    negative_words = ['bad', 'terrible', 'awful', 'horrible', 'negative', 'hate', 'not good']

    sentiment_score = 0
    words = review.lower().split()
    for word in words:
        if word in positive_words:
            sentiment_score += 1
        elif word in negative_words:
            sentiment_score -= 1

    return 'positive' if sentiment_score > 0 else 'negative' if sentiment_score < 0 else 'neutral'

@app.route('/api/reviews', methods=['POST'])
def post_review():
    cursor = db.cursor()
    review_data = request.json
    text = review_data['text']
    imdb_id = review_data['imdb_id'] 
    sentiment = analyze_sentiment(text)

    sql = 'INSERT INTO reviews (text, sentiment, imdb_id, likes, dislikes) VALUES (%s, %s, %s, %s, %s)'
    cursor.execute(sql, (text, sentiment, imdb_id, 0, 0))
    db.commit()

    review_id = cursor.lastrowid
    cursor.close()  

    return jsonify({'id': review_id, 'text': text, 'sentiment': sentiment, 'likes': 0, 'dislikes': 0}), 201

@app.route('/api/reviews', methods=['GET'])
def get_reviews():
    cursor = db.cursor()

    cursor.execute('SELECT id, text, sentiment, likes, dislikes FROM reviews WHERE imdb_id = %s ORDER BY created_at DESC', (imdb_id))
    results = cursor.fetchall()
    cursor.close()  

    reviews = [{'id': row[0], 'text': row[1], 'sentiment': row[2], 'likes': row[3], 'dislikes': row[4]} for row in results]
    return jsonify(reviews)

@app.route('/api/reviews/<int:review_id>/like', methods=['POST'])
def like_review(review_id):
    cursor = db.cursor()
    cursor.execute('UPDATE reviews SET likes = likes + 1 WHERE id = %s', (review_id,))
    db.commit()
    cursor.close()
    return '', 204

@app.route('/api/reviews/<int:review_id>/dislike', methods=['POST'])
def dislike_review(review_id):
    cursor = db.cursor()
    cursor.execute('UPDATE reviews SET dislikes = dislikes + 1 WHERE id = %s', (review_id,))
    db.commit()
    cursor.close()
    return '', 204


if __name__ == '__main__':
    app.run(debug=True, port=5000)
