<?php

declare(strict_types=1);

namespace App\Application\Enum;

enum OutputFileType {
    case csv;
    case xml;
    case txt;
}
