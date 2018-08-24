<?php

use Codeception\Util\HttpCode;

class Test1Cest
{

    public function _before(\ApiTester $I)
    {
    }

    public function _after(\ApiTester $I)
    {
    }

    private $totalViews;
    private $totalReaders;

    public function checkBooksJson(\ApiTester $I)
    {
        $I->sendGET('books.json');
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $this->totalViews = array_sum($I->grabDataFromResponseByJsonPath('$..views'));
        $this->totalReaders = array_sum($I->grabDataFromResponseByJsonPath('$..readers'));
        $I->seeResponseMatchesJsonType([
            'id' => 'integer',
            'title' => 'string|null',
            'author' => 'string|null',
            'views' => 'integer',
            'readers' => 'integer',
        ]);
        $I->seeResponseIsJson();
    }

    public function checkDashboardJson(\ApiTester $I)
    {
        $I->sendGET('dashboard.json');
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $I->seeResponseJsonMatchesXpath('//dashboard/totalViews');
        $involvement = round(100 * $this->totalReaders / $this->totalViews, 2);
        $I->seeResponseMatchesJsonType(['dashboard' => [
            'totalViews' => 'integer:=' . $this->totalViews,
            'totalReaders' => 'integer:=' . $this->totalReaders,
            'involvement' => 'integer:=' . $involvement,
        ]]);
    }

}