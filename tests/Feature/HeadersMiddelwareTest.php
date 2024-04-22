<?php

namespace Tests\Feature;

use App\Http\Middleware\ValidateHeader;
use App\Http\Middleware\ValidateJsonApiHeaders;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Route;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class HeadersMiddelwareTest extends TestCase
{
    use RefreshDatabase;
    protected function setUp(): void
    {
        parent::setUp();
        Route::any('test_route',function(){
            return 'OK';
        })->middleware(ValidateHeader::class);

    }

    #[Test]
    public function header_POST(){
        $this->post('test_route',[],[
            'accept'=>'application/vnd.api+json'
        ])->assertStatus(415);

        $this->post('test_route',[],[
            'accept'=>'application/vnd.api+json',
            'content-type' =>'application/vnd.api+json'
        ])->assertSuccessful();

    }
    #[Test]
    public function header_PATCH(){
        $this->patch('test_route',[],[
            'accept'=>'application/vnd.api+json'
        ])->assertStatus(415);

        $this->patch('test_route',[],[
            'accept'=>'application/vnd.api+json',
            'content-type' =>'application/vnd.api+json'
        ])->assertSuccessful();

    }
    #[Test]
    public function resp_header(){
        $this->get('test_route',[
            'accept'=>'application/vnd.api+json',
        ])->assertHeader('content-type','application/vnd.api+json');

    }
    #[Test]
    public function content_header_empty(){
        Route::any('empty_response',function(){
            return response()->noContent();
        })->middleware(ValidateHeader::class);

        $this->get('empty_response',[
            'accept'=>'application/vnd.api+json',
        ])->assertHeaderMissing('content-type');

        $this->post('empty_response',[],[
            'accept'=>'application/vnd.api+json',
            'content-type' =>'application/vnd.api+json'
        ])->assertHeaderMissing('content-type');

        $this->patch('empty_response',[],[
            'accept'=>'application/vnd.api+json',
            'content-type' =>'application/vnd.api+json'
        ])->assertHeaderMissing('content-type');

        $this->delete('empty_response',[],[
            'accept'=>'application/vnd.api+json',
            'content-type' =>'application/vnd.api+json'
        ])->assertHeaderMissing('content-type');
    }
}
