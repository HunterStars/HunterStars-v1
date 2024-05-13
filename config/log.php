<?php

namespace HS\config;

enum LogFile: string
{
    case SCSS = 'scss';
    case DB = 'db';
    case CRYPT = 'crypt';
    case IMG = 'img';
    case IMG_UPLOAD = 'img_up';
    case NO_ACCESS = 'no_access';
    case HTML_FILTER = 'html_filter';
    case GET_POST_FORMAT = 'input_get_post';
    case TRACKING = 'tracking';
    case PDF = 'mpdf';
}