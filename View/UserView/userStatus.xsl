<?xml version="1.0" encoding="UTF-8"?>

<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
    <xsl:output method="html"/>
    <!-- Pass the current date from PHP -->
    <xsl:param name="currentDate" />

    <xsl:template match="/">
        <!-- Extract the current year and month from the parameter -->
        <xsl:variable name="currentYear" select="substring($currentDate, 1, 4)" />
        <xsl:variable name="currentMonth" select="substring($currentDate, 6, 2)" />
        
        <table border="1" style="margin: 10px auto;">
            <h3 style="margin-left: 30%; margin-top: 80px;">Yearly User Status Report</h3>
            <tr>
                <th>User ID</th>
                <th>Username</th>
                <th>Full Name</th>
                <th>Phone Number</th>
                <th>Email</th>
                <th>Status</th>
            </tr>

            <!-- Loop through each user -->
            <xsl:for-each select="users/user">
                <!-- Extract the year and month from lastLoginDateTime and registrationDateTime -->
                <xsl:variable name="lastLoginYear" select="substring(lastLoginDateTime, 1, 4)" />
                <xsl:variable name="lastLoginMonth" select="substring(lastLoginDateTime, 6, 2)" />
                <xsl:variable name="registrationYear" select="substring(registrationDateTime, 1, 4)" />
                <xsl:variable name="registrationMonth" select="substring(registrationDateTime, 6, 2)" />

                <!-- Exclude rows where the last login or registration is older than a year -->
                <xsl:choose>
                    <xsl:when test="($lastLoginYear = $currentYear and $lastLoginMonth &gt;= $currentMonth) or ($registrationYear = $currentYear and $registrationMonth &gt;= $currentMonth)">
                        <tr>
                            <td><xsl:value-of select="@id" /></td>
                            <td><xsl:value-of select="username" /></td>
                            <td><xsl:value-of select="fullName" /></td>
                            <td><xsl:value-of select="phoneNumber" /></td>
                            <td><xsl:value-of select="email" /></td>

                            <!-- Status with conditional logic for Active/Inactive and New -->
                            <td>
                                <!-- Active/Inactive status -->
                                <xsl:choose>
                                    <xsl:when test="$lastLoginYear = $currentYear and $lastLoginMonth = $currentMonth">
                                        <span style="background-color: green; color: white; padding: 2px 5px; border-radius: 4px;">Active</span>
                                    </xsl:when>
                                    <xsl:otherwise>
                                        <span style="background-color: red; color: white; padding: 2px 5px; border-radius: 4px;">Inactive</span>
                                    </xsl:otherwise>
                                </xsl:choose>

                                <!-- New user status -->
                                <xsl:if test="$registrationYear = $currentYear and $registrationMonth = $currentMonth">
                                    <span style="background-color: yellow; color: black; padding: 2px 5px; border-radius: 4px; margin-left: 5px;">New</span>
                                </xsl:if>
                            </td>
                        </tr>
                    </xsl:when>
                </xsl:choose>
            </xsl:for-each>
        </table>
    </xsl:template>
</xsl:stylesheet>
