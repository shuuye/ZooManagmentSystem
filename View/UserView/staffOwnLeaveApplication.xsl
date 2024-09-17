<?xml version="1.0" encoding="UTF-8"?> 
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
    <xsl:output method="html"/>
    <!-- Pass the current ID from PHP -->
    <xsl:param name="ownId" />

    <xsl:template match="/">
        <table class="userManagementTable" border="1">
            <tr>
                <th>Leave Date</th>
                <th>Leave Start Time</th>
                <th>Leave End Time</th>
                <th>Reason</th>
                <th>Leave Approve Status</th> 
            </tr>

            <!-- Loop through each attendance where the id matches ownId -->
            <xsl:for-each select="leaveApplications/leaveApplication[@id = $ownId]">
                <tr>
                    <!-- Display workingDate, workingStartingTime, workingOffTime -->
                    <td><xsl:value-of select="leaveDate" /></td>
                    <td><xsl:value-of select="leaveStartTime" /></td>
                    <td><xsl:value-of select="leaveEndTime" /></td>
                    <td><xsl:value-of select="reason" /></td>
                    
                    <!-- Determine and display leave approve status based on approved (boolean) -->
                    <td>
                        <xsl:choose>
                            <xsl:when test="approved = 0">
                                <span style="color: red;">Not Yet Approved</span>
                            </xsl:when>
                            <xsl:when test="approved = 1">
                                <span style="color: green;">Approved</span>
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
