<?xml version="1.0" encoding="UTF-8"?>

<!--
    Document   : health_records.xsl.xsl
    Created on : 29 August 2024, 10:44 am
    Author     : chaiy
    Description:
        Purpose of transformation follows.
-->

<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
    <xsl:output method="html"/>

    <!-- TODO customize transformation rules 
         syntax recommendation http://www.w3.org/TR/xslt 
    -->
<xsl:template match="/">
    <html>
    <body>
      <h2>Animal Health Records</h2>
      <table border="1">
        <tr>
          <th>ID</th>
          <th>Animal ID</th>
          <th>Last Checkup</th>
          <th>Treatments</th>
          <th>Condition</th>
        </tr>
        <xsl:for-each select="HealthRecords/HealthRecord">
          <tr>
            <td><xsl:value-of select="hRecord_id"/></td>
            <td><xsl:value-of select="animal_id"/></td>
            <td><xsl:value-of select="last_checkup"/></td>
            <td><xsl:value-of select="treatments"/></td>
            <td><xsl:value-of select="healthStatus"/></td>
          </tr>
        </xsl:for-each>
      </table>
    </body>
    </html>
  </xsl:template>

</xsl:stylesheet>
