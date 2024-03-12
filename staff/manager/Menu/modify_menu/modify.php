<?php
session_start();
class MyDB extends SQLite3
{
    function __construct()
    {
        $this->open('../../../../rodrudee.db');
    }
}

$db = new MyDB();
if (!$db) {
    echo $db->lastErrorMsg();
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $image_uploaded = false;
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
        $image_uploaded = true;
        $image = $_FILES['image']['name'];
        $temp_name = $_FILES['image']['tmp_name'];
        $upload_dir = "../../../../foodimg/";
        
 
        $foodName = $_POST['name'];
        
        $foodName = strtolower(str_replace(' ', '_', $foodName));

        $image = $foodName . '.' . pathinfo($image, PATHINFO_EXTENSION);
        
        move_uploaded_file($temp_name, $upload_dir.$image);
    }


    if ($image_uploaded) {

        $stmt = $db->prepare("UPDATE menu SET name=?, price=?, category=?, region=?, file_path=? WHERE menu_id=?");

        // Set parameters and execute
        $stmt->bindParam(1, $_POST['name']);
        $stmt->bindParam(2, $_POST['price']);
        $stmt->bindParam(3, $_POST['category']);
        $stmt->bindParam(4, $_POST['region']);
        $file_path = '../foodimg/' . $_POST['name'] . '.jpg';
        $stmt->bindParam(5, $file_path);
        $stmt->bindParam(6, $_GET['id']);

        $stmt->execute();

        $stmt->close();

        echo '<script>alert("แก้ไขข้อมูลสำเร็จ");</script>';
        echo '<script>window.location.href="../view_menu/menu.php";</script>';
        
    } else {
        echo '<script>alert("ไม่มีรูปภาพที่อัปโหลด กรุณาอัปโหลดรูปภาพ");</script>';
    }
}

if (isset($_GET['id'])) {
    $menu_id = $_GET['id'];
    $fetch_menu_sql = "SELECT * FROM menu WHERE menu_id = '$menu_id'";
    $fetch_menu_result = $db->query($fetch_menu_sql);

    $row = $fetch_menu_result->fetchArray(SQLITE3_ASSOC);

    if ($row) {
        $menu_data = $row;
    } else {
        echo "Menu not found";
        exit; 
    }
}

$db->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="modify.css">
    <title>แก้ไขเมนู</title>
</head>
<body>
    <header>
        <img src="profile.png" alt="profile">
        <span>ผู้จัดการ</span>
    </header>
    <div class="content">
        <div class="box">
            <div class="tab">
                <p>แก้ไขเมนู</p>
            </div>
            <div class="bottom-content">
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . $menu_id; ?>"
                      enctype="multipart/form-data">
                    <div class="boxcontent">
                        <div class="changename">
                            <span>ชื่อ: </span>
                            <span style="display:flex;"><input type="text" name="name" class="text-field" value="<?php echo $menu_data['name']; ?>"></span>
                        </div>
                        <div class="changename">
                            <span>ราคา: </span>
                            <span style="display:flex;"><input type="text" name="price" class="text-price" value="<?php echo $menu_data['price']; ?>"></span>
                            <span>บาท</span>
                        </div>
                        <div class="changename">
                            <span>ประเภท: </span>
                            <select name="category" id="category" class="selecte-option">
                                <option value="อาหารจานเดียว" <?php echo ($menu_data['category'] == 'อาหารจานเดียว') ? 'selected' : ''; ?>>อาหารจานเดียว</option>
                                <option value="เครื่องดื่ม" <?php echo ($menu_data['category'] == 'เครื่องดื่ม') ? 'selected' : ''; ?>>เครื่องดื่ม</option>
                                <option value="กับข้าว" <?php echo ($menu_data['category'] == 'กับข้าว') ? 'selected' : ''; ?>>กับข้าว</option>
                                <option value="ของหวาน" <?php echo ($menu_data['category'] == 'ของหวาน') ? 'selected' : ''; ?>>ของหวาน</option>
                            </select>
                        </div>
                        <div class="changename">
                            <span>ภาค: </span>
                            <select name="region" id="region" class="selecte-option">
                                <option value="ภาคกลาง" <?php echo ($menu_data['region'] == 'ภาคกลาง') ? 'selected' : ''; ?>>ภาคกลาง</option>
                                <option value="ภาคใต้" <?php echo ($menu_data['region'] == 'ภาคใต้') ? 'selected' : ''; ?>>ภาคใต้</option>
                                <option value="ภาคเหนือ" <?php echo ($menu_data['region'] == 'ภาคเหนือ') ? 'selected' : ''; ?>>ภาคเหนือ</option>
                                <option value="ภาคอีสาน" <?php echo ($menu_data['region'] == 'ภาคอีสาน') ? 'selected' : ''; ?>>ภาคอีสาน</option>
                            </select>
                        </div>
                        <div class="changeimg">
                            <span>เลือกรูปภาพ: </span>
                            <span><input type="file" name="image" id="image" accept="image/*" class="hidden"></span>
                        </div>
                        <div id="preview"></div>
                    </div>
                    <div class="forbutton">
                        <button type="submit" class="submit">ยืนยัน</button>
                        <button type="button"  class="cancel" onclick="cancelbuttonClick()">ยกเลิก</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById("image").addEventListener("change", function (event) {
            var file = event.target.files[0];
            var reader = new FileReader();
            reader.onload = function (e) {
                var img = document.createElement("img");
                img.src = e.target.result;
                document.getElementById("preview").innerHTML = "";
                document.getElementById("preview").appendChild(img);
            }
            reader.readAsDataURL(file);
        });

    function cancelbuttonClick(){
        window.location.href= "../view_menu/menu.php";
    }
    </script>
</body>
</html>
