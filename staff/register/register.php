<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>รสฤดี - ลงทะเบียน</title>
</head>

<body>
    <header>
        <div class="top"></div>
        <div class="text">ลงทะเบียนสำหรับพนักงาน</div>
        <div class="rodruedee">รสฤดี</div>
    </header>

    <div class="login-container">

        <div>
            <form action="register_process.php" method="post">

                <div>
                    <div class="username-text"> <label for="first-name">ชื่อจริง:</label> </div>
                    <div class="text-field"> <input type="text" id="first-name" name="first-name" required> </div>
                </div>

                <div>
                    <div class="username-text"> <label for="last-name">นามสกุล:</label> </div>
                    <div class="text-field"> <input type="text" id="last-name" name="last-name" required> </div>
                </div>

                <div>
                    <div class="username-text"> <label for="username">ชื่อผู้ใช้:</label> </div>
                    <div class="text-field"> <input type="text" id="username" name="username" required> </div>
                </div>

                <div>
                    <div class="username-text"> <label for="password">รหัสผ่าน:</label> </div>
                    <div class="text-field"> <input type="password" id="password" name="password" required> </div>
                </div>

                <div>
                    <div class="username-text"> <label for="password-confirm">ยืนยันรหัสผ่าน:</label> </div>
                    <div class="text-field"> <input type="password" id="password-confirm" name="password-confirm" required> </div>
                </div>

                <div class="text-field">
                    <label for="password-confirm">ตำแหน่ง:</label>
                    <select id="role" name="role" required>
                        <option value="chef">เชฟ</option>
                        <option value="employee">พนักงาน</option>
                    </select>
                </div>

                <div class="center">
                    <button type="submit" class="login-button">สมัครบัญชีพนักงาน</button>
                </div>

            </form>
        </div>
    </div>

</body>

</html>