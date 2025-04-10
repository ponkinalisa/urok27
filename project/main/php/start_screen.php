<?php 
require_once 'config.php';
session_start();
if (!isset($_SESSION['id']) or !isset($_SESSION['login'])){
    die('Сначала войдите в аккаунт...');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    if (isset($_POST['description'])){
        $description = $_POST['description'];
    }else{
        $description = '';
    }
    if (isset($_FILES['file'])){
        $file = $_FILES['file'];
        $type = $file['type'];
        $file_name = $file['name'];
        $tmp_name = $file["tmp_name"];
        $file_name_sep = mb_split("\.", $file_name);
        $error = 'Неподдерживаемый формат изображения.';
        $new_file_name = random_int(1, 10000000000);
        $ext = $file_name_sep[count($file_name_sep)-1];
        switch ($type) {
            case 'image/gif':
                $error = Null;
                break;
            case 'image/jpg':
            case 'image/jpeg':
                $error = Null;
                break;
            case 'image/png':
                $error = Null;
                break;
            }
        $sql = "SELECT * FROM users WHERE login = :username";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['username' => $_SESSION['login']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $dir_name = $user['login'];
        if (!$error){
            $directory = "../user_img/$dir_name";
            if (!file_exists($directory)) {
                mkdir($directory);  
            }
            move_uploaded_file($tmp_name, "./../user_img/$dir_name/$new_file_name.$ext");
            $path = "../user_img/$dir_name" . "/" . $new_file_name . '.' . $ext;

            $_SESSION['path'] = $path;
            $_SESSION['type'] = $type;
            $_SESSION['blur'] = 0;
            $_SESSION['wb'] = 0;
            $_SESSION['invers'] = 0;
            $_SESSION['rotation'] = 0;
            $_SESSION['scale'] = 100;
            $_SESSION['crop'] = 0;
            $_SESSION['width'] = getimagesize($path)[0];
            $_SESSION['height'] = getimagesize($path)[1];
            $_SESSION['top'] = 0;
            $_SESSION['left'] = 0;

            $sql = "INSERT INTO images (user_id, name,  description, type, date) VALUES(:user_id, :name, :description, :type, CURRENT_DATE())";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['user_id' => $_SESSION['id'], 'name' => $new_file_name . '.' . $ext, 'description' => $description, 'type' => $ext]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            header('Location: preview.php');
        }
    }else{
        $error = 'Ошибка получения файла!';
    }
}
?>


<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Главная</title>
    <link rel="stylesheet" type="text/css" href="../css/style.css">
</head>
<body>
    <div class="btn" onclick="download()">Личный кабинет</div>
    <div onclick="all_photo()" class="btn button1">Галерея фото всех пользователей</div>
    <form enctype="multipart/form-data" action="start_screen.php" method="post">
        <h3>Выбор изображения</h3>
        <label id="fileformlabel" style="display: none; height: 0px;"></label>
        <label class="input-file">
            <input id="file" type="file" name="file" accept=".jpg, .jpeg, .png, .gif" onchange="getName(this.value);" required>
            <span class="input-file-btn">Выберите фото</span>   
            <label for="file">В формате png, jpg или gif</label><br>
        </label>
        <input type="text" placeholder="Добавьте описание фото.." name="description" required>
        <input type="submit" value="Редактировать">
        <a href="logout.php" style="font-size: 23px;">Выйти из аккаунта</a>
    </form>
    <?php 
    if (($error)){
        echo "<div class='error'>$error</div>";
    };
    ?>
    <script>
        function getName (str){
    if (str.lastIndexOf('\\')){
        var i = str.lastIndexOf('\\')+1;
    }
    else{
        var i = str.lastIndexOf('/')+1;
    }						
    var filename = str.slice(i);
    console.log(filename);
    var uploaded = document.getElementById("fileformlabel");
    uploaded.style.display = 'block';
    uploaded.style.height = '20px';
    uploaded.innerHTML = 'Выбран файл: ' + filename;
    }
    </script>

    <script>
        function download(){
            window.location.href = "account.php";
        }
        function all_photo(){
            window.location.href = "all_photo.php";
        }
    </script>
</body>
</html>