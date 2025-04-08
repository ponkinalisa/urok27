var crop1 = 0;
//Холст и его контекст
const canvas = document.getElementById("canvas");
const ctx = canvas.getContext("2d");

//Поля ввода
const widthBox = document.getElementById("widthBox");
const heightBox = document.getElementById("heightBox");

const topBox = document.getElementById("topBox");
const leftBox = document.getElementById("leftBox");

const wrapper = document.getElementsByClassName('main')[0];
const wrapper1 = document.getElementsByClassName('wrapper')[0];
//Ссылка на новое изображение
const image = document.getElementById("image");

var selection =
{
    mDown: false,
    x: Math.floor(image.width / 2),
    y: Math.floor(image.height / 2),
    top: 0,
    left: 0,
    width: 100,
    height: 100
};
widthBox.addEventListener('input', () => {selection.width = Number(widthBox.value); DrawSelection();});
heightBox.addEventListener('input', () => {selection.height = Number(heightBox.value);DrawSelection();});

selection.left = Math.floor(image.width / 2);
selection.top = Math.floor(image.height / 2);

const save = document.getElementById('save');
save.addEventListener('click', Save());

canvas.addEventListener('mousedown', (e) => {MouseDown(e)});
canvas.addEventListener('mousemove', (e) => {MouseMove(e)});
canvas.addEventListener('mouseup', (e) => {MouseUp(e)});

const crop = document.getElementById("btn_crop");
crop.addEventListener("click", function () { crop1 = 1; Init();});

function Init()
{
    wrapper.style.visibility = 'hidden';
    wrapper1.style.visibility = 'visible';
    canvas.width = image.width;
    canvas.height = image.height;

    canvas.setAttribute("style", "top: " + (image.offsetTop + 5) + "px; left: " + (image.offsetLeft + 5) + "px;");

    DrawSelection(); 
}


function DrawSelection()
{
    console.log(selection.left, selection.top, Math.floor(image.height / 2));
    ctx.fillStyle = "rgba(0, 0, 0, 0.7)";

    ctx.clearRect(0, 0, canvas.width, canvas.height);

    ctx.fillRect(0, 0, canvas.width, canvas.height);

    ctx.clearRect(selection.left, selection.top, selection.width, selection.height);

    ctx.strokeStyle = "#fff";

    ctx.beginPath();

    ctx.moveTo(selection.left, 0);
    ctx.lineTo(selection.left, canvas.height);

    ctx.moveTo(Number(selection.left + selection.width), 0);
    ctx.lineTo(Number(selection.left + selection.width), canvas.height);

    ctx.moveTo(0, selection.top);
    ctx.lineTo(canvas.width, selection.top);

    ctx.moveTo(0, selection.top + selection.height);
    ctx.lineTo(canvas.width, selection.top + selection.height);

    ctx.stroke();

    leftBox.value = selection.left;
    topBox.value = selection.top;
    widthBox.value = selection.width;
    heightBox.value = selection.height;
}

function MouseDown(e)
{
    //Говорим, что кнопка была зажата
    selection.mDown = true;
}

function MouseMove(e)
{
    if(selection.mDown) //Проверяем, зажата ли кнопка
    {
	//Получаем координаты курсора на холсте
   	 selection.x = e.clientX - canvas.offsetLeft - wrapper.offsetWidth;
   	 selection.y = e.clientY - canvas.offsetTop;

	//Меняем позицию выделенного фрагмента
   	 selection.left = selection.x - selection.width / 2;
   	 selection.top = selection.y - selection.height / 2;

	//Ввод новых значений в поля, отрисовка рамки
   	 DrawSelection(); 
    }
}

function MouseUp(e)
{
    //Отпускаем кнопку
    selection.mDown = false; 
}


function Save()
{
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    wrapper.style.visaibility = 'visible';
    wrapper1.style.visaibility = 'hidden';
    crop1 = 0;
}