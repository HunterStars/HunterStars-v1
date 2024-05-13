<?php

namespace HS\config\enums;

enum AppDirs: string
{
    case TEMP = '/.temp';
    case LOG = '/.temp/logs';
    case CACHE = '/.temp/cache';
    case IMAGE_CACHE = '/.temp/cache/img';
    case PDF_CACHE = '/.temp/cache/pdf';
    case CONFIG = '/config';
    case ROUTES = '/config/routes';
    case ENUMS = '/config/enums';
    case LANG = '/config/lang';
    case VIEW = '/app/views';
    case FILES = '/files';
    case IMAGES = '/files/img';
    case UPLOAD_IMG = '/upload/img';
}