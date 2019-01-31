
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>CRC Credit Bureau Credit Score Report</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="main.css" />
    <style>
        body {
            width: 60%;
            margin: 0 auto;
            position: relative;
            padding:10px;
            font-family: "Futura Bk BT";
            /* font-family: "Helvetica Neue Light", "HelveticaNeue-Light", "Helvetica Neue", sans-serif; */
            color: #000;
            font-size: 12px;
        }
        #crc-logo {
            position: absolute;
            right: 10px;
            width:200px;
            height:30px;
        }
        #crc-logo2 {
            float: right;
            left: 10px;
            width:200px;
            height:30px;
        }
        #title{
            text-align:center;color:#003296;text-decoration:underline;font-size:2em;
        }
        .caption {
            background: #000066;
            padding: 5px 10px;
            color: #fff;
            font-size: 16px;
            font-weight:bold;
            text-transform: uppercase;
            text-align: center;
        }
        table, td {
            margin-bottom: 10px;
        }
        table, th, td {
            border: 1px ridge #003296;
            border-collapse: collapse;
            height:10px;
            padding-left:5px;
            background-color:#f5f5f5;
        }
        th {
            text-align: left;
            color: #000;
        }
        #score-summary th, #score-summary td {
            text-align: center;
        }
        #disclaimer {
            text-align: justify;
            font-size: 11px;
        }
        .score {
            font-size: 5em;
            text-align: center;
            color: #000;
            vertical-align: top;
            border-top:1px solid #f5f5f5;
        }
        .reasonlist{
            text-align:center;
            width:60px;
            font-size:18px;
        }
        #rating{
            text-transform:uppercase;
            font-weight:bold;
            font-size:30px;
            vertical-align:top;
            text-align:center;
            padding:0px;
        }
        .baby{
            font-size:9px;
            border:1px solid #f5f5f5;
            padding:6px;
        }
    </style>
</head>
<body>
<img src="{{asset('public_path()./images/logo.png')}}" id="crc-logo">
<img src="{{asset('/images/crc_credit_score_logo.jpg')}}" id="crc-logo2">
<br/><br/>
<div class="caption">CRC Credit Bureau Limited Credit Information Report</div><br>
<table class="table" width="100%" style="">
    <tr>
        <td>Dear Customer, <br />
        The report you requested is attached in this email. <br /> 
        Kindly download it to view.
        </td>
    </tr>
</table>
<br />
<div class="caption">Support</div><br />
<div id="disclaimer">For further enquiries please send an email to support@crccreditbureau.com or call +234 (0) 8072090622.</div>
</body>
</html>