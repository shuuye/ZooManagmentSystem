#  To run the file
# http://127.0.0.1:5000/api/visitor_summary?visit_date=2024-09-20 (the date can change)

from flask import Flask, request, jsonify
import mysql.connector
from mysql.connector import Error
from collections import defaultdict

app = Flask(__name__)

def get_db_connection():
    try:
        conn = mysql.connector.connect(
            host='localhost',
            database='zooManagementdb',
            user='alibaba',
            password='alibaba123'
        )
        if conn.is_connected():
            return conn
    except Error as e:
        print("Error while connecting to MySQL", e)
        return None

def fetch_visitors_by_date(visit_date):
    conn = get_db_connection()
    if conn is None:
        return {"error": "Database connection failed."}
    
    cursor = conn.cursor(dictionary=True)
    query = """
        SELECT tp.ticket_id, tp.quantity, tp.visit_date, t.type
        FROM ticket_purchases tp
        JOIN tickets t ON tp.ticket_id = t.id
        WHERE tp.visit_date = %s
    """
    cursor.execute(query, (visit_date,))
    visitors = cursor.fetchall()
    
    cursor.close()
    conn.close()
    
    return visitors
    
    

@app.route('/api/visitor_summary', methods=['GET'])
def visitor_summary():
    visit_date = request.args.get('visit_date')
    if not visit_date:
        return jsonify({"error": "Visit date is required."}), 400
    
    visitors = fetch_visitors_by_date(visit_date)
    if "error" in visitors:
        return jsonify(visitors), 500

    # Calculate total visitors as the sum of all quantities
    total_visitors = sum(visitor['quantity'] for visitor in visitors)

    # Group by ticket_id and sum quantities
    ticket_summary = defaultdict(lambda: {"quantity": 0, "type": ""})
    for visitor in visitors:
        ticket_summary[visitor['ticket_id']]["quantity"] += visitor['quantity']
        ticket_summary[visitor['ticket_id']]["type"] = visitor['type']

    # Format the final response
    formatted_visitors = [
        {
            "ticket_id": ticket_id,
            "type": ticket_info["type"],
            "quantity": ticket_info["quantity"],
            "visit_date": visit_date
        }
        for ticket_id, ticket_info in ticket_summary.items()
    ]

    return jsonify({
        "total_visitors": total_visitors,
        "visit_date": visit_date,
        "visitors": formatted_visitors
    })

if __name__ == '__main__':
    app.run(debug=True)
