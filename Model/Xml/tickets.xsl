<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:output method="html" doctype-public="XSLT-compat"/>

    <xsl:template match="/">
        <html>
        <head>
            <title>Zoo Tickets</title>
        </head>
        <body>
            <h1>Zoo Tickets</h1>
            <table border="1">
                <tr>
                    <th>Ticket ID</th>
                    <th>Type</th>
                    <th>Description</th>
                    <th>Price (RM)</th>
                </tr>
                <xsl:for-each select="Tickets/Ticket">
                    <tr>
                        <td><xsl:value-of select="id"/></td>
                        <td><xsl:value-of select="type"/></td>
                        <td><xsl:value-of select="description"/></td>
                        <td><xsl:value-of select="price"/></td>
                    </tr>
                </xsl:for-each>
            </table>
        </body>
        </html>
    </xsl:template>
</xsl:stylesheet>
