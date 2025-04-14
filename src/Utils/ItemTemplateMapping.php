<?php

namespace App\Utils;

class ItemTemplateMapping
{
    public static function getMapping(): array
    {
        return [
            'Tableau' => 'tableau/index.html.twig',
            'Description' => 'description/index.html.twig',
            'Projet' => 'projet/index.html.twig',
            'Soumission' => 'soumission/index.html.twig',
            'Financement' => 'financement/index.html.twig',
            'Methodologie' => 'methodologie/index.html.twig'

        ];
    }
}