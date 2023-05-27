<?php

namespace CleaniqueCoders\NadiLaravel\Data;

use CleaniqueCoders\Nadi\Data\Entry as DataEntry;
use CleaniqueCoders\NadiLaravel\Concerns\InteractsWithMetric;
use Illuminate\Support\Facades\Auth;
use Throwable;

class Entry extends DataEntry
{
    use InteractsWithMetric;

    /**
     * The currently authenticated user, if applicable.
     *
     * @var mixed
     */
    public $user;

    /**
     * Create a new incoming entry instance.
     *
     * @param  string|null  $uuid
     * @return void
     */
    public function __construct($type, array $content, $uuid = null)
    {
        parent::__construct($type, $content, $uuid);

        $this->registerMetrics();

        try {
            if (Auth::hasResolvedGuards() && Auth::hasUser()) {
                $this->user(Auth::user());
            }
        } catch (Throwable $e) {
            // Do nothing.
        }
    }

    /**
     * Set the currently authenticated user.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @return $this
     */
    public function user($user)
    {
        $this->user = $user;

        $this->content = array_merge($this->content, [
            'user' => [
                'id' => $user->getAuthIdentifier(),
                'name' => $user->name ?? null,
                'email' => $user->email ?? null,
            ],
        ]);

        $this->tags(['Auth:'.$user->getAuthIdentifier()]);

        return $this;
    }
}
