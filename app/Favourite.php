<?php
namespace App;

use App\Traits\isUserPropertyChild;

class Favourite extends UserProperty {
    use isUserPropertyChild;

    const RELATION_TYPE = parent::TYPE_FAVOURITE;
}