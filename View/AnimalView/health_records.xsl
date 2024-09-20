<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:template match="/">
        <html>
             <head>
             <title>Health Records</title>
             <link rel="stylesheet" type="text/css" href="../../Css/AnimalN/ani_Nav.css"/>
             <link rel="stylesheet" type="text/css" href="../../Css/AnimalN/health.css"/>
              </head>
              <body>
                <nav class="navigation">
                  <ul>
                    <li><a href="animal_home.php">Animal Home</a></li>
                    <li><a href="animal_health_report.html" target="_blank">View Reports</a></li>
                    <li><a href="add_health.php">Add | Update Health Record</a></li>
                    </ul>
                </nav>
                
                <h2>Health Records</h2>
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
               
            </body>
        </html>
    </xsl:template>
</xsl:stylesheet>
