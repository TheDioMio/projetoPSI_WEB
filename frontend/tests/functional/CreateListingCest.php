<?php

declare(strict_types=1);


namespace frontend\tests\functional;

use common\models\User;
use frontend\tests\FunctionalTester;

final class CreateListingCest
{
    public function _before(FunctionalTester $I): void
    {
        $this->user = $this->createValidUser(true);
        $I->amLoggedInAs($this->user->id);

        $I->amOnRoute('listings/create-listing');
    }

    public function submitEmptyForm(FunctionalTester $I)
    {
        $I ->see('Criar Novo Anúncio');
        $I->click('#create-listing-form button[type=submit]');


        $I->seeElement('.invalid-feedback');
    }

    public function submitValidForm(FunctionalTester $I)
    {
        $I->amOnRoute('listings/create-listing');
        $I->see('Criar Novo Anúncio', 'h1');


        $formData = [
            'Animal[name]' => 'Bobi Teste',
            'Animal[description]' => 'Este é um animal de teste criado pelo Codeception. É muito dócil e amigável.',
            'Animal[animal_type_id]' => '1',
            'Animal[breed_id]' => '1',
            'Animal[age_id]' => '1',
            'Animal[size_id]' => '1',
            'Animal[location]' => 'Leiria, Portugal',
            'Animal[vaccination_id]' => '1',
            'Animal[neutered]' => '1',
            'Listing[description]' => 'Texto apelativo para o anúncio do Bobi Teste.',
            'Listing[status]' => '1', // Ex: Ativo
        ];


        $I->submitForm('#create-listing-form', $formData, 'create-button');


        $I->dontSeeElement('.invalid-feedback');
        $I->dontSee('cannot be blank');


        $I->see('Bobi Teste');
    }


    private function createValidUser(bool $save = false): User
    {
        $user = new User();
        $user->name = 'userTeste';
        $user->username = 'user_' . uniqid();
        $user->email = uniqid() . '@teste.pt';
        $user->status = User::STATUS_ACTIVE;
        $user->setPassword('123456789');
        $user->generateAuthKey();

        if ($save) {
            // Se o save retornar falso, o teste deve parar aqui para sabermos o porquê
            if (!$user->save(false)) {
                throw new \Exception("Erro ao gravar utilizador de teste!");
            }

            $auth = \Yii::$app->authManager;
            $role = $auth->getRole('user');

            if ($role) {
                // Importante: use (string)$user->id para garantir que o RBAC aceita o formato
                $auth->assign($role, $user->id);
            }
        }

        return $user;
    }
}
