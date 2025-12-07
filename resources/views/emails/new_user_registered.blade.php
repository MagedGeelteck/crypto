<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>New User Registration</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #2196F3; color: white; padding: 20px; text-align: center; }
        .content { background-color: #f9f9f9; padding: 20px; margin-top: 20px; }
        .info-row { margin: 10px 0; padding: 10px; background-color: white; border-left: 4px solid #2196F3; }
        .label { font-weight: bold; color: #555; }
        .value { color: #333; }
        .footer { margin-top: 20px; padding: 20px; text-align: center; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <!-- Logo -->
            <div style="margin-bottom: 15px;">
                <img src="{{ asset('assets/images/logo_icon/logo.png') }}" alt="{{ gs()->site_name }}" style="max-width: 120px; height: auto;">
            </div>
            <h1>👤 New User Registration</h1>
        </div>
        
        <div class="content">
            <p><strong>A new user has registered on your platform.</strong></p>
            
            <div class="info-row">
                <span class="label">Username:</span>
                <span class="value">{{ $user->username }}</span>
            </div>
            
            <div class="info-row">
                <span class="label">Email:</span>
                <span class="value">{{ $user->email ?? 'Not provided' }}</span>
            </div>
            
            <div class="info-row">
                <span class="label">Mobile:</span>
                <span class="value">{{ $user->mobile ?? 'Not provided' }}</span>
            </div>
            
            <div class="info-row">
                <span class="label">Registration Date:</span>
                <span class="value">{{ $user->created_at->format('M d, Y - h:i A') }}</span>
            </div>
            
            <div class="info-row">
                <span class="label">Country:</span>
                <span class="value">{{ $user->address->country ?? 'Not provided' }}</span>
            </div>
            
            <div class="info-row">
                <span class="label">Status:</span>
                <span class="value">{{ $user->status == 1 ? 'Active' : 'Inactive' }}</span>
            </div>
            
            <p style="margin-top: 20px;">
                <a href="{{ route('admin.users.detail', $user->id) }}" 
                   style="display: inline-block; padding: 10px 20px; background-color: #2196F3; color: white; text-decoration: none; border-radius: 5px;">
                    View User Profile
                </a>
            </p>
        </div>
        
        <div class="footer">
            <p>This is an automated notification from {{ gs('site_name') }}</p>
            <p>{{ now()->format('Y-m-d H:i:s') }}</p>
        </div>
    </div>
</body>
</html>
