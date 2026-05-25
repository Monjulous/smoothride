<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Email Template</title>
    <style type="text/css">
        /* Reset styles */
        body,
        #bodyTable,
        #bodyCell {
            height: 100% !important;
            margin: 0;
            padding: 0;
            width: 100% !important;
        }

        table {
            border-collapse: collapse;
        }

        img,
        a img {
            border: 0;
            outline: none;
            text-decoration: none;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            margin: 0;
            padding: 0;
        }

        p {
            margin: 1em 0;
            padding: 0;
        }

        /* Email wrapper */
        #outlook a {
            padding: 0;
        }

        .ReadMsgBody {
            width: 100%;
        }

        .ExternalClass {
            width: 100%;
        }

        .backgroundTable {
            margin: 0 auto;
            padding: 0;
            width: 100% !important;
        }

        /* Responsive */
        @media screen and (max-width: 600px) {
            table[class="fluid"],
            table[class="fluid"] td,
            table[class="fluid"] img {
                width: 100% !important;
            }
        }

        /* Your styles */
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            color: #333;
            background-color: #f2f2f2;
        }

        h1 {
            font-size: 28px;
            font-weight: bold;
            color: #444;
            margin: 20px 0;
        }

        p {
            font-size: 16px;
            line-height: 1.5em;
            margin-bottom: 20px;
        }

        a {
            color: #0066cc;
        }

        /* Company logo */
        .logo {
            display: inline-block;
            vertical-align: middle;
            max-width: 200px;
        }

        /* Company name */
        .company-name {
            display: inline-block;
            vertical-align: middle;
            font-size: 20px;
            font-weight: bold;
            margin-left: 10px;
        }
    </style>
</head>
<body>
    <table border="0" cellpadding="0" cellspacing="0" width="100%" id="bodyTable">
        <tr>
            <td align="center" valign="top" id="bodyCell">
                <!-- Email wrapper -->
                <table border="0" cellpadding="0" cellspacing="0" width="600" class="backgroundTable">
                    <tr>
                        <td align="center" valign="top">
                            <!-- Header -->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#ffffff">
                                <tr>
                                    <td align="center" valign="top" style="padding: 20px;">
                                        <img src="{{ url('images/logo.png') }}" alt="Company Logo" class="logo">
                                        <span class="company-name">Caco</span>
                                    </td>
                                </tr>
                            </table>
                            <!-- Content -->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#ffffff">
                                                        <tr>
                                <td align="left" valign="top" style="padding: 20px;">
                                    <h1>Document verification</h1>
                                     <h3>Dear {{@$user['name']}} </h3>
                                    <p>Your document has been expired and you can not add rides please reupload your document</p>
                                    <p>Regards,<br>Smooth Ride</p>
                                </td>
                            </tr>
                        </table>
                        <!-- Footer -->
                        <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#ffffff">
                            <tr>
                                <td align="center" valign="top" style="padding: 20px;">
                                    <p>Copyright © 2025 Smooth Ride</p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>

                               
