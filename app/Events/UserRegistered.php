<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;

class UserRegistered
{
    use SerializesModels;

    /**
     * The authenticated user.
     *
     * @var \Illuminate\Contracts\Auth\Authenticatable
     */
    public $user;

    public $role;

    /**
     * Create a new event instance.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @return void
     */
    public function __construct($user, $role)
    {
        $this->user = $user;
        $this->role = $role;
    }
}