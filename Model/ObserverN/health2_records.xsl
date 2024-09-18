<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:template match="/">
        <html>
            <head>
                <link rel="stylesheet" type="text/css" href="../../Css/AnimalN/health.css"/>
                <script type="text/javascript" src="../../View/AnimalView/health.js"></script>
                <title>Animal Health Reports</title>
            </head>
            <body>
                <h2>Animal Health in Warning</h2>
                <table border="1">
                    <tr>
                        <th>Health ID</th>
                        <th>Animal ID</th>
                        <th>Last Checkup</th>
                        <th>Treatments</th>
                        <th>Health Status</th>
                    </tr>
                    <xsl:for-each select="HealthRecords/HealthRecord">
                        <tr>
                            <td><xsl:value-of select="@hRecord_id"/></td>
                            <td><xsl:value-of select="animal_id"/></td>
                            <td><xsl:value-of select="last_checkup"/></td>
                            <td><xsl:value-of select="treatments"/></td>
                            <td><xsl:value-of select="healthStatus"/></td>
                        </tr>
                    </xsl:for-each>
                </table>
                <br/>
                <button type="button" onclick="confirmAndPrint()">Print Report</button>
            </body>
        </html>
    </xsl:template>
</xsl:stylesheet>
