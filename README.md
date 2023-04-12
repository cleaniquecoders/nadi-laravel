# Nadi Laravel Client

Nadi is a simple issue tracker for monitoring your application crashes. This package developed for Laravel framework.

## Installation

```bash
composer require cleaniquecoders/nadi-laravel
```

## Configuration

Register your account at [nadi](https://nadi.cleaniquecoders.com) then create:

1. API Key
2. An Application

Copy both API Key and Application Token then configure `.env`

```text
NADI_ENDPOINT="https:://nadi.cleaniquecoders.com/api"
NADI_DRIVER=http
NADI_KEY=<api-key>
NADI_TOKEN=<application-token>
```
