<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <title>{{ $subject }}</title>
    <!--[if !mso]>-->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!--<![endif]-->
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style type="text/css">
        #outlook a {
            padding: 0;
        }

        h1,h2,h3,h4,h5,h6 {
            font-family: 'Tahoma', sans-serif !important;
            font-weight: 400 !important;
            line-height: 120%;
        }

        h1 {
            text-align: center !important;
            font-size: 24px !important;
            color: #fff !important;
        }

        h2 {
            color: #cbd5e1 !important;
            margin: 0 0 8px 0 !important;
            text-align: left !important;
            font-size: 20px !important;
        }

        h3 {
            color: #94a3b8 !important;
            margin: 0 0 4px 0 !important;
            text-align: left !important;
            font-size: 16px !important;
        }

        p {
            line-height: 150% !important;
            font-size: 14px !important;
            margin: 0 auto !important;
            font-weight: 300 !important;
            text-align: left !important;
            color:#e2e8f0 !important;
        }

        .btn {
            background: #df5900 !important;
            text-decoration: none !important;
            display: inline-block !important;
            padding: 4px 16px !important;
            font-family: 'Arial', sans-serif !important;
            font-size: 14px !important;
            font-weight: 700 !important;
            color: #ffffff !important;
            text-align: center !important;
            border-radius: 100px !important;
        }

        .link, p > a {
            color:#ff6600 !important;
        }

        .sign {
            color: #ffffff !important;
            font-weight: 500 !important;
            font-style: italic !important;
        }

        .ReadMsgBody {
            width: 100%;
        }

        .ExternalClass {
            width: 100%;
        }

        .ExternalClass * {
            line-height: 100%;
        }

        body {
            font-family: 'Tahoma', sans-serif !important;
            background: #64748b;
            color: #ffffff;
            margin: 0;
            padding: 16px;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }

        .header {
            text-align: center;
            font-size: 14px;
            border-bottom: 1px #ff6600 solid;
            padding: 16px
        }

        .main-content {
            padding: 24px !important;
        }

        .footer {
            text-align: center !important;
            font-size: 12px !important;
            border-top: 1px #ff6600 solid !important;
            padding: 24px !important;
        }

        table,
        td {
            padding: 16px 0;
            line-height: 150%;
            font-size: 14px;
            margin: 0 auto;
            font-weight: 300;
            text-align: left;
            color:#e2e8f0;
            border-collapse: collapse;
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }
    </style>
    <!--[if !mso]><!-->
    <style type="text/css">
        @media only screen and (max-width:480px) {
            @-ms-viewport {
                width: 320px;
            }

            @viewport {
                width: 320px;
            }
        }
    </style>
    <!--<![endif]-->
    <!--[if mso]><xml>  <o:OfficeDocumentSettings>    <o:AllowPNG/>    <o:PixelsPerInch>96</o:PixelsPerInch>  </o:OfficeDocumentSettings></xml><![endif]-->
    <!--[if lte mso 11]><style type="text/css">  .outlook-group-fix {    width:100% !important;  }</style><![endif]-->
    <!--[if !mso]><!-->
    <!--<![endif]-->
    <style type="text/css">
        @media only screen and (max-width:600px) {
            .container {
                width: 100% !important;
            }

            .button {
                display: block !important;
                width: auto !important;
            }
        }
    </style>
</head>

<body>
    <table width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
        <tbody>
            <tr>
                <td valign="top" align="center">
                    <table class="container" cellspacing="0" width="480" cellpadding="0" border="0" bgcolor="#0f172a" style="background-color: #0f172a;">
                        <tbody>
                            <tr>
                                <td class="header">
                                    <a href="{{ config('app.url') }}">
                                        <img src="{{ $message->embed(public_path() . '/img/logo/logo_horizontal.png') }}" alt="Logo" style="height: 24px" />
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td class="main-content">
                                    <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    @isset($greeting)
                                                        <h1 style="font-size: 32px;margin: 0; padding: 0; text-align: center;">{{ $greeting }}</h1>
                                                    @endisset
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    @isset($intro_lines)
                                                        @foreach ($intro_lines as $line)
                                                            {{ Illuminate\Mail\Markdown::parse($line) }}
                                                        @endforeach
                                                    @endisset

                                                    @isset($ul_list)
                                                        <ul>
                                                            @foreach ($ul_list as $item)
                                                                <li>{{ Illuminate\Mail\Markdown::parse($item) }}</li>
                                                            @endforeach
                                                        </ul>
                                                    @endisset

                                                    @isset($ol_list)
                                                        <ol>
                                                            @foreach ($ol_list as $item)
                                                                <li>{{ Illuminate\Mail\Markdown::parse($item) }}</li>
                                                            @endforeach
                                                        </ol>
                                                    @endisset

                                                    @isset($action_lines)
                                                        @foreach($action_lines as $line)
                                                            {{ Illuminate\Mail\Markdown::parse($line) }}
                                                        @endforeach
                                                    @endisset
                                                </td>
                                            </tr>
                                            @isset($action_url)
                                            <tr>
                                                <td style="text-align: center;">
                                                    <a class="btn" href="{{ $action_url }}"
                                                        title="{{ $action_url }}">
                                                        {{ $action_label }}
                                                    </a>
                                                </td>
                                            </tr>
                                            @endif
                                            <tr>
                                                <td>
                                                    @isset($outro_lines)
                                                        @foreach ($outro_lines as $line)
                                                            {{ Illuminate\Mail\Markdown::parse($line) }}
                                                        @endforeach
                                                        <br>
                                                    @endisset

                                                    <p class="sign">
                                                        Il team di Musigate
                                                    </p>
                                                </td>
                                            </tr>
                                            @isset($action_url)
                                            <tr>
                                                <td style="font-size: 10px;border-top: 1px #334155 solid;font-weight: 400;text-align: left;word-break: break-word; padding-bottom: 0">
                                                    Se hai difficoltà a cliccare sul pulsante "{{ $action_label }}", clicca o copia e
                                                    incolla l'URL seguente nel tuo browser:
                                                    <a href="{{ $action_url }}" class="link" title="{{ $action_label }}">
                                                        {{ $action_url }}
                                                    </a>
                                                </td>
                                            </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td class="footer">
                                    <!--[if mso | IE]>      <table role="presentation" border="0" cellpadding="0" cellspacing="0">        <tr>          <td style="vertical-align:top;width:300px;">      <![endif]-->
                                    <div class="outlook-group-fix">
                                        <img src="{{ $message->embed(public_path() . '/img/logo/logo_horizontal_complete.png') }}" alt="Musigate logo" style="height: 32px" />
                                        <br />
                                        <br />
                                        © {{ \Carbon\Carbon::now()->year }} OrangeWeb<br />
                                        P.IVA 04258520982
                                        <br />
                                        <br />
                                        <a href="https://www.facebook.com/musigate.it" title="Facebook" style="text-decoration: none">
                                            <img src="{{ $message->embed(public_path() . '/img/logo/facebook.png') }}" alt="facebook" style="height: 20px" />
                                        </a>

                                        <a href="https://www.instagram.com/musigate.it/" title="Instagram" style="text-decoration: none">
                                            <img src="{{ $message->embed(public_path() . '/img/logo/instagram.png') }}" alt="instagram" style="height: 20px" />
                                        </a>
                                    </div>
                                    <!--[if mso | IE]>      </td></tr></table>      <![endif]-->
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
</body>

</html>