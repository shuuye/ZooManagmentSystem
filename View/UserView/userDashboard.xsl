<!--Author Name: Chew Wei Seng-->
<?xml version="1.0" encoding="UTF-8"?>

<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
    <xsl:output method="html"/>
    <!-- Pass the current date from PHP -->
    <xsl:param name="currentDate" />
    
    <xsl:template match="/">
        <!-- Extract the current year and month from the parameter -->
        <xsl:variable name="currentYear" select="substring($currentDate, 1, 4)" />
        <xsl:variable name="currentMonth" select="substring($currentDate, 6, 2)" />
        
        <!-- Initialize counts -->
        <xsl:variable name="totalUsers" select="count(users/user)" />
        <xsl:variable name="totalAdmins" select="count(users/user[roleID = 1])" />
        <xsl:variable name="totalCustomers" select="count(users/user[roleID = 2])" />
        <xsl:variable name="totalStaff" select="count(users/user[roleID = 3])" />
        
        <!-- Count active users -->
        <xsl:variable name="activeUsers" select="count(users/user[substring(lastLoginDateTime, 1, 7) = concat($currentYear, '-', $currentMonth)])" />
        
        <!-- Count new users -->
        <xsl:variable name="newUsers" select="count(users/user[substring(registrationDateTime, 1, 7) = concat($currentYear, '-', $currentMonth)])" />
        
        <table class="userDashboard" style="margin: 10px auto;">
            <h1 style="margin-left: 5%; margin-top: 50px;">User Dashboard</h1>
            <tr>
                <td style="text-align: center; padding-left: 30px;"><strong>Total Users:</strong></td>
                <td style="text-align: center; padding-left: 30px;"><strong>Total Admins:</strong></td>
                <td style="text-align: center; padding-left: 30px;"><strong>Total Customers:</strong></td>
                <td style="text-align: center; padding-left: 30px;"><strong>Total Staff:</strong></td>
                <td style="text-align: center; padding-left: 30px;"><strong>Active Users:</strong></td>
                <td style="text-align: center; padding-left: 30px;"><strong>New Users:</strong></td>
            </tr>
            <tr>
                <td style="text-align: center; padding-left: 30px;"><xsl:value-of select="$totalUsers" /></td>
                <td style="text-align: center; padding-left: 30px;"><xsl:value-of select="$totalAdmins" /></td>
                <td style="text-align: center; padding-left: 30px;"><xsl:value-of select="$totalCustomers" /></td>
                <td style="text-align: center; padding-left: 30px;"><xsl:value-of select="$totalStaff" /></td>
                <td style="text-align: center; padding-left: 30px;"><xsl:value-of select="$activeUsers" /></td>
                <td style="text-align: center; padding-left: 30px;"><xsl:value-of select="$newUsers" /></td>
            </tr>
        </table>
    </xsl:template>
</xsl:stylesheet>
