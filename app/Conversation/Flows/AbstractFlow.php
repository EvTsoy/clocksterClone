<?php

namespace App\Conversation\Flows;

use App\Events\FlowRunned;
use http\Exception\InvalidArgumentException;
use Log;
use Telegram;
use App\Models\User;
use Telegram\Bot\Api;
use App\Models\Message;

abstract class AbstractFlow
{
    protected $user;

    protected $message;

    protected $triggers = [];

    protected $states = ['first'];

    protected $context = [];

    protected $option;

    public function setUser(User $user)
    {
        $this->user = $user;
    }

    public function setMessage(Message $message)
    {
        $this->message = $message;
    }

    public function setOption($option)
    {
        $this->option = $option;
    }

    public function setContext($context)
    {
        $this->context = $context;
    }

    public function getStates()
    {
        return $this->states;
    }

    protected function telegram(): Api
    {
        return Telegram::bot();
    }

    public function run($state = null): bool
    {
        Log::debug(static::class . '.run', [
            'user' => $this->user->toArray(),
            'message' => $this->message->toArray(),
            'state' => $state
        ]);

        // Передано значение state
        if(!is_null($state)) {
            event(new FlowRunned($this->user, $this, $state));
            $this->$state();

            return true;
        }

        // Поиск по контексту
        $state = $this->findByContext();

        if(!is_null($state)) {
            event(new FlowRunned($this->user, $this, $state));
            $this->$state();

            return true;
        }

        // Поиск по триггерам
        $state = $this->findByTrigger();


        if(!is_null($state)) {
            event(new FlowRunned($this->user, $this, $state));
            $this->$state();

            return true;
        }

        $option = $this->getOption();
        if(!is_null($option)) {
            $this->$option();
            return true;
        }
        return false;
    }

    private function getOption()
    {
        if(!is_null($this->option)) {
            return $this->option;
        }
        return null;
    }

    private function findByContext()
    {
        $state = null;

        if(
            isset($this->context['flow']) &&
            isset($this->context['state']) &&
            class_exists($this->context['flow']) &&
            method_exists(app($this->context['flow']), $this->context['state'])
        )
        {
            $flow = $this->getFlow($this->context['flow']);

            $states = $flow->getStates();
            $currentState = collect($states)->search($this->context['state']);

            $nextState = $currentState + 1;
            if(isset($states[$nextState])) {
                $flow->run($states[$nextState]);

                return $states[$nextState];
            }
        }

        return null;
    }

    private function findByTrigger()
    {
        $state = null;

        foreach ($this->triggers as $trigger)
        {
            if(hash_equals($trigger, $this->message->message_text))
            {
                $state = 'first';
            }
        }
        return $state;
    }

    protected function jump(string $flow, string $state = 'first')
    {
        $this->getFlow($flow)->run($state);
    }

    private function getFlow(string $flow) : AbstractFlow
    {
        if(!class_exists($flow)) {
            throw new InvalidArgumentException('Flow does not exist');
        }

        $flow = app($flow);

        $flow->setUser($this->user);
        $flow->setMessage($this->message);
        $flow->setContext($this->context);

        return $flow;
    }

    abstract protected function first();
}
