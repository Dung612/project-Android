<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: #f2f4f7;
        }
        .login-box {
            background: #fff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
            width: 300px;
        }
        input {
            display: block;
            width: 100%;
            margin: 10px 0;
            padding: 10px;
            box-sizing: border-box;
        }
        button {
            padding: 10px 20px;
            background: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
            width: 100%;
        }
        #error {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="login-box">
        <img src="/images/logoTLU.png" alt="Logo" style="width: 150px;">
        <h2>Quản trị viên TLU RB</h2>
        <form onsubmit="login(event)">
            <input type="email" id="email" placeholder="TÀI KHOẢN" required>
            <input type="password" id="password" placeholder="MẬT KHẨU" required>
            <button type="submit">Xác Nhận</button>
            <p id="error"></p>
        </form>
    </div>

    <script>
        async function login(e) {
            e.preventDefault();

            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value.trim();

            const res = await fetch('/api/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ email, password })
            });

            const data = await res.json();

            if (res.ok) {
                localStorage.setItem('token', data.access_token);
                window.location.href = '/dashboard';
            } else {
                document.getElementById('error').textContent = data.message || 'Đăng nhập thất bại';
            }
        }
    </script>
</body>
</html>
