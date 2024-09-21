<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

    <!-- Key for grouping by event_id -->
    <xsl:key name="eventIdKey" match="Booking" use="event_id" />

    <xsl:template match="/">
        <html>
            <head>
                <title>Event Summary Report</title>
                <style>
                    table {
                        width: 100%;
                        border-collapse: collapse;
                    }
                    table, th, td {
                        border: 1px solid black;
                    }
                    th, td {
                        padding: 8px;
                        text-align: center;
                    }
                    .back-button {
                        display: inline-block;
                        margin: 10px 0;
                        padding: 8px 16px;
                        background-color: #4CAF50;
                        color: white;
                        text-decoration: none;
                        font-weight: bold;
                        border-radius: 4px;
                        font-size: 14px;
                        text-align: center;
                    }
                    .back-button:hover {
                        background-color: #45a049;
                    }
                </style>
            </head>
            <body>
                <!-- Back button -->
                <a class="back-button" href="javascript:history.back()">Back</a>
                
                <h2>Public Event Summary Report - Total Ticket Sales for Each Event</h2>
                <table>
                    <tr>
                        <th>Event ID</th>
                        <th>Title</th>
                        <th>Event Type</th>
                        
                        <th>Total Ticket Sales</th>
                    </tr>

                    <!-- Group by event_id and sum up the total price -->
                    <xsl:for-each select="Bookings/Booking[generate-id() = generate-id(key('eventIdKey', event_id)[1])]">
                        <tr>
                            <td><xsl:value-of select="event_id" /></td>
                            <td><xsl:value-of select="title" /></td>
                            <td><xsl:value-of select="type" /></td>
                            <td>
                                <xsl:value-of select="sum(key('eventIdKey', event_id)/ticket_number)" />
                            </td>
                        </tr>
                    </xsl:for-each>
                </table>
            </body>
        </html>
    </xsl:template>

</xsl:stylesheet>
