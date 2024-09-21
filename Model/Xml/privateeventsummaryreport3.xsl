<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

    <!-- Key for grouping by location -->
    <xsl:key name="locationKey" match="Booking" use="location" />

    <xsl:template match="/">
        <html>
            <head>
                <title>Bookings by Location</title>
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
                
                <h2>Private Event Summary Report - Bookings Analysis by Location</h2>
                <table>
                    <tr>
                        <th>Location</th>
                        <th>Number of Bookings</th>
                    </tr>

                    <!-- Group by location and count the number of bookings -->
                    <xsl:for-each select="Bookings/Booking[generate-id() = generate-id(key('locationKey', location)[1])]">
                        <tr>
                            <td><xsl:value-of select="location" /></td>
                            <td>
                                <xsl:value-of select="count(key('locationKey', location))" />
                            </td>
                        </tr>
                    </xsl:for-each>
                </table>
            </body>
        </html>
    </xsl:template>

</xsl:stylesheet>
