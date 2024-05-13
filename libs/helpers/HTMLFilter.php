<?php

namespace HS\libs\helpers;

use ErrorException;
use HS\libs\io\Path;
use HTMLPurifier;
use HTMLPurifier_AttrDef_Enum;
use HTMLPurifier_Config;
use HTMLPurifier_HTML5Config;

require_once Path::CombineRoot('/vendor/HTMLPurifier/HTMLPurifier.auto.php');

class HTMLFilter
{
    /**
     * @throws ErrorException
     */
    public static function GetHTMLPurifierStringForBasicEditor(string $htmlUnTrust): string
    {
        set_error_handler(function ($errno, $errstr, $errfile, $errline) use ($htmlUnTrust) {
            if (0 === error_reporting()) return false;
            throw new ErrorException($errstr . "\n\t" . $htmlUnTrust, 0, $errno, $errfile, $errline);
        });

        $config = HTMLPurifier_Config::create([
            'HTML.Allowed' => 'p[style],span[class|style|dir|lang],strong,i,s,u,blockquote,br,ol[style|start|reversed],ul[style],li',
            'CSS.AllowedProperties' => 'text-align,color,list-style-type',
            'Attr.AllowedClasses' => 'ck-list-bogus-paragraph',
            'HTML.DefinitionID' => 'basic_rich_editor',
            'HTML.DefinitionRev' => 1
        ]);
        if ($def = $config->maybeGetRawHTMLDefinition())
            $def->addAttribute('ol', 'reversed', 'Bool#reversed');
        //\HTMLPurifier_HTML5Config::createDefault();
        $purifier = new HTMLPurifier($config);
        $htmlTrust = $purifier->purify($htmlUnTrust);

        restore_error_handler();

        return $htmlTrust;
    }

    public static function PurifyTitleOfDocumentEditor(string $htmlUnTrust): string
    {
        set_error_handler(function ($errno, $errstr, $errfile, $errline) use ($htmlUnTrust) {
            if (0 === error_reporting()) return false;
            throw new ErrorException($errstr . "\n\t" . $htmlUnTrust, 0, $errno, $errfile, $errline);
        });

        $config = HTMLPurifier_Config::create([
            'HTML.Allowed' => 'h1',
            'CSS.AllowedProperties' => '',
            'Attr.AllowedClasses' => '',
        ]);
        $purifier = new HTMLPurifier($config);
        $htmlTrust = $purifier->purify($htmlUnTrust);

        restore_error_handler();

        return $htmlTrust;
    }

    public static function PurifyBodyOfDocumentEditor(string $htmlUnTrust): string
    {
        set_error_handler(function ($errno, $errstr, $errfile, $errline) use ($htmlUnTrust) {
            if (0 === error_reporting()) return false;
            throw new ErrorException($errstr . "\n\t" . $htmlUnTrust, 0, $errno, $errfile, $errline);
        });

        $allowed_tags = [
            'h1[style]', 'h2[style]', 'h3[style]', 'h4[style]', 'p[style]', //heading
            'span[class|style|dir|lang]',
            'mark[class]', //highlight,
            'strong', 'i', 's', 'u', //Basic Styles
            'ol[style|start|reversed|type]', 'ul[style]', 'li', //Lists
            'a[href|rel|target|download]', //Links
            'blockquote',
            'br', 'hr',
            'figure[class|style]', 'figcaption[data-placeholder]', 'img[class|style|alt|sizes|src|srcset]', //Image
            'table', 'thead', 'tbody', 'tr', 'td[colspan|rowspan|style]', 'th[colspan|rowspan|style]', //Tables
            'colgroup', 'col[style]',
            'div[class|style]' //Page Break
        ];

        $allowed_classes = [
            'marker-yellow,marker-green,marker-pink,marker-blue,pen-red,pen-green', //Highlight
            'image', 'image-inline', 'image_resized', 'image-style-side', 'image-style-align-left', //Image
            'image-style-align-right', 'image-style-block-align-center', 'image-style-block-align-left', //Image
            'image-style-block-align-right',
            'table', //Tables
            'page-break' //Page Break
        ];

        $allowed_css_props = [
            'text-align', 'color', 'font-family',
            'list-style-type', //Lists
            'margin-left', 'margin-right', //Indent
            'width', //Image
            'background-color', 'border,border-top,border-bottom,border-left,border-right', 'height', //Tables
            'padding,vertical-align', 'float', //Tables
            'page-break-after', 'display' //Page Break
        ];

        $config = HTMLPurifier_HTML5Config::create([
            'CSS.MaxImgLength' => null,
            'HTML.Allowed' => implode(',', $allowed_tags),
            'CSS.AllowedProperties' => implode(',', $allowed_css_props),
            'Attr.AllowedClasses' => implode(',', $allowed_classes),
            'Attr.AllowedFrameTargets' => ['_blank'],
            'Attr.AllowedRel' => ['nofollow', 'noreferrer', 'noopener'],
            'HTML.DefinitionID' => 'document_rich_editor',
            'HTML.DefinitionRev' => 1,
            'CSS.DefinitionID' => 'document_rich_editor_css',
            'CSS.DefinitionRev' => 1
        ]);

        if ($def = $config->maybeGetRawHTMLDefinition()) {
            $def->addAttribute('ol', 'reversed', 'Bool#reversed');
            $def->addAttribute('figcaption', 'data-placeholder', 'CDATA');
        }
        if ($def = $config->maybeGetRawCSSDefinition()) {
            $def->info['page-break-after'] = new HTMLPurifier_AttrDef_Enum(
                [
                    'auto',
                    'always',
                    'avoid',
                    'left',
                    'right',
                    'recto',
                    'verso'
                ]
            );
            $def->info['display'] = new HTMLPurifier_AttrDef_Enum(
                [
                    'inline',
                    'block',
                    'list-item',
                    'run-in',
                    'compact',
                    'marker',
                    'table',
                    'inline-block',
                    'inline-table',
                    'table-row-group',
                    'table-header-group',
                    'table-footer-group',
                    'table-row',
                    'table-column-group',
                    'table-column',
                    'table-cell',
                    'table-caption',
                    'none',
                    'flex'
                ]
            );
        }

        $purifier = new HTMLPurifier($config);
        $htmlTrust = $purifier->purify($htmlUnTrust);

        restore_error_handler();

        return $htmlTrust;
    }

    public static function PurifyHTMLForPDF(string $htmlUnTrust, array $extra_options = []): string
    {
        set_error_handler(function ($errno, $errstr, $errfile, $errline) use ($htmlUnTrust) {
            if (0 === error_reporting()) return false;
            throw new ErrorException($errstr . "\n\t" . $htmlUnTrust, 0, $errno, $errfile, $errline);
        });

        $allowed_tags = [
            'h1[style]', 'h2[style]', 'h3[style]', 'h4[style]', 'p[style]', //heading
            'span[class|style|dir|lang]',
            'mark[class]', //highlight,
            'strong', 'i', 's', 'u', //Basic Styles
            'ol[style|start|reversed|type]', 'ul[style]', 'li', //Lists
            'a[href|rel|target|download]', //Links
            'blockquote',
            'br', 'hr',
            'figure[class|style]', 'figcaption[data-placeholder]', 'img[class|style|width|alt|sizes|src|srcset]', //Image
            'table', 'thead', 'tbody', 'tr', 'td[colspan|rowspan|style]', 'th[colspan|rowspan|style]', //Tables
            'colgroup', 'col[style]',
            'div[class|style]' //Page Break
        ];

        $allowed_classes = [
            'marker-yellow,marker-green,marker-pink,marker-blue,pen-red,pen-green', //Highlight
            'image', 'image-inline', 'image_resized', 'image-style-side', 'image-style-align-left', //Image
            'image-style-align-right', 'image-style-block-align-center', 'image-style-block-align-left', //Image
            'image-style-block-align-right',
            'table', //Tables
            'page-break' //Page Break
        ];

        $allowed_css_props = [
            'text-align', 'color', 'font-family',
            'list-style-type', //Lists
            'margin-left', 'margin-right', //Indent
            'width', //Image
            'background-color', 'border,border-top,border-bottom,border-left,border-right', 'height', //Tables
            'padding,vertical-align', 'float', //Tables
            'page-break-after', 'display' //Page Break
        ];

        $config = HTMLPurifier_HTML5Config::create([
                'CSS.MaxImgLength' => null,
                'HTML.Allowed' => implode(',', $allowed_tags),
                'CSS.AllowedProperties' => implode(',', $allowed_css_props),
                'Attr.AllowedClasses' => implode(',', $allowed_classes),
                'HTML.DefinitionID' => 'document_rich_editor',
                'HTML.DefinitionRev' => 1,
                'CSS.DefinitionID' => 'document_rich_editor_css',
                'CSS.DefinitionRev' => 1
            ] + $extra_options
        );
        if ($def = $config->maybeGetRawHTMLDefinition()) {
            $def->addAttribute('ol', 'reversed', 'Bool#reversed');
            $def->addAttribute('figcaption', 'data-placeholder', 'CDATA');
        }
        if ($def = $config->maybeGetRawCSSDefinition()) {
            $def->info['page-break-after'] = new HTMLPurifier_AttrDef_Enum(
                [
                    'auto',
                    'always',
                    'avoid',
                    'left',
                    'right',
                    'recto',
                    'verso'
                ]
            );
            $def->info['display'] = new HTMLPurifier_AttrDef_Enum(
                [
                    'inline',
                    'block',
                    'list-item',
                    'run-in',
                    'compact',
                    'marker',
                    'table',
                    'inline-block',
                    'inline-table',
                    'table-row-group',
                    'table-header-group',
                    'table-footer-group',
                    'table-row',
                    'table-column-group',
                    'table-column',
                    'table-cell',
                    'table-caption',
                    'none',
                    'flex'
                ]
            );
        }

        $purifier = new HTMLPurifier($config);
        $htmlTrust = $purifier->purify($htmlUnTrust);

        restore_error_handler();

        return $htmlTrust;
    }
}