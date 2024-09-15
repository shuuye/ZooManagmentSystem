<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:output method="html" doctype-public="XSLT-compat"/>
    <xsl:template match="/">
        <html>
        <head>
            <title>Your Ticket Purchases</title>
        </head>
        <body>
            <h1>Your Ticket Purchases</h1>
            <table border="1">
                <tr>
                    <th>Ticket ID</th>
                    <th>Ticket Type</th>
                    <th>Quantity</th>
                    <th>Price (RM)</th>
                    <th>Total (RM)</th>
                    <th>Visit Date</th>
                </tr>
                <xsl:for-each select="Purchases/Purchase">
                    <tr>
                        <td><xsl:value-of select="@ticket_id"/></td>
                        <td><xsl:value-of select="ticket_type"/></td>
                        <td><xsl:value-of select="quantity"/></td>
                        <td><xsl:value-of select="price"/></td>
                        <td><xsl:value-of select="total"/></td>
                        <td><xsl:value-of select="visit_date"/></td>
                    </tr>
                </xsl:for-each>
            </table>
        </body>
        </html>
    </xsl:template>
</xsl:stylesheet>
