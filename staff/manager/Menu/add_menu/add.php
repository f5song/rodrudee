<?php
session_start();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Include database connection file
    $db = new SQLite3('../../../../rodrudee.db');
    if (!$db) {
        die("Connection failed: " . $db->lastErrorMsg());
    }

    if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
        $image = $_FILES['image']['name'];
        $temp_name = $_FILES['image']['tmp_name'];
        $upload_dir = "../../../../foodimg/";
        

        $foodName = $_POST['name'];

        $foodName = strtolower(str_replace(' ', '_', $foodName));

        $image = $foodName . '.' . pathinfo($image, PATHINFO_EXTENSION);

        move_uploaded_file($temp_name, $upload_dir . $image);
    } else {
        echo '<script>alert("เกิดข้อผิดพลาดในการอัปโหลดไฟล์รูปภาพ");</script>';
    }

    // Prepare and bind parameters
    $stmt = $db->prepare("INSERT INTO menu (name, price, category, region, file_path) VALUES (:name, :price, :category, :region, :file_path)");

    $stmt->bindValue(':name', $_POST['name'], SQLITE3_TEXT);
    $stmt->bindValue(':price', $_POST['price'], SQLITE3_INTEGER);
    $stmt->bindValue(':category', $_POST['category'], SQLITE3_TEXT);
    $stmt->bindValue(':region', $_POST['region'], SQLITE3_TEXT);
    $stmt->bindValue(':file_path', '../foodimg/' . $_POST['name'] . '.jpg', SQLITE3_TEXT);

    $stmt->execute();

    // Close statement
    $stmt->close();

    // Close connection
    $db->close();

    echo '<script>alert("เพิ่มเมนูอาหารสำเร็จ");</script>';
    // echo '<script>window.location.href="../view_menu/menu.php";</script>';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="add.css">
    <title>เพิ่มเมนู</title>
</head>
<body>
    <header>
        <img src="profile.png" alt="profile">
        <span>ผู้จัดการ</span>
    </header>
    <div class="content">
        <div class="box">
            <div class="tab">
                <p>เพิ่มเมนู</p>
            </div>
            <div class="bottom-content">
                <form id="addForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
                    <div class="boxcontent">
                        <div class="changename">
                            <span>ชื่อ: </span>
                            <span style="display:flex;"><input type="text" name="name" id="name" class="text-field"></span>
                        </div>
                        <div class="changename">
                            <span>ราคา: </span>
                            <span style="display:flex;"><input type="text" name="price" id="price" class="text-price"></span>
                            <span>บาท</span>
                        </div>
                        <div class="changename">
                            <span>ประเภท: </span>
                            <select name="category" id="category" class="selecte-option">
                                <option value="อาหารจานเดียว">อาหารจานเดียว</option>
                                <option value="เครื่องดื่ม">เครื่องดื่ม</option>
                                <option value="กับข้าว">กับข้าว</option>
                                <option value="ของหวาน">ของหวาน</option>
                            </select>
                        </div>
                        <div class="changename">
                            <span>ภาค: </span>
                            <select name="region" id="region" class="selecte-option">
                                <option value="ภาคกลาง">ภาคกลาง</option>
                                <option value="ภาคใต้">ภาคใต้</option>
                                <option value="ภาคเหนือ">ภาคเหนือ</option>
                                <option value="ภาคอีสาน">ภาคอีสาน</option>
                            </select>
                        </div>
                        <div class="changeimg">
                            <span>เลือกรูปภาพ: </span>
                            <span><input type="file" name="image" id="image" accept="image/*" class="hidden"></span>
                        </div>
                        <div id="preview"></div>
                    </div>
                    <div class="forbutton">
                        <button type="button"  class="submit" onclick="validateForm()">ยืนยัน</button>
                        <button type="button"  class="cancel" onclick="cancelbuttonClick()">ยกเลิก</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    function validateForm() {
        var name = document.getElementById("name").value;
        var price = document.getElementById("price").value;
        var category = document.getElementById("category").value;
        var region = document.getElementById("region").value;
        var image = document.getElementById("image").value;

        if (name == "" || price == "" || category == "" || region == "" || image == "") {
            alert("กรุณากรอกข้อมูลให้ครบถ้วน");
        } else {
            document.getElementById("addForm").submit();
        }
    }

    document.getElementById("image").addEventListener("change", function(event) {
        var file = event.target.files[0];
        var reader = new FileReader();
        reader.onload = function(e) {
            var img = document.createElement("img");
            img.src = e.target.result;
            document.getElementById("preview").innerHTML = "";
            document.getElementById("preview").appendChild(img);
        }
        reader.readAsDataURL(file);
    });

    function cancelbuttonClick() {
        window.location.href="../view_menu/menu.php";
    }
    </script>
</body>
</html>
