<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول - نظام إدارة رياض الأطفال</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .login-container {
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 450px;
        }
        
        .logo {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .logo-icon {
            font-size: 60px;
            margin-bottom: 10px;
        }
        
        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 10px;
            font-size: 24px;
        }
        
        .subtitle {
            text-align: center;
            color: #666;
            font-size: 14px;
            margin-bottom: 30px;
        }
        
        .alert {
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        
        .alert-error {
            background: #fee;
            color: #c33;
            border: 1px solid #fcc;
        }
        
        .alert-success {
            background: #dfd;
            color: #363;
            border: 1px solid #cec;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            color: #333;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 14px;
        }
        
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 15px;
            transition: all 0.3s;
        }
        
        input[type="email"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .btn-login {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        
        .test-info {
            margin-top: 30px;
            padding: 15px;
            background: #f5f5f5;
            border-radius: 8px;
            border-right: 4px solid #667eea;
        }
        
        .test-info p {
            margin: 5px 0;
            font-size: 13px;
            color: #555;
        }
        
        .test-info strong {
            color: #667eea;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo">
            <div class="logo-icon">🏫</div>
            <h1>نظام إدارة رياض الأطفال</h1>
            <p class="subtitle">روضة الزهراء النموذجية | فضاء الأطفال النموذجية</p>
        </div>

        @if(session('error'))
        <div class="alert alert-error">
            ⚠️ {{ session('error') }}
        </div>
        @endif

        @if(session('success'))
        <div class="alert alert-success">
            ✅ {{ session('success') }}
        </div>
        @endif

   <form method="POST" action="/login">
    @csrf
            
            <div class="form-group">
                <label for="email">📧 البريد الإلكتروني</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    value="admin@kindergarten.ly"
                    required 
                    autofocus
                    placeholder="أدخل البريد الإلكتروني">
            </div>

            <div class="form-group">
                <label for="password">🔒 كلمة المرور</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    value="password123"
                    required
                    placeholder="أدخل كلمة المرور">
            </div>

            <button type="submit" class="btn-login">
                🚀 تسجيل الدخول
            </button>
        </form>

        <div class="test-info">
            <p><strong>🔑 للاختبار استخدم:</strong></p>
            <p><strong>البريد:</strong> admin@kindergarten.ly</p>
            <p><strong>كلمة المرور:</strong> password123</p>
        </div>
    </div>
</body>
</html>