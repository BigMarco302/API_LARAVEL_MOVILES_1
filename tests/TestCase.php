<?php

namespace Tests;
use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase{

    protected bool $formatJsonApiDocument = true;

    public function json($method, $uri, array $data = [], array $headers = [], $options = 0)
    {
        $headers['accept'] = 'application/vnd.api+json';
        if($this->formatJsonApiDocument){
            $formattedData = $this->getFormattedData($uri, $data);
        }

        return parent::json($method, $uri,$formattedData ?? $data , $headers, $options);
    }

    public function postJson($uri, array $data = [], array $headers = [], $options = 0)
    {   
        $headers['content-type'] = 'application/vnd.api+json';
        return parent::postJson($uri,  $data,  $headers, $options );
    }

    public function patchJson($uri, array $data = [], array $headers = [], $options = 0)
    {   
        $headers['content-type'] = 'application/vnd.api+json';
        return parent::patchJson($uri,  $data,  $headers, $options );
    }
    /**
     * @param $uri
     * @param array $data
     * @return array
     */
    protected function getFormattedData($uri, array $data): array
    {
        $path = parse_url($uri)['path'];
        $type = (string) Str::of($path)->after('api/v1/')->before('/');
        $id = (string) Str::of($path)->after($type)->replace('/', '');

        return [
            'data' => array_filter([
                'type' => $type,
                'id' => $id,
                'attributes' => $data
            ])
        ];
    }
 }

