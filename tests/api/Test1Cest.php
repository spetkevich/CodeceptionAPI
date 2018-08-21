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

    public function checkBooksJson(\ApiTester $I)
    {
        $I->sendGET('books.json');
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType([
            'id' => 'integer',
            'title' => 'string|null',
            'author' => 'string|null',
            'views' => 'integer',
            'readers' => 'integer'
        ]);
    }

    public function checkDashboardJson(\ApiTester $I)
    {
        $I->sendGET('dashboard.json');
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $I->seeResponseJsonMatchesXpath('//dashboard/totalViews');
        $totalViews = $I->grabDataFromResponseByJsonPath('$.dashboard.totalViews')[0];
        $totalReaders = $I->grabDataFromResponseByJsonPath('$..dashboard.totalReaders')[0];
        $involvement = round(100 * $totalReaders / $totalViews, 2);
        $I->seeResponseMatchesJsonType(['dashboard' => [
            'totalViews' => 'integer',
            'totalReaders' => 'integer',
            'involvement' => 'float:=' . $involvement
        ]]);
    }

}