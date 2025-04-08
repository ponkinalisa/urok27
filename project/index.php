<?php 
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST'){
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
        $files = glob('user_img/*'); // get all file names
        foreach($files as $file){ // iterate files
           if(is_file($file)) {
                unlink($file); // delete file
            }
        }
        if (!$error){
            move_uploaded_file($tmp_name, "./user_img/$new_file_name.$ext");
            $path = "user_img" . "/" . $new_file_name . '.' . $ext;
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
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
    <form enctype="multipart/form-data" action="index.php" method="post">
        <h3>Выбор изображения</h3>
        <label id="fileformlabel" style="display: none; height: 0px;"></label>
        <label class="input-file">
            <input id="file" type="file" name="file" accept=".jpg, .jpeg, .png, .gif" onchange="getName(this.value);" required>
            <span class="input-file-btn">Выберите фото</span>   
            <label for="file">В формате png, jpg или gif</label><br>
        </label>
        <input type="submit" value="Редактировать">
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
</body>
</html>