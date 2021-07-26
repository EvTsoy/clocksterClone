<?php

namespace App\Listeners;

use App\Conversation\Context;
use App\Events\FlowRunned;
use Log;

class SaveRunnedFlowToContext
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  FlowRunned  $event
     * @return void
     */
    public function handle(FlowRunned $event)
    {
        $user = $event->getUser();
        $flow = $event->getFlow();
        $state = $event->getState();

        Log::debug('SaveRunnedFlowToContext.handle', [
            'user' => $user->toArray(),
            'flow' => get_class($flow),
            'state' => $state,
        ]);

        Context::save($user, $flow, $state);
    }
}
