<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Successful</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #1a365d; color: #fff; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { background: #f7fafc; padding: 24px; border: 1px solid #e2e8f0; border-top: none; border-radius: 0 0 8px 8px; }
        .detail-row { margin: 12px 0; padding: 10px; background: #fff; border-radius: 4px; border-left: 4px solid #3182ce; }
        .label { font-weight: bold; color: #2d3748; }
        .value { margin-top: 4px; }
        .login-box { background: #edf2f7; padding: 16px; border-radius: 6px; margin: 20px 0; }
        .site-link { display: inline-block; margin-top: 16px; padding: 12px 24px; background: #3182ce; color: #fff !important; text-decoration: none; border-radius: 6px; }
        .footer { margin-top: 24px; font-size: 12px; color: #718096; }
    </style>
</head>
<body>
    <div class="header">
        <h1 style="margin:0;">Registration Successful</h1>
        <p style="margin:8px 0 0 0; opacity:0.9;">{{ config('app.name') }}</p>
    </div>
    <div class="content">
        <p>Hello {{ $owner->name }},</p>
        <p>Your organization <strong>{{ $organization->company_name }}</strong> has been registered successfully.</p>

        <h3>Organization details</h3>
        <div class="detail-row"><span class="label">Company name</span><div class="value">{{ $organization->company_name }}</div></div>
        @if($organization->legal_name)
        <div class="detail-row"><span class="label">Legal name</span><div class="value">{{ $organization->legal_name }}</div></div>
        @endif
        <div class="detail-row"><span class="label">Organization code</span><div class="value">{{ $organization->organization_code }}</div></div>
        <div class="detail-row"><span class="label">Email</span><div class="value">{{ $organization->email }}</div></div>
        @if($organization->phone)
        <div class="detail-row"><span class="label">Phone</span><div class="value">{{ $organization->phone }}</div></div>
        @endif
        @if($organization->gstin)
        <div class="detail-row"><span class="label">GSTIN</span><div class="value">{{ $organization->gstin }}</div></div>
        @endif
        @if($organization->address)
        <div class="detail-row"><span class="label">Address</span><div class="value">{{ $organization->address }}</div></div>
        @endif

        <h3>Your login details</h3>
        <div class="login-box">
            <div class="detail-row"><span class="label">Username (email)</span><div class="value">{{ $owner->email }}</div></div>
            <div class="detail-row"><span class="label">Password</span><div class="value">{{ $plainPassword }}</div></div>
            <p style="margin:12px 0 0 0; font-size:13px; color:#718096;">Use these credentials to log in at the link below. We recommend changing your password after first login.</p>
        </div>

        <p>Access your account:</p>
        <a href="{{ $siteUrl }}" class="site-link">Go to {{ config('app.name') }}</a>

        <div class="footer">
            <p>This email was sent to {{ $organization->email }} because your organization was registered on {{ config('app.name') }}.</p>
        </div>
    </div>
</body>
</html>
