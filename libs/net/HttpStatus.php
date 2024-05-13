<?php

namespace HS\libs\net;

enum HttpStatus: int
{
    case C303_SEE_OTHER = 303;
    case C403_FORBIDDEN = 403;
    case C404_NOTFOUND = 404;
    case C500_INTERNAL_ERROR = 500;
}
