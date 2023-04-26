<?php

namespace Tests\Unit;

//use PHPUnit\Framework\TestCase;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use App\Models\Item;
use Illuminate\Http\Response;


class ApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_item_table_has_expected_columns()
    {
        $this->assertTrue(
            Schema::hasColumns('item', [
                'id','name', 'phone', 'key', 'history', 'created_at', 'updated_at',
            ])
        );
    }

    public function testUnauthorizedUser() {

        $this->json('get', "api/item/list")
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function testItemIsAddedSuccessfully() {
    
        $item = [
            'name' => 'Первый',
            'phone'=> '1(111)111-11-11',
            'key'  => '111111111111111'
        ];
        $token = 'Bearer ' . config('apitokens')[0];
        $this->json('post', 'api/item', $item, ['HTTP_AUTHORIZATION' => $token])
             ->assertStatus(Response::HTTP_CREATED)
             ->assertJsonStructure(
                 [
                    'id',
                    'name',
                    'phone',
                    'key',
                    'created_at',
                    'updated_at'
                 ]
             );
        $this->assertDatabaseHas('item', $item);
    }

    public function testItemListIsShownCorrectly() {
        $item = Item::create(
            [
                'name' => 'Первый',
                'phone'=> '1(111)111-11-11',
                'key'  => '111111111111111'
            ]
        );
        $token = 'Bearer ' . config('apitokens')[0];    
        $this->json('get', "api/item/list", [], ['HTTP_AUTHORIZATION' => $token])
            ->assertStatus(Response::HTTP_OK)
            ->assertExactJson([
                    [
                        'id' => $item->id,
                        'name' => $item->name,
                        'phone'=> $item->phone,
                        'key'  => $item->key,
                        'history' => null,
                        'created_at' => $item->created_at,
                        'updated_at' => $item->created_at,
                    ]
                ]
            );
    }

    public function testItemIsShownCorrectly() {
        $item = Item::create(
            [
                'name' => 'Первый',
                'phone'=> '1(111)111-11-11',
                'key'  => '111111111111111'
            ]
        );
        $token = 'Bearer ' . config('apitokens')[0];
        $this->json('get', "api/item/$item->id", [], ['HTTP_AUTHORIZATION' => $token])
            ->assertStatus(Response::HTTP_OK)
            ->assertExactJson(
                [
                    'id' => $item->id,
                    'name' => $item->name,
                    'phone'=> $item->phone,
                    'key'  => $item->key,
                    'history' => null,
                    'created_at' => $item->created_at,
                    'updated_at' => $item->created_at,
                ]
            );
    }

    public function testUpdateItemReturnsCorrectData() {
        $item = Item::create(
            [
                'name' => 'Первый',
                'phone'=> '1(111)111-11-11',
                'key'  => '111111111111111'
            ]
        );

        $history = json_encode([
            'key'  => '111111111111111',
            'name' => 'Первый',
            'phone'=> '1(111)111-11-11',
            'updated_at' => $item->updated_at
        ]);

        $update = [
            'name' => 'Первый новый',
            'phone'=> '2(222)222-22-22',
            'key'  => '222222222222222'
        ];
        $token = 'Bearer ' . config('apitokens')[0];    
        $this->json('put', "api/item/$item->id", $update, ['HTTP_AUTHORIZATION' => $token])
            ->assertStatus(Response::HTTP_OK)
            ->assertExactJson(
                [
                    'id' => $item->id,
                    'name' => $update['name'],
                    'phone'=> $update['phone'],
                    'key'  => $update['key'],
                    'history' => [
                        json_decode($history)
                    ],
                    'created_at' => $item->created_at,
                    'updated_at' => $item->created_at,
                ]
            );
    }
    
    public function testItemIsDeleted() {
        $itemData =
            [
                'name' => 'Первый',
                'phone'=> '1(111)111-11-11',
                'key'  => '111111111111111'
            ];
        $item = Item::create($itemData);
        $token = 'Bearer ' . config('apitokens')[0];
        $this->json('delete', "api/item/$item->id", [], ['HTTP_AUTHORIZATION' => $token])
             ->assertNoContent();
        $this->assertDatabaseMissing('item', $itemData);
    }

    public function testItemAddValidation() {
        $item = [
            'name' => 'Первый',
            'phone'=> '1(111)111-11-11',
            'key'  => ''
        ];
        $token = 'Bearer ' . config('apitokens')[0];
        $this->json('post', 'api/item', $item, ['HTTP_AUTHORIZATION' => $token])
             ->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    public function testUpdateItemValidation() {
        $item = Item::create(
            [
                'name' => 'Первый',
                'phone'=> '1(111)111-11-11',
                'key'  => '111111111111111'
            ]
        );

        $update = [
            'name' => 'Первый новый',
            'phone'=> '2(222)222-22-22',
            'key'  => ''
        ];
        $token = 'Bearer ' . config('apitokens')[0];    
        $this->json('put', "api/item/$item->id", $update, ['HTTP_AUTHORIZATION' => $token])
            ->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    public function testItemIsUnfound() {
        $item = Item::create(
            [
                'name' => 'Первый',
                'phone'=> '1(111)111-11-11',
                'key'  => '111111111111111'
            ]
        );
        $token = 'Bearer ' . config('apitokens')[0];    
        $this->json('get', "api/item/0", [], ['HTTP_AUTHORIZATION' => $token])
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function testUpdateItemReturnsNotFound() {
        $item = Item::create(
            [
                'name' => 'Первый',
                'phone'=> '1(111)111-11-11',
                'key'  => '111111111111111'
            ]
        );

        $update = [
            'name' => 'Первый новый',
            'phone'=> '2(222)222-22-22',
            'key'  => '222222222222222'
        ];
        $token = 'Bearer ' . config('apitokens')[0];    
        $this->json('put', "api/item/0", $update, ['HTTP_AUTHORIZATION' => $token])
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function testItemIsNotFoundAtDestroy() {
        $itemData =
            [
                'name' => 'Первый',
                'phone'=> '1(111)111-11-11',
                'key'  => '111111111111111'
            ];
        $item = Item::create(
            $itemData
        );
        $token = 'Bearer ' . config('apitokens')[0];
        $this->json('delete', "api/item/0", [], ['HTTP_AUTHORIZATION' => $token])
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }
}
