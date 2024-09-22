from flask import Flask, jsonify, request
import mysql.connector
from flask_cors import CORS

# Initialize the Flask app
app = Flask(__name__)
CORS(app)

# MySQL database connection
def get_db_connection():
    return mysql.connector.connect(
        host="localhost",
        user="alibaba",
        password="alibaba123",
        database="zooManagementdb"
    )

# Route to get visitors summary by date
@app.route('/visitor_summary', methods=['GET'])
def visitor_summary():
    visit_date = request.args.get('visit_date')

    if not visit_date:
        return jsonify({"error": "Visit date is required."}), 400

    try:
        conn = get_db_connection()
        cursor = conn.cursor(dictionary=True)

        # Query to join ticket_purchases with tickets to get type
        query = """
        SELECT tp.ticket_id, t.type, SUM(tp.quantity) AS total_quantity, tp.visit_date
        FROM ticket_purchases tp
        JOIN tickets t ON tp.ticket_id = t.id
        WHERE tp.visit_date = %s
        GROUP BY tp.ticket_id, t.type, tp.visit_date
        """
        cursor.execute(query, (visit_date,))
        result = cursor.fetchall()

        if not result:
            return jsonify({
                "visit_date": visit_date,
                "total_visitors": 0,
                "visitors": []
            })

        total_visitors = sum([row['total_quantity'] for row in result])

        return jsonify({
            "visit_date": visit_date,
            "total_visitors": total_visitors,
            "visitors": result
        })

    except mysql.connector.Error as err:
        return jsonify({"error": str(err)}), 500
    finally:
        if conn.is_connected():
            cursor.close()
            conn.close()

# Run the Flask app
if __name__ == '__main__':
    app.run(debug=True)
