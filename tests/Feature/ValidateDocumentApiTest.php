<?php

namespace Tests\Feature;

use App\Http\Middleware\ValidateDocumentMiddleware;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Route;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ValidateDocumentApiTest extends TestCase
{
    use RefreshDatabase;
    protected function setUp(): void
    {
        parent::setUp();

        $this->formatJsonApiDocument = false;
        Route::any('test_document',function(){
            return 'OK';
        })->middleware(ValidateDocumentMiddleware::class);

    }
    #[Test] // data es requerido
    public function data_is_require()
    {
        $this->postJson('test_document',[])
        ->assertJsonApiValidationErrors('data');

        $this->patchJson('test_document',[])
        ->assertJsonApiValidationErrors('data');
    }
    #[Test] // verificamos que data sea un array
    public function data_is_array()
    {
        $this->postJson('test_document',[
            'data'=>'string'
        ])
        ->assertJsonApiValidationErrors('data');

        $this->patchJson('test_document',[
            'data'=>'string'
        ])
        ->assertJsonApiValidationErrors('data');
    }

    #[Test] // verificamos que data tenga type o que no este vacio
    public function data_is_type_require()
    {
        $this->postJson('test_document',[
            'attributes'=>[]
        ])
        ->assertJsonApiValidationErrors('data.type');

        $this->patchJson('test_document',[
            'attributes'=>[]
        ])
        ->assertJsonApiValidationErrors('data.type');
    }



    #[Test] // verificamos que data type sea un string
    public function data_type_must_be_a_string()
    {
        $this->postJson('test_document',[
            'data'=>[
                'type'=>1
            ]
        ])
        ->assertJsonApiValidationErrors('data.type');

        $this->patchJson('test_document',[
            'data'=>[
                'type'=>1
            ]
        ])
        ->assertJsonApiValidationErrors('data.type');
    }

    #[Test] // verificamos que attributes sea obligatorio
    public function data_attibute_is_require()
    {
        $this->postJson('test_document',[
            'data'=>[
                'type'=>'string'
            ]
        ])
        ->assertJsonApiValidationErrors('data.attributes');

        $this->patchJson('test_document',[
            'data'=>[
                'type'=>'string'
            ]
        ])
        ->assertJsonApiValidationErrors('data.attributes');
    }

    #[Test] // verificamos que attributes sea obligatorio
    public function data_attibute_must_be_an_array()
    {
        $this->postJson('test_document',[
            'data'=>[
                'attributes'=>'string'
            ]
        ])
        ->assertJsonApiValidationErrors('data.attributes');

        $this->patchJson('test_document',[
            'data'=>[
                'attributes'=>'string'
            ]
        ])
        ->assertJsonApiValidationErrors('data.attributes');
    }
    #[Test] // verificamos que attributes sea obligatorio
    public function data_id_is_require_PATCH()
    {
        $this->patchJson('test_document',[
            'data'=>[
                'type'=>'string',
                'attributes'=>[
                    'name'=>'test'
                ]
            ]
        ])
        ->assertJsonApiValidationErrors('data.id');
    }
    #[Test] // verificamos que attributes sea obligatorio
    public function data_id_must_be_a_string_PATCH()
    {
        $this->patchJson('test_document',[
            'data'=>[
                'id'=>1,
                'type'=>'string',
                'attributes'=>[
                    'name'=>'test'
                ]
            ]
        ])
        ->assertJsonApiValidationErrors('data.id');
    }

    #[Test] // verificamos que attributes sea obligatorio
    public function only_accepts_valid_json_api_document()
    {
        $this->postJson('test_document',[
            'data'=>[
                'type'=>'string',
                'attributes'=>[
                    'name'=>'test'
                ]
            ]
        ])
        ->assertSuccessful();

        $this->patchJson('test_document',[
            'data'=>[
                'id'=>'1',
                'type'=>'string',
                'attributes'=>[
                    'name'=>'test'
                ]
            ]
        ])
        ->assertSuccessful();
    }
}
