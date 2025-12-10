<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject ?? 'Notification' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif; }
        
        /* EMAIL SAFE COLORS (Converted from your OKLCH config)
           Primary:   #4F46E5 (Matches your blue-ish purple)
           Secondary: #FB923C (Matches your apricot/orange)
           Accent:    #22D3EE (Matches your teal)
           Base-100:  #F9FAFB (Your almost-white background)
           Base-200:  #F3F4F6 (Your light gray background)
           Content:   #1F2937 (Your dark text)
        */

        .bg-base-100 { background-color: #F9FAFB; }
        .bg-base-200 { background-color: #F3F4F6; }
        .text-base-content { color: #1F2937; }
        
        .btn-primary { 
            background-color: #4F46E5 !important; 
            color: #ffffff !important; 
            border-radius: 0.25rem !important; /* Your --radius-selector */
            padding: 12px 24px;
            text-decoration: none;
            display: inline-block;
        }

        .text-primary { color: #4F46E5 !important; }
        .text-secondary { color: #FB923C !important; }
        
        /* Layout resets */
        .wrapper { width: 100%; background-color: #F3F4F6; padding: 40px 0; }
        .content-box { 
            background-color: #F9FAFB; 
            margin: 0 auto; 
            width: 100%; 
            max-width: 600px; 
            border-radius: 0.25rem; /* Your --radius-box */
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body style="background-color: #F3F4F6; margin: 0; padding: 0;">

    <div class="wrapper">
        <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <td align="center">
                    
                    <div style="margin-bottom: 24px; text-align: center;">
                        <h1 style="color: #4F46E5; font-size: 24px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.05em;">
                            {{ config('app.name') }}
                        </h1>
                    </div>

                    <table class="content-box" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                            <td style="padding: 32px;">
                                @yield('content')
                            </td>
                        </tr>
                    </table>

                    <div style="margin-top: 32px; text-align: center; color: #6B7280; font-size: 12px;">
                        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
                        <p>
                            <a href="#" style="color: #6B7280; text-decoration: underline;">Unsubscribe</a>
                        </p>
                    </div>

                </td>
            </tr>
        </table>
    </div>

</body>
</html>