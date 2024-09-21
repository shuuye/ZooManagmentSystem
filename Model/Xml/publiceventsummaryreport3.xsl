<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

    <!-- Key for grouping by month and year -->
    <xsl:key name="monthYearKey" match="Booking" use="substring(date, 1, 7)" />

    <xsl:template match="/">
        <html>
            <head>
                <title>Event Booking Trend Report</title>
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
                <a class="back-button" href="javascript:history.back()">Back</a>
                <h2>Public Event Summary Report - Monthly Analysis for Bookings</h2>
                <table>
                    <tr>
                        <th>Month</th>
                        <th>Event Type</th>
                        <th>Total Bookings</th>
                        <th>Total Revenue</th>
                    </tr>

                    <!-- Group by month and year -->
                    <xsl:for-each select="Bookings/Booking[generate-id() = generate-id(key('monthYearKey', substring(date, 1, 7))[1])]">
                        <xsl:variable name="monthYear" select="substring(date, 1, 7)" />
                        <xsl:variable name="year" select="substring($monthYear, 1, 4)" />
                        <xsl:variable name="monthNum" select="substring($monthYear, 6, 2)" />
                        <xsl:variable name="totalBookings" select="count(key('monthYearKey', $monthYear))" />
                        <xsl:variable name="totalRevenue" select="sum(key('monthYearKey', $monthYear)/totalprice)" />
                        <tr>
                            <td>
                                <!-- Convert month number to month name -->
                                <xsl:choose>
                                    <xsl:when test="$monthNum = '01'">January</xsl:when>
                                    <xsl:when test="$monthNum = '02'">February</xsl:when>
                                    <xsl:when test="$monthNum = '03'">March</xsl:when>
                                    <xsl:when test="$monthNum = '04'">April</xsl:when>
                                    <xsl:when test="$monthNum = '05'">May</xsl:when>
                                    <xsl:when test="$monthNum = '06'">June</xsl:when>
                                    <xsl:when test="$monthNum = '07'">July</xsl:when>
                                    <xsl:when test="$monthNum = '08'">August</xsl:when>
                                    <xsl:when test="$monthNum = '09'">September</xsl:when>
                                    <xsl:when test="$monthNum = '10'">October</xsl:when>
                                    <xsl:when test="$monthNum = '11'">November</xsl:when>
                                    <xsl:when test="$monthNum = '12'">December</xsl:when>
                                </xsl:choose>
                                <xsl:text> </xsl:text>
                                <xsl:value-of select="$year"/>
                            </td>
                            <td><xsl:value-of select="type" /></td>
                            <td><xsl:value-of select="$totalBookings" /></td>
                            <td><xsl:value-of select="$totalRevenue" /></td>
                        </tr>
                    </xsl:for-each>
                </table>
            </body>
        </html>
    </xsl:template>
</xsl:stylesheet>
