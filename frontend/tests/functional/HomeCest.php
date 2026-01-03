<?php

namespace frontend\tests\functional;

use frontend\tests\FunctionalTester;

class HomeCest
{
    public function checkOpen(FunctionalTester $I)
    {
        $I->amOnRoute(\Yii::$app->homeUrl);
        $I->see('PetPanion');
        $I->seeLink('Início');
        $I->seeLink('Animais');
        $I->seeLink('Contactos');
    }
}