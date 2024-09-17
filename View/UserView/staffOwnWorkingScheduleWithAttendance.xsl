<?xml version="1.0" encoding="UTF-8"?> 
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
    <xsl:output method="html"/>
    <!-- Pass the current ID from PHP -->
    <xsl:param name="ownId" />

    <xsl:template match="/">
        <table class="userManagementTable" border="1">
            <tr>
                <th>Working Date</th>
                <th>Working Starting Time</th>
                <th>Working Off Time</th>
                <th>Status</th> 
            </tr>

            <!-- Loop through each attendance where the id matches ownId -->
            <xsl:for-each select="attendances/attendance[@id = $ownId]">
                <tr>
                    <!-- Display workingDate, workingStartingTime, workingOffTime -->
                    <td><xsl:value-of select="workingDate" /></td>
                    <td><xsl:value-of select="workingStartingTime" /></td>
                    <td><xsl:value-of select="workingOffTime" /></td>
                    
                    <!-- Determine and display status based on statusID -->
                    <td>
                        <xsl:choose>
                            <xsl:when test="statusID = 1">
                                <span style="color: blue;">Pending</span>
                            </xsl:when>
                            <xsl:when test="statusID = 2">
                                <span style="color: green;">Present</span>
                            </xsl:when>
                            <xsl:when test="statusID = 3">
                                <span style="color: red;">Absent</span>
                            </xsl:when>
                            <xsl:when test="statusID = 4">
                                <span style="color: yellow;">Leave</span>
                            </xsl:when>
                            <xsl:otherwise>
                                <span>Unknown</span>
                            </xsl:otherwise>
                        </xsl:choose>
                    </td>
                </tr>
            </xsl:for-each>
        </table>
    </xsl:template>
</xsl:stylesheet>
