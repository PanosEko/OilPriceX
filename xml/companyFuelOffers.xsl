<?xml version="1.0" encoding="UTF-8"?>
<!-- XSLT stylesheet to transform company_fuel_offers.xml to HTML -->
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <!-- Set output method to html and indent the output -->
    <xsl:output method="html" indent="yes"/>

    <xsl:template match="/">
        <html>
            <head>
                <title>Προσφορές</title>
                <style>
                    body {
                    text-align: center;
                    font-family: Arial, sans-serif;
                    }
                    h1, h2 {
                    margin-top: 40px;
                    }
                    table {
                    margin: 40px auto;
                    border-collapse: collapse;
                    width: 80%;
                    }
                    th {
                    background-color: #3ca3ff;
                    border: 1px solid #ddd;
                    padding: 8px;
                    text-align: left;
                    }
                    td {
                    border: 1px solid #ddd;
                    padding: 8px;
                    text-align: left;
                    }
                    tr:nth-child(even) {
                    background-color: #f2f2f2;
                    }
                </style>
            </head>
            <body>
                <!-- Count the number of offers -->
                <h2>Πλήθος προσφορών: <xsl:value-of select="count(company_fuel_offers/fuel_offers/offer)"/> </h2>
                <h2>Ανάλυση προσφορών: </h2>
                <table>
                    <tr>
                        <th>Τύπος καυσίμων</th>
                        <th>Ημ/νία λήξης προσφοράς</th>
                        <th>Τιμή προσφοράς</th>
                        <th>Ενεργή προσφορά</th>
                    </tr>
                    <xsl:apply-templates select="company_fuel_offers/fuel_offers/offer"/>
                </table>
                <xsl:apply-templates select="company_fuel_offers/company_info"/>
            </body>
        </html>
    </xsl:template>
    <!-- Match each the offer element -->
    <xsl:template match="offer">
        <tr>
            <td><xsl:value-of select="fuel_type"/></td>
            <td><xsl:value-of select="end_date"/></td>
            <td><xsl:value-of select="price"/></td>
            <td>
                <!-- Set offer state to "Ενεργή" if active attribute is True or "Ανένεργη" if it isnt -->
                <xsl:choose>
                    <xsl:when test="active='True'">Ενεργή</xsl:when>
                    <xsl:otherwise>Ανενεργή</xsl:otherwise>
                </xsl:choose>
            </td>
        </tr>
    </xsl:template>

    <!-- Match the company_info element -->
    <xsl:template match="company_info">
        <h2>Στοιχεία Επιχείρησης</h2>
        <p><strong>Επωνυμία:</strong> <xsl:value-of select="name"/></p>
        <p><strong>Α.Φ.Μ.:</strong> <xsl:value-of select="tax_id"/></p>
        <p>
            <strong>Διεύθυνση:</strong>
            <xsl:value-of select="address/street"/>,
            <xsl:value-of select="address/municipality"/>,
            <xsl:value-of select="address/prefecture"/>
        </p>
    </xsl:template>

</xsl:stylesheet>

