<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Config sets
    |--------------------------------------------------------------------------
    |
    | Overrides the package's default set (vendor/stevebauman/purify/config/purify.php).
    | Only 'configs' is redefined here; every other key (definitions, serializer, etc.)
    | falls back to the package default via Laravel's config merge.
    |
    */

    'configs' => [

        'default' => [
            'Core.Encoding' => 'utf-8',
            'HTML.Doctype' => 'HTML 4.01 Transitional',
            'HTML.Allowed' => 'h1,h2,h3,h4,h5,h6,b,u,strong,i,em,s,del,a[href|title],ul,ol,li[class],p[style|class],br,span,img[width|height|alt|src],blockquote',
            'HTML.ForbiddenElements' => '',
            'CSS.AllowedProperties' => 'font,font-size,font-weight,font-style,font-family,text-decoration,padding-left,color,background-color,text-align',
            'AutoFormat.AutoParagraph' => false,
            'AutoFormat.RemoveEmpty' => false,

            // The Quill editor (training ticket notes, instructor notes, event
            // descriptions, etc.) encodes indent levels as ql-indent-N classes on
            // <p>/<li> rather than inline styles. Without an explicit allow-list,
            // HTMLPurifier strips every class attribute, which silently removes
            // all indentation from submitted content. Only these known Quill
            // classes are permitted — nothing else can be injected via class.
            'Attr.AllowedClasses' => 'ql-indent-1,ql-indent-2,ql-indent-3,ql-indent-4,ql-indent-5,ql-indent-6,ql-indent-7,ql-indent-8',
        ],

    ],

];
