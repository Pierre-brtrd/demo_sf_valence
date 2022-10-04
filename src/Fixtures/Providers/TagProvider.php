<?php

namespace App\Fixtures\Providers;

class TagProvider
{
    public function randomTag(): string
    {
        $tagList = [
            'Symfony',
            'Php',
            'Framework',
            'NodeJs',
            'VueJs',
            'Github',
            'Api',
            'CI/CD',
            'Frontend',
            'Backend',
            'WebDesign',
            'Maquette',
            'Data',
            'SQL',
        ];

        return $tagList[array_rand($tagList)];
    }
}
