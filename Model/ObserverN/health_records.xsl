<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:template match="/">
        <html>
            <head>
                <title>Health Records</title>
                  <style>
                    .report-button {
                        margin-top: 20px;
                    }
                </style>
            </head>
            <body>
                <h2>Health Records</h2>
                <nav>
                    <ul>
                        <li><a href="../../View/AnimalView/animal_home.php">Animal Home</a></li>
                      
                    </ul>
                </nav>
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
                <a href="animal_health_report.html" target="_blank">
                    <button class="report-button">View Reports</button>
                </a>
                <a href="../../View/AnimalView/add_health.php"><button>Add New Health Record</button></a>
            </body>
        </html>
    </xsl:template>
</xsl:stylesheet>
