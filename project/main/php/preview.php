<?php 
session_start();
if (isset($_SESSION['path']) and isset($_SESSION['type'])){
    $error = Null;
}else{
    $error = 'Ошибка получения фото!';
}
if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    if (!empty($_POST)){
        if ($_POST['blur']){
            $_SESSION['blur'] = 1;
        }else{
            $_SESSION['blur'] = 0;
        }
        if ($_POST['wb']){
            $_SESSION['wb'] = 1;
        }else{
            $_SESSION['wb'] = 0;
        }
        if ($_POST['invers']){
            $_SESSION['invers'] = 1;
        }else{
            $_SESSION['invers'] = 0;
        }
        $_SESSION['rotation'] = $_POST['select'];
        $_SESSION['scale'] = $_POST['range'];
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Главная</title>
    <link rel="stylesheet" type="text/css" href="../css/preview.css">
</head>
<body>
    <!-- Обрезка изображения -->
    <form class="wrapper" action="editor.php" method="post">
    <h3>Обрезка изображения</h3>
            <label>Ширина: <input type="number" name="widthBox" id="widthBox"  min="100" title="Width"></label>
            <label>Высота: <input type="number" name="heightBox" id="heightBox"  min="100" title="Height"></label>
   			 <label>Отступ сверху: <input type="number" name="topBox" id="topBox" value="0" min="0" title="Top"> </label>
   			 <label>Отступ слева: <input type="number" name="leftBox" id="leftBox" value="0" min="0" title="Left"></label>
   			 <input type="submit" value="Сохранить" id="save">
    </form>

    <div class="btn" onclick="download()">Скачать</div>

    <div class="main" id="main">
        <form action="preview.php" method="post" id="myform">
            <div class="item">
                <input type="checkbox" name="blur" id="blur" value="blur" class="toggle" <?php if(isset($_SESSION['blur']) and $_SESSION['blur'] == 1){echo('checked');}?>>
                <label for="blur">Добавить размытие</label>
            </div>
            <div class="item">
                <input type="checkbox" name="wb" id="wb" value="wb" class="toggle" <?php if(isset($_SESSION['wb']) and $_SESSION['wb'] == 1){echo('checked');}?>>
                <label for="wb">Черно-белое</label>
            </div>
            <div class="item">
                <input type="checkbox" name="invers" id="invers" value="invers" class="toggle" <?php if(isset($_SESSION['invers']) and $_SESSION['invers'] == 1){echo('checked');}?>>
                <label for="invers">Инверсия</label>
            </div>
            <div class="item">
                <select name="select" id="select" onchange="document.getElementById('myform').submit()">
                    <option value="0" <?php if(isset($_SESSION['rotation']) and $_SESSION['rotation'] == '0'){echo('selected');}?>>0</option>
                    <option value="90" <?php if(isset($_SESSION['rotation']) and $_SESSION['rotation'] == '90'){echo('selected');}?>>90</option>
                    <option value="180" <?php if(isset($_SESSION['rotation']) and $_SESSION['rotation'] == '180'){echo('selected');}?>>180</option>
                    <option value="270" <?php if(isset($_SESSION['rotation']) and $_SESSION['rotation'] == '270'){echo('selected');}?>>270</option>
                </select>
                <label for="select">Выбрать угол поворота</label>
            </div>
            <label for="range">Выбрать масштаб:</label>
            <input type="range" id="range" name="range" max="200" min="10" <?php 
            if (!isset($_SESSION['scale'])){
                echo 'value="95" style="background-size: 50% 100%;"';}
            else{
                echo 'value="'.$_SESSION['scale'].'" style="background-size: '.(int)(($_SESSION['scale'] - 10) / 2).'% 100%;"';
            }
            ?>>
            <input id="btn_crop" value="Обрезать" type="button">
            <input type="submit" value="Применить изменения">
        </form>
        <div class="error"></div>
    </div>
    <div class="photo">
             <canvas id="canvas" class="canvas">
   			 </canvas>
        <img src="img.php" class="image"  id="image" alt="здесь должно быть ваше фото">
    </div>
    
    <script>
        function download(){
            window.location.href = "download.php";
        }

        const toggles = document.querySelectorAll('input[type="checkbox"]')
        const rotation = document.getElementById('select')
        const rangeInputs = document.querySelectorAll('input[type="range"]')
const numberInput = document.querySelector('input[type="number"]')

function handleInputChange(e) {
  let target = e.target
  if (e.target.type !== 'range') {
    target = document.getElementById('range')
  } 
  const min = target.min
  const max = target.max
  const val = target.value
  
  target.style.backgroundSize = (val - min) * 100 / (max - min) + '% 100%';
}

rangeInputs.forEach(input => {
  input.addEventListener('input', handleInputChange);
  input.addEventListener('change', () =>{document.getElementById('myform').submit();});
  input.addEventListener('DOMContentLoaded', handleInputChange);
})

toggles.forEach(input => {
  input.addEventListener('change', () =>{document.getElementById('myform').submit();});
  input.addEventListener('input', () =>{document.getElementById('myform').submit();});
})

rotation.addEventListener('onchange', () =>{document.getElementById('myform').submit();});
rotation.addEventListener('input', () =>{document.getElementById('myform').submit();});
numberInput.addEventListener('input', handleInputChange);
numberInput.addEventListener('DOMContentLoaded', handleInputChange);

    </script>
    <script src="../js/crop.js"></script>
</body>
</html>
