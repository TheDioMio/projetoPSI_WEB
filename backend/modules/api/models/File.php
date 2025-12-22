<?php

namespace backend\modules\api\models;

use Yii;

class File extends \common\models\File
{

    public function fields()
    {
        return [
            'id_file' => 'id',
            'id_animal' => 'animal_id',
            'file_address' => 'path',
        ];
    }

}
