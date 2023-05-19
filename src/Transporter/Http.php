<?php

namespace CleaniqueCoders\NadiLaravel\Transporter;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http as Client;

class Http implements Contract
{
    protected PendingRequest $client;

    public function __construct()
    {
        $this->client = Client::withHeaders([
            'Accept' => 'application/vnd.nadi.'.config('nadi.version').'+json',
            'Authorization' => 'Bearer '.config('nadi.key'),
            'Nadi-Token' => config('nadi.token'),
        ]);
    }

    public function test()
    {
        $response = $this->client->post($this->url('test'));

        return $response->status() == 200;
    }

    public function verify()
    {
        $response = $this->client->post($this->url('verify'));

        return $response->status() == 200;
    }

    public function send(array $data)
    {
        return $this->client->post($this->url('record'), $data);
    }

    public function url(string $endpoint)
    {
        return rtrim(config('nadi.endpoint'), '/').'/'.trim($endpoint, '/');
    }
}
