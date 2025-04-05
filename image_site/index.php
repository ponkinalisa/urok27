<?php
/**
 * Класс нормальзующий картинку по заданному разрешению
 */
class imageWorker {

  public $baseWidth = 150;  // длина эталонного изображения (именно товара внутри картинки)
  public $baseHeight = 119; // высота эталонного изображения (именно товара внутри картинки)
  public $outImageWidth = 600;  // длина изображения после обработки
  public $outImageHeight = 400; // высота изображения после обработки
  public $im = null;  // дескриптор картинки
  public $binaryMartix = null; // матричное представление картинки
  public $saveBinaryMartixTofile = false; // сохранять матричное представление картинки в файл
  public $dir = 'image/source/';  // расположение набора картинок
  public $saveMartix = false; // сохранять матричное представление картинок
  public $extensions = array("", "gif", "jpeg", "png"); // допустимые картинки
  public $curImageWidth = 1;  // ширина обрабатываемого изображения
  public $curImageHeight = 1; // высота обрабатываемого изображения
  public $imgFunctionProcess = "imagecreatefromjpeg"; // функция для работы с изображением
  public $curExt = ""; // расширение картинки
  public $curImageName = ""; // имя картинки
  public $dirUpload = 'image/result/'; // папка для выгрузки (должна быть создана)


  function __construct($path, $w, $h) {
    $this->outImageWidth = $w;
    $this->outImageHeight = $h;
    $this->curImageName = $path;

    list($this->curImageWidth, $this->curImageHeight, $type) = getimagesize($this->dir.$path); // Получаем размеры и тип изображения (число)
    $ext = $this->extensions[$type];
    if ($ext) {
      $this->imgFunctionProcess = 'imagecreatefrom'.$ext; // Получаем название функции, соответствующую типу, для создания изображения
      $func = $this->imgFunctionProcess;
      $this->curExt = $ext;
      $this->im = $func($this->dir.$path); // Создаём дескриптор для работы с исходным изображением
      if (!$this->im) {
        return false;
      }
    } else {
      echo 'Ошибка: Неизвестный формат изображения!';
      return false;
    }

    $this->binaryMartix = $this->imageToMatrix($this->im, false);

    if ($this->saveBinaryMartixTofile) {
      $this->printMatrix($this->binaryMartix);
    }

    $res = $this->explodeMatrix($this->binaryMartix);
    $width = $res['resultInterval'];
    $cropX = $res['startInterval'];

    $this->binaryMartix = $this->imageToMatrix($this->im, true);
    $res = $this->explodeMatrix($this->binaryMartix);
    $height = $res['resultInterval'];
    $cropY = $res['startInterval'];

    $result = "Размеры изображения (".$path.") <br/>width=".$this->curImageWidth."px; <br/>	height=".$this->curImageHeight."px;";
    $result .= "<br/>Размеры изделия внутри изображения <br/>width=".$width."px; <br/>	height=".$height."px;";
    $result .= "<br/>Коэффициенты сжатия <br/>width=".$this->baseWidth / $width."px; <br/>	height=".$this->baseHeight / $height."px;";
    $result .= "<br/>Отрезать картинку с точки <br/>cropX=".$cropX." <br/>	cropY=".$cropY." ";

    //$this->crop("2.png", $cropY, $cropX, $width, $height); // Вызываем функцию
    //$this->reSizeImage($name, $ext, $tmp, 0.3);
    echo $result;
    if ($this->baseHeight < $height) {
      $this->resizeImage($this->baseHeight / $height);
    } else {
      $this->resizeImage(1);
    };

    imagedestroy($this->im);
  }


  function explodeMatrix($binaryMartix) {
    $temp = array();

    // сложение столбцов для выявления интервалов
    for ($i = 0; $i < count($binaryMartix); $i++) {
      $sum = 0;
      for ($j = 0; $j < count($binaryMartix[0]); $j++) {
        $sum += $binaryMartix[$i][$j];
      }
      $temp[] = $sum ? 1 : 0;
    }

    // вычисление интервалов по полученной строке
    $start = false;
    $countPart = 0;
    $arrayInterval = array();
    foreach ($temp as $k => $v) {

      if ($v == 1 && !$start) {
        $arrayInterval[$countPart]['start'] = $k;
        $start = true;
      }

      if ($v == 0 && $start) {
        $arrayInterval[$countPart]['end'] = $k - 1;
        $start = false;
        $countPart++;
      }
    }

    //отсеиваем помехи (мелкие интервалы), Большая картинка, всяко больше 20px. 

    $resultInterval = 1;
    $startInterval = 1; // начало интервала
    foreach ($arrayInterval as $key => $interval) {
      if (($interval['end'] - $interval['start']) > 20) {
        $resultInterval = $interval['end'] - $interval['start'];
        $startInterval = $interval['start'];
      }
    }
    return
      array(
        'resultInterval' => $resultInterval,
        'startInterval' => $startInterval
    );
  }


  /**
   * Конвертация рисунка в бинарную матрицу
   * Все пиксели отличные от фона получают значение 1
   * @param resource $im - картинка в формате PNG
   * @param bool $rotate - горизонтальная или вертикальная матрица 
   */
  function imageToMatrix($im, $rotate = false) {
    $height = imagesy($im);
    $width = imagesx($im);

    if ($rotate) {
      $height = imagesx($im);
      $width = imagesy($im);
    }

    $background = 0;
    for ($i = 0; $i < $height; $i++)
      for ($j = 0; $j < $width; $j++) {

        if ($rotate) {
          $rgb = imagecolorat($im, $i, $j);
        } else {
          $rgb = imagecolorat($im, $j, $i);
        }

        //получаем индексы цвета RGB 
        list($r, $g, $b) = array_values(imagecolorsforindex($im, $rgb));

        //вычисляем индекс красного, для фона изображения
        if ($i == 0 && $j == 0) {
          $background = $r;
        }

        $sensitivity = 15;
        // если цвет пикселя не равен фоновому заполняем матрицу единицей		
        $binary[$i][$j] = ($r > $background - $sensitivity) ? 0 : 1;
      }
    return $binary;
  }


  /**
   * Выводит матрицу на экран
   * @param array $binaryMartix
   */
  function printMatrix($binaryMartix) {
    $return = '';
    for ($i = 0; $i < count($binaryMartix); $i++) {
      $return .= "\n";
      for ($j = 0; $j < count($binaryMartix[0]); $j++) {
        $return .= $binaryMartix[$i][$j]." ";
      }
    }
    file_put_contents($this->dirUpload.$this->curImageName.".txt", $return);
  }


  /**
   * Функция для ресайза картинки
   * @param float $koef коэффициент сжатия изображения
   * @return void
   */
  public function resizeImage($koef) {
    // получение новых размеров  
    $newWidth = $koef * $this->curImageWidth;
    $newHeight = $koef * $this->curImageHeight;
    // ресэмплирование    
    $image_p = imagecreatetruecolor($this->outImageWidth, $this->outImageHeight);
    //делаем фон изображения белым, иначе в png при прозрачных рисунках фон черный
    $color = imagecolorallocate($image_p, 255, 255, 255);
    imagefill($image_p, 0, 0, $color);
    imagecopyresampled(
      $image_p, $this->im, ($this->outImageWidth - $newWidth) / 2, ($this->outImageHeight - $newHeight) / 2, 0, 0, $newWidth, $newHeight, $this->curImageWidth, $this->curImageHeight
    );
    $func = "image".$this->curExt;
    $func($image_p, $this->dirUpload.$this->curImageName);
    imagedestroy($image_p);
  }

}// конец тела класса

/**
 * Массив имен файлов
 */
$imagesName = array(
  'kameya_medium_1005858593.jpg',
  'kameya_medium_1005862834.jpg',
  'km0210-3_small.jpg',
  'sd0201966_small.jpg',
);

/**
 * Перебор массива имен файлов и нормализация изображений
 */
foreach ($imagesName as $image) {
  if (file_exists('image/source/'.$image)) {
    // каждую картинку нормализуем и приведем к разрешению 200x200 пикселей
    $encrypt = new imageWorker($image, 200, 200);
  } else {
    continue;
  }
}
