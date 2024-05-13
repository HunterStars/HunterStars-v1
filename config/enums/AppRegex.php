<?php

namespace HS\config\enums;

enum AppRegex: string
{
    case UserName = '^[a-zA-Z1-9À-ÖØ-öø-ÿ]+\.?(( |\-)[a-zA-Z1-9À-ÖØ-öø-ÿ]+\.?)*$';
    case UserNick = '^[a-zA-Z]((\.|_|-)?[a-zA-Z0-9]+){3,11}$';
    case UserPass = '^.{8,50}$';
}