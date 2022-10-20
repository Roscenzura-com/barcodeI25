# Генератор баркодов (штрихкодов) I25 (Interleaved 2 of 5).

PHP скрипт для генерации штрихкодов формата I25. Такой код, например, использует Почта России. Сгенерированный штриход можно распечатать на наклейки. 
Если нужна помощь в настройке скрипта пишите на admin@roscenzura.com.

Пример использования: 

```php
   include(__DIR__.'/barcode.class.php');
   $font=__DIR__.'/fonts/arial.ttf';

   $barcodeGenerator = new BarcodeI25(200, null, [0, 0, 0], [255,255,255], $font ); // Параметры: высота, коэффициент ширины, цвет кода (rgb), цвет фона (rgb), шрифт
   $barcodeGenerator->getBarcode('80110177878444', '801101 77 87844 4'); // Параметры: значение для штрихкода, подпись (под штрихкодом)
```
