from flask import Flask, request, jsonify, send_file
import qrcode
from io import BytesIO
import mysql.connector

app = Flask(__name__)

# Database configuration
db_config = {
    'host': 'localhost',
    'user': 'alibaba',
    'password': 'alibaba123',
    'database': 'zooManagementdb'
}

@app.route('/generate_qr', methods=['POST'])
def generate_qr():
    bookingid = request.form.get('bookingid')
    customerid = request.form.get('customerid')
    event_type = request.form.get('type')  # Added parameter for event type
    
    if not bookingid or not customerid or not event_type:
        return jsonify({'error': 'Booking ID, Customer ID, and Event Type are required'}), 400
    
    try:
        # Connect to the database
        conn = mysql.connector.connect(**db_config)
        cursor = conn.cursor(dictionary=True)
        
        # Prepare SQL queries based on event type
        if event_type == 'Public':
            query = "SELECT * FROM publiceventbooking WHERE bookingid = %s AND customerid = %s"
        elif event_type == 'Private':
            query = "SELECT * FROM privateeventbooking WHERE bookingid = %s AND customerid = %s"
        else:
            return jsonify({'error': 'Unknown event type'}), 400
        
        cursor.execute(query, (bookingid, customerid))
        event = cursor.fetchone()
        
        if not event:
            return jsonify({'error': 'No event found for the provided booking ID and Event Type'}), 404
        
        # Generate QR code data based on event type
        qr_data = ""
        
        if event_type == 'Public':
            qr_data = (f"Public Event\n"
                       f"Title: {event.get('title', 'N/A')}\n"
                       f"Location: {event.get('location', 'N/A')}\n"
		       f"Date: {event.get('date', 'N/A')}\n"
		       f"Start Time: {event.get('starttime', 'N/A')}\n"
                       f"End Time: {event.get('endtime', 'N/A')}\n"
		       f"Price per ticket: {event.get('price', 'N/A')}\n"
		       f"Ticket Number: {event.get('ticket_number', 'N/A')}\n"
		       f"Total Price: {event.get('totalprice', 'N/A')}\n")                  
        elif event_type == 'Private':
            qr_data = (f"Private Event\n"
                       f"Title: {event.get('title', 'N/A')}\n"
                       f"Location: {event.get('location', 'N/A')}\n"
                       f"Date: {event.get('date', 'N/A')}\n"
                       f"Start Time: {event.get('starttime', 'N/A')}\n"
		       f"End Time: {event.get('endtime', 'N/A')}\n"
		       f"Number of Attendees: {event.get('numberofattendees', 'N/A')}\n"
                       f"Deposit: {event.get('deposit', 'N/A')}\n")
        
        # Generate QR code
        qr = qrcode.QRCode(version=1, box_size=10, border=4)
        qr.add_data(qr_data)
        qr.make(fit=True)
        
        img = qr.make_image(fill='black', back_color='white')
        buf = BytesIO()
        img.save(buf)
        buf.seek(0)
        
        return send_file(buf, mimetype='image/png')

    except mysql.connector.Error as err:
        return jsonify({'error': str(err)}), 500
    finally:
        cursor.close()
        conn.close()

if __name__ == '__main__':
    app.run(host='127.0.0.1', port=5000, debug=True)
