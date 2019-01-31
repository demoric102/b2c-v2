<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>CRC Credit Bureau Credit Score Report</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="main.css" />
    <style>
        body {
            width: 80%;
            margin: 0 auto;
            position: relative;
            padding:10px;
            font-family: "Helvetica Neue Light", "HelveticaNeue-Light", "Helvetica Neue", sans-serif;
            color: #000;
            font-size: 12px;
        }
        #crc-logo {
            position: absolute;
            right: 10px;
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
            font-size: 9px;
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
<img src="images/logo.png" id="crc-logo"><br/><br/>
<h3 id="title">Credit Score Report</h3>
<div class="caption">SUBJECT INFORMATION</div><br>
<table class="table" width="100%" style="">
    <tr>
        <th>NAME</th>
        <td>{{$data[0]['customer_name']}}</td>
    </tr>
</table>
<table class="table table-bordered" width="100%" style="padding: 5px;">
    <tr>
        <th>GENDER</th>
        <td>{{$data[0]['gender']}}</td>
        <td style="border-top:3px solid #f5f5f5">&nbsp;</td>
        <th>BVN</th>
        <td>{{$data[0]['bvn']}}</td>
    </tr>
    <tr>
        <th>MOBILE NO</th>
        <td>{{$data[0]['phone']}}</td>
        <td style="border-top:3px solid #f5f5f5">&nbsp;</td>
        <th>EMAIL</th>
        <td></td>
    </tr>
    <tr>
        <th>DATE OF BIRTH</th>
        <td>{{ date('d-m-Y',strtotime($data[0]['date_of_birth'])) }}</td>
        <td style="border-top:3px solid #f5f5f5;border-bottom:3px solid #f5f5f5">&nbsp;</td>
        <th>REQUESTED DATE</th>
        <td>{{ strtoupper(date('d-M-Y', time())) }} </td>
    </tr>
</table>
<div class="caption">CREDIT SCORE data</div><br />
<table style="width:95%;" height="500px">
    <tr style="margin:10px;border:2px solid #f5f5f5;line-height:10px">
        <td style="border-bottom:1px solid #f5f5f5;text-align:center;" width="33%">Your Credit Score is</td>
        <td style="line-height:10px;text-align:center; border-bottom:1px solid #f5f5f5" width="33%">Your credit score rating is:</td>
        <td rowspan="2" style="vertical-align: left" width="33%">
            <table style="border:1px solid #f5f5f5;width:160px;height:100px;padding-top:7px;vertical-align:middle;background:transparent;"cellpadding="1px">
                <tr>
                    <td class="baby" width="40px"><div style="width:20px; height:15px; background-color:#ff0000"></div></td>
                    <td class="baby" width="60px">300 – 499</td>
                    <td class="baby" width="60px">POOR</td>
                </tr>
                <tr>
                    <td class="baby" width="40px"><div style="width:20px; height:15px; background-color:#f3d012"></div></td>
                    <td class="baby" width="60px">500 – 649</td>
                    <td class="baby" width="60px">AVERAGE</td>
                </tr>
                <tr>
                    <td class="baby" width="40px"><div style="width:20px;height:15px;background-color:#b2d115"></div></td>
                    <td class="baby" width="60px">650 – 749</td>
                    <td class="baby" width="60px">GOOD</td>
                </tr>
                <tr>
                    <td class="baby" width="40px"><div style="width:20px;height:15px; background-color:#009600"></div></td>
                    <td class="baby" width="60px">750 – 850</td>
                    <td class="baby" width="60px">EXCELLENT</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr style="height:120px">
        <th class="score" style="">{{$data[0]['score']}}</th>
        <td id="rating">
            @if( ($data[0]['score'] > 300) && ($data[0]['score'] < 500))
                POOR

            @elseif( ($data[0]['score'] > 499) && ($data[0]['score'] < 650))
                AVERAGE

            @elseif( ($data[0]['score'] > 649) && ($data[0]['score'] < 749))
                GOOD

            @elseif( ($data[0]['score'] > 749) && ($data[0]['score'] < 850))
                EXCELLENT

            @endif
        </td>
    </tr>
</table>
<br />
<div class="caption">REASONS FOR YOUR CREDIT REPORT SCORE</div>
<table class="table table-bordered" style="width:100%">
    <tr>
        <td class="reasonlist">i</td>
        <td>{{$data[0]['reasoncode1']}}</td>
    </tr>
    <tr>
        <td class="reasonlist">ii</td>
        <td>{{$data[0]['reasoncode2']}}</td>
    </tr>
    <tr>
        <td class="reasonlist">iii</td>
        <td>{{$data[0]['reasoncode3']}}</td>
    </tr>
    <tr>
        <td class="reasonlist">iv</td>
        <td>{{$data[0]['reasoncode4']}}</td>
    </tr>
</table><br />
<div class="caption">DISCLAIMER</div><br />
<div id="disclaimer">The information contained in this report has been compiled based on the data provided by members of CRC Nigeria and does not represent the opinion of CRC Nigeria on the credit worthiness or suitability of the subject(s) for any credit facility. Such info usually is not the product of an independent investigation prompted by each member inquiry but is updated and revised on a periodic basis.<br/><br/>
    CRC Nigeria does not and cannot guarantee or give any warranty (in so far as such warranties may be excluded under any relevant law) in respect of the correctness, completeness, currency, merchantability or fitness of the information for any particular purpose. CRC Nigeria shall not be liable, and hereby denies liability, for any losses or damage that may result from use of this report as a consequence of any inaccuracies in, or any omissions from, the information which it may contain.<br /><br/>
    The information contained in this report is supplied on a strictly confidential basis to you and shall not be disclosed to any other person. The information contained in this report shall be used only for the permissible purpose as defined from time to time by the Central Bank of Nigeria or any other government agency authorized in that regard.</div>
</body>
</html>