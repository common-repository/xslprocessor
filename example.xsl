<?xml version="1.0" encoding="iso-8859-1"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
  <xsl:param name="default_param" select="'You have to override this parameter'"/>
  <xsl:template match="/resume">
    <table>
      <tr>
        <td>
          Author: <xsl:value-of select="name"/>
        </td>
        <td>
          E-mail: <xsl:value-of select="email"/>
        </td>
      </tr>
	  <tr>
		<td colspan="2">
			Parameter test: <xsl:value-of select="$default_param"/>
		</td>	  
	  </tr>
    </table>
  </xsl:template>
</xsl:stylesheet>