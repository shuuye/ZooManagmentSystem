<?xml version="1.0" encoding="UTF-8"?> 
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
    <xsl:output method="html"/>
    <!-- Pass the current ID from PHP -->
    <xsl:param name="ownId" />

    <xsl:template match="/">
        <!-- Initialize variables to store counts -->
        <xsl:variable name="totalPresent" select="count(attendances/attendance[@id = $ownId and statusID = 2])" />
        <xsl:variable name="totalAbsent" select="count(attendances/attendance[@id = $ownId and statusID = 3])" />
        <xsl:variable name="totalLeave" select="count(attendances/attendance[@id = $ownId and statusID = 4])" />
        
        <!-- Total attendance count (Present, Absent, Leave) -->
        <xsl:variable name="totalAttendance" select="$totalPresent + $totalAbsent + $totalLeave" />
        
        <!-- Calculate total percentage for Present and Leave -->
        <xsl:variable name="totalPercentage" select="( ($totalPresent + $totalLeave) div $totalAttendance) * 100" />

        <table style="margin: 50px auto;">
            <tr>
                <th style="color: green; text-align:center; padding-right: 20px;">Present:</th>
                <th style="color: red; text-align:center; padding-right: 20px;">Absent:</th>
                <th style="color: yellow; text-align:center; padding-right: 20px;">Leave:</th>
                <th style="text-align:center;">Total Percentage:</th> 
            </tr>
            <tr>
                <!-- Display the counts for Present, Absent, Leave -->
                <td style="color: green; text-align:center; padding-right: 20px;">
                    <xsl:value-of select="$totalPresent" />
                </td>
                <td style="color: red; text-align:center; padding-right: 20px;">
                    <xsl:value-of select="$totalAbsent" />
                </td>
                <td style="color: yellow; text-align:center; padding-right: 20px;">
                    <xsl:value-of select="$totalLeave" />
                </td>
                
                <!-- Conditionally display the total percentage in different colors -->
                <td style="text-align:center;">
                    <xsl:choose>
                        <!-- If totalPercentage is below 80, print in red -->
                        <xsl:when test="$totalPercentage &lt; 80">
                            <span style="color: red;">
                                <xsl:value-of select="format-number($totalPercentage, '0.00')" />%
                            </span>
                        </xsl:when>
                        <!-- If totalPercentage is 80 or above, print in green -->
                        <xsl:otherwise>
                            <span style="color: green;">
                                <xsl:value-of select="format-number($totalPercentage, '0.00')" />%
                            </span>
                        </xsl:otherwise>
                    </xsl:choose>
                </td>
            </tr>
        </table>
    </xsl:template>
</xsl:stylesheet>
