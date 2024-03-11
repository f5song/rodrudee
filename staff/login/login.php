<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>รสฤดี - เข้าสู่ระบบ</title>
</head>

<body>
    <header>
        <div class="top"></div>
        <div class="text">ลงทะเบียนสำหรับพนักงาน</div>
        <div class="rodruedee">รสฤดี</div>
    </header>

    <div class="login-container">
        <div class="login-text">เข้าสู่ระบบ</div>
        <div>
            <form action="login_process.php" method="post">
                <div>
                    <div class="username-text"> <label for="username">ชื่อผู้ใช้:</label> </div>
                    <div class="text-field"> <input type="text" id="username" name="username"> </div>
                </div>
                <div>
                    <div class="username-text"> <label for="password">รหัสผ่าน:</label> </div>
                    <div class="text-field"> <input type="password" id="password" name="password"> </div>
                </div>
                <div style="display: flex; flex-direction: column; align-items: center;">
                    <button class="login-button">เข้าสู่ระบบ</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        document.querySelector('.login-button').addEventListener('click', function (event) {
            var usernameInput = document.getElementById('username').value.trim();
            var passwordInput = document.getElementById('password').value.trim();

            if (usernameInput === '' || passwordInput === '') {
                alert('กรุณากรอกข้อมูลให้ถูกต้อง');
                event.preventDefault(); // Prevent the form from being submitted
            } else {
                window.location.href = 'state.html'; // Redirect to state.html
            }
        });
    </script>
</body>

</html>