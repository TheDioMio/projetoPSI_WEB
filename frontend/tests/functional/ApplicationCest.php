<?php

declare(strict_types=1);


namespace frontend\tests\Functional;

use common\models\Animal;
use common\models\Application;
use common\models\Listing;
use common\models\User;
use frontend\tests\FunctionalTester;

final class ApplicationCest
{
    public function _before(FunctionalTester $I): void
    {
        $this->candidate = $this->createValidUser();
        $I->amLoggedInAs($this->candidate);
        $owner = $this->createValidUser();

        $animalId = $I->haveRecord(Animal::class, [
            'name' => 'Bobi Candidatura',
            'animal_type_id' => 1,
            'breed_id' => 1,
            'age_id' => 1,
            'size_id' => 1,
            'user_id' => $owner->id,
        ]);

        $I->haveRecord(Listing::class, [
            'animal_id' => $animalId,
            'user_id'   => $owner->id,   // normalmente o dono do anúncio
            'views'     => 0,
            'status'    => Listing::STATUS_ACTIVE, // ou 1
            // 'description' => 'Anúncio de teste', // opcional
        ]);

        $this->animal = Animal::findOne($animalId);

        $I->amOnRoute('application/apply', ['animal_id' => $this->animal->id]);
    }

    public function testEmptyFormValidation(FunctionalTester $I)
    {
        $I->click('Submeter Candidatura');

        $I->see('Há erros no formulário:', '.alert-danger');

    }

    public function testSubmitValidApplication(FunctionalTester $I)
    {
        $I->amOnRoute('application/apply', ['animal_id' => $this->animal->id]);
        $I->see('Candidatura', 'h1');

        // Definimos todos os dados do formulário num array
        // Isto evita conflitos com os inputs hidden do Yii2
        $formData = [
            'Application[data][name]'      => 'Candidato Teste',
            'Application[data][age]'       => '28',
            'Application[data][contact]'    => '912912912',
            'Application[data][home]'       => '1', // Própria
            'Application[data][timeAlone]'  => '1', // 4 a 8 horas
            'Application[data][children]'   => '1', // Sim
            'Application[data][bills]'      => '1', // Sim
            'Application[data][followUp]'   => '1', // Sim
            'Application[data][motive]'     => 'Tenho condições excelentes para receber este animal e espaço disponível.',
        ];

        // Submetemos o formulário pelo ID e passamos os dados
        $I->submitForm('#apply-form', $formData);

        // Validações
        $I->dontSeeElement('.alert-danger');
    }


    private function createValidUser(): User
    {
        $user = new User();
        $user->name = 'UserTeste';
        $user->username = 'user_' . uniqid();
        $user->email = uniqid() . '@teste.pt';
        $user->status = User::STATUS_ACTIVE;
        $user->setPassword('123456789');
        $user->generateAuthKey();
        $user->save(false);

        // Atribuir Role 'user' (opcional, dependendo do seu RBAC)
        $auth = \Yii::$app->authManager;
        $role = $auth->getRole('user');
        if ($role) {
            $auth->assign($role, $user->id);
        }

        return $user;
    }}
