<?php

namespace App\Events;


use App\Conversation\Flows\AbstractFlow;
use App\Models\User;
use Illuminate\Queue\SerializesModels;

class FlowRunned
{
    use SerializesModels;

    protected $user;
    protected $flow;
    protected $state;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, AbstractFlow $flow, string $state)
    {
        $this->user = $user;
        $this->flow = $flow;
        $this->state = $state;
    }

    public function getFlow(): AbstractFlow
    {
        return $this->flow;
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function getUser(): User
    {
        return $this->user;
    }

}
