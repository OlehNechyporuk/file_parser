1. Run make install
2. Run make start (up docker and run jobs)

Використовує symfony/messenger - на базі redis

Після додавання нової задачі запускається консольна команда на парсинг App\Infrastructure\Command\ParseFilesCommand

Файли читає по рядку з генератора (для екномії пам'яті)

 $handle = fopen($path, 'r');

    while (!feof($handle)) {
        yield fgets($handle);
    }

fclose($handle);

Запис рузультату також по рядку (fputcsv, fwrite) 

Файл пошук постів та видалення тегів у App\Infrastructure\Service\WpFileParseService через Regex
