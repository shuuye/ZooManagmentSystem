import requests
import matplotlib.pyplot as plt
import matplotlib.dates as mdates
from datetime import datetime

# Define your PHP web service URL
url = 'http://localhost/ZooManagementSystem/public/EventWebServices/get_user_events.php'

# Define the parameters for the request
params = {
    'customerid': 15,  # Replace with actual customer ID
    'start_date': '2024-09-01',  # Replace with actual start date
    'end_date': '2024-09-30'  # Replace with actual end date
}
def parse_datetime(date_str, time_str=None):
    if time_str is None:
        return datetime.strptime(date_str, '%Y-%m-%d')
    return datetime.strptime(f"{date_str} {time_str}", '%Y-%m-%d %H:%M:%S')

try:
    # Make the GET request to the PHP web service
    response = requests.get(url, params=params)
    
    # Check if the request was successful
    if response.status_code == 200:
        print("Request successful")
        
        # Parse the JSON response
        data = response.json()
        
        # Check if there's an error in the response
        if 'error' in data:
            print("Error:", data['error'])
        else:
            # Prepare data for plotting
            events = []
            
            for event in data:
                start_date = parse_datetime(event['date'], event.get('starttime'))
                end_date = parse_datetime(event['date'], event.get('endtime', '23:59:59'))
                location = event.get('location', 'No Location')
                events.append((event['event_id'], start_date, end_date, event['title'], location))
            
            # Plotting the calendar view
            fig, ax = plt.subplots(figsize=(14, 10))
            ax.xaxis.set_major_locator(mdates.MonthLocator())
            ax.xaxis.set_minor_locator(mdates.WeekdayLocator())
            ax.xaxis.set_major_formatter(mdates.DateFormatter('%B %Y'))
            
            # Plot each event as a bar and annotate with details
            for event_id, start_date, end_date, title, location in events:
                bar = ax.barh(title, (end_date - start_date).days, left=start_date, height=0.4,
                              color='skyblue', edgecolor='black')
                
                # Annotate with a small box showing event details
                annotation_text = (
                    f"ID: {event_id}\n"
                    f"Title: {title}\n"
                    f"Start: {start_date.strftime('%Y-%m-%d %H:%M:%S')}\n"
                    f"End: {end_date.strftime('%Y-%m-%d %H:%M:%S')}\n"
                    f"Location: {location}"
                )
                # Ensure the annotation box is displayed within the bounds of the bar
                ax.text(start_date + (end_date - start_date) / 2, title,
                        annotation_text,
                        verticalalignment='center', horizontalalignment='center',
                        fontsize=10, color='black',
                        bbox=dict(facecolor='lightyellow', edgecolor='black', boxstyle='round,pad=0.5'))

            # Format plot
            plt.title('Event Calendar')
            plt.xlabel('Date')
            plt.ylabel('Events')
            plt.grid(True, linestyle='--', alpha=0.7)
            plt.legend(loc='upper left', bbox_to_anchor=(1, 1), title='Events')
            plt.tight_layout()
            plt.show()

    else:
        print(f"Request failed with status code: {response.status_code}")
        print("Response content:", response.text)

except requests.RequestException as e:
    # Handle any errors that occur during the request
    print("An error occurred:", e)
