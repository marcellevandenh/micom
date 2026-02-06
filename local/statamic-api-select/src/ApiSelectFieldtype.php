<?php

namespace Surge\ApiSelectFieldtype;

use Statamic\Fields\Fieldtype;
use Illuminate\Support\Facades\Cache;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class ApiSelectFieldtype extends Fieldtype
{
    protected static $title = 'API Select';

    protected $icon = 'select';

    protected function configFieldItems(): array
    {
        return [
            'placeholder' => [
                'display' => __('Placeholder'),
                'instructions' => __('statamic::fieldtypes.select.config.placeholder'),
                'type' => 'text',
                'default' => '',
                'width' => 50,
            ],
            'endpoint_type' => [
                'display' => __('Endpoint Type'),
                'type' => 'select',
                'options' => [
                    /*'config' => 'Config',*/
                    'url' => 'URL',
                ],
                'default' => 'url',
                'width' => 25,
                'required' => true,
            ],
            'endpoint' => [
                'display' => __('Endpoint'),
                'type' => 'text',
                /*'placeholder' => __('URL / Config "dot" syntax variable.'),*/
                'placeholder' => __('Relative URL to local APP URL'),
                'width' => 75,
                'required' => true,
            ],
            'cache_minutes' => [
                'display' => __('Cache Duration'),
                'instructions' => __('How long API results should be cached for in minutes.'),
                'type' => 'text',
                'input_type' => 'number',
                'default' => 0,
                'width' => 25,
            ],
            'data_set_key' => [
                'display' => __('Data Set Key'),
                'instructions' => __('If your data set isn\'t top-level, you can define it\'s location.'),
                'type' => 'text',
                'placeholder' => 'data.users',
                'width' => 25,
            ],
            'item_key' => [
                'display' => __('Item Key'),
                'instructions' => __('Define the unique identifier to be used as the option value.'),
                'type' => 'text',
                'placeholder' => 'id',
                'width' => 25,
                'required' => true,
            ],
            'item_label' => [
                'display' => __('Item Label'),
                'instructions' => __('Define the value to be used as the option label.'),
                'type' => 'text',
                'placeholder' => 'name',
                'width' => 25,
                'required' => true,
            ],
            'clearable' => [
                'display' => __('Clearable'),
                'instructions' => __('statamic::fieldtypes.select.config.clearable'),
                'type' => 'toggle',
                'default' => false,
                'width' => 25,
            ],
            'multiple' => [
                'display' => __('Multiple'),
                'instructions' => __('statamic::fieldtypes.select.config.multiple'),
                'type' => 'toggle',
                'default' => false,
                'width' => 25,
            ],
            'searchable' => [
                'display' => __('Searchable'),
                'instructions' => __('statamic::fieldtypes.select.config.searchable'),
                'type' => 'toggle',
                'default' => true,
                'width' => 25,
            ],
            'cast_booleans' => [
                'display' => __('Cast Booleans'),
                'instructions' => __('statamic::fieldtypes.select.config.cast_booleans'),
                'type' => 'toggle',
                'default' => false,
                'width' => 25,
            ],
        ];
    }

    public function preload()
    {
        return [
            'options' => $this->getOptions(),
        ];
    }

    public function augment($value)
    {
        $data = collect($this->getData());
        $key = $this->config('item_key');

        $values = $data
            ->whereIn($key, $value);

        if (!is_array($value)) {
            return $values->first();
        }

        return $values->all();
    }

    public function preProcess($value)
    {
        if ($this->config('cast_booleans')) {
            if ($value === true) {
                return 'true';
            } elseif ($value === false) {
                return 'false';
            }
        }

        return $value;
    }

    public function preProcessIndex($value)
    {
        $data = collect($this->getData());
        $key = $this->config('item_key');
        $label = $this->config('item_label');

        return $data
            ->whereIn($key, $value)
            ->implode($label, ', ');
    }

    public function process($value)
    {
        if ($this->config('cast_booleans')) {
            if ($value === 'true') {
                return true;
            } elseif ($value === 'false') {
                return false;
            }
        }

        return $value;
    }

    private function getOptions()
    {
        $data = $this->getData();
        $dsKey = $this->config('data_set_key');

        return collect(data_get($data, $dsKey))
            ->mapWithKeys(function ($option) {
                $key = $this->config('item_key');
                $label = $this->config('item_label');

                return [ data_get($option, $key) => data_get($option, $label) ];
            })
            ->all();
    }
 
    // Original before Guzzle workaraound
    // private function getData()
    // {
    //     $key = $this->handle() . $this->config('endpoint');
    //     $minutes = $this->config('cache_minutes');

    //     if (!$data = Cache::get($key)) {
    //         $response = app(Client::class)->get($this->getEndpoint());
    //         Log::debug("RESP:". json_encode($response));

    //         $data = json_decode((string) $response->getBody(), true);
    //         Log::debug("DATA:". $data);
    //         if ($minutes > 0) {
    //             Cache::put($key, $data, now()->addMinutes($minutes));
    //         }
    //     }

    //     return $data;
    // }

    private function getData()
    {
        $key = $this->handle() . $this->config('endpoint');
        $minutes = $this->config('cache_minutes');

        if (!$data = Cache::get($key)) {
            // ✅ Create Guzzle client with conditional SSL verification
            $client = new Client([
                'verify' => !app()->environment('local'), // skip SSL in local
                'timeout' => 10, // optional: avoid hanging requests
            ]);

            try {
                $response = $client->get($this->getEndpoint());
                Log::debug("RESP:" . $response->getStatusCode());

                $data = json_decode((string) $response->getBody(), true);
                Log::debug("DATA:" . json_encode($data));

                if ($minutes > 0) {
                    Cache::put($key, $data, now()->addMinutes($minutes));
                }
            } catch (\Exception $e) {
                Log::error("API Fetch Failed: " . $e->getMessage());
                return [];
            }
        }

        return $data;
    }

    private function getEndpoint()
    {
        $endpoint = $this->config('endpoint');
        return env('APP_URL') . $endpoint;
        /*switch ($this->config('endpoint_type')) {
            case 'config':
                Log::debug("ENDPOINT:". config($endpoint));
                return config($endpoint);
            default:
                return env('APP_URL') . $endpoint;
        }*/
    }
}
