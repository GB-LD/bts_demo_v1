<?php

namespace App\Data;

class SearchData
{
    /**
     * @var int
     */
    public $page = 1;

    /**
     * @var string
     */
    public $q = '';

    /**
     * @var string
     */
    public $ville = '';

    /**
     * @var array
     */
    public $categories = [];

    /**
     * @var array
     */
    public $matieres = [];
}
