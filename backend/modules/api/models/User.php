<?php

namespace backend\modules\api\models;

class User extends \common\models\User{

    /**
     * Override over the fields of the MissingAnimal model
     * @return array|false
     */
    public function fields(){

        $fields = parent::fields();

        unset(
            $fields['auth_key'],
            $fields['password_hash'],
            $fields['password_reset_token'],
            $fields['created_at'],
            $fields['updated_at'],
            $fields['verification_token'],
            $fields['address_id'],
            $fields['status']
        );

        return array_merge($fields, ['address', 'fullName']);
    }
}