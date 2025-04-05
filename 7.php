<?php
// Путь к PNG изображению
$file_png = __DIR__ . '/donut.png';

// Загрузим PNG изображение
$img = imagecreatefrompng($file_png);

// Установим цвет текста (черный)
$text_color = imagecolorallocate($img, 0, 0, 0);

// Путь к шрифту TTF
$font_path = __DIR__ . '/arial.ttf'; // Путь к шрифту (удостоверьтесь, что файл arial.ttf существует)

// Текст для добавления
$text = "Привет, мир!";

// Размер шрифта
$font_size = 20;

// Угол наклона текста
$angle = 0;

// Позиция текста на изображении
$x = 50;
$y = 50;

// Добавим текст на изображение
imagettftext($img, $font_size, $angle, $x, $y, $text_color, $font_path, $text);

// Установим заголовок Content-Type для отображения изображения
header('Content-Type: image/png');

// Отправим изображение в браузер
imagepng($img);

// Освободим память
imagedestroy($img);
?>
