<?php

namespace App\Conversation;

use App\Conversation\Flows\AbstractConversation;
use App\Conversation\Flows\City;
use App\Conversation\Flows\Contacts;
use App\Conversation\Flows\DateOfBirth;
use App\Conversation\Flows\Fullname;
use App\Conversation\Flows\Profile;
use App\Conversation\Flows\Welcome;
use App\Models\Message;
use App\Models\User;

class Conversation extends AbstractConversation

{
    public function start(User $user, Message $message, $option)
    {
        $this->user = $user;

        $this->message = $message;

        $state = $this->getStatus($user);

        if(hash_equals($state->status, 'first') ||
            hash_equals($message->message_text, '/start'))
        {
            $this->changeStatus('first');
            $this->sendMessage(Welcome::class);
        }

        if(hash_equals($option, 'accepted'))
        {
            $this->sendMessage(Fullname::class);
            $this->changeStatus('intro');
        }

        if(hash_equals($state->status, 'intro'))
        {
            $this->storeData(Fullname::class);
            $this->changeStatus('contacts');

            $this->sendMessage(Contacts::class);
        }

        if(hash_equals($state->status, 'phone')) {
            $this->sendMessage(Contacts::class);
        }

        if(hash_equals($option, 'contacts'))
        {
            $this->changeStatus('city');
            $this->sendMessage(City::class);
        }

        if(hash_equals($state->status, 'city'))
        {
            $this->changeStatus('next');
            $this->sendMessage(City::class);
        }

        if(str_contains($option, 'city.')) {
            $this->city = str_replace('city.', '', $option);

            $this->storeCity(City::class);

            $this->sendMessage(DateOfBirth::class);
            $this->changeStatus('dateOfBirth');
        }

        if(hash_equals($state->status, 'dateOfBirth'))
        {
            $this->storeData(DateOfBirth::class);
            $this->changeStatus('profile');

            $this->sendMessage(Profile::class);
            $this->changeStatus('registered');
        }

        if(hash_equals($option, 'profile.data'))
        {
            $this->showProfile(Profile::class);
        }

        if(hash_equals($state->status, 'editName'))
        {
            $this->sendMessage(Fullname::class);
            $this->changeStatus('editedName');
        }

        if(hash_equals($state->status, 'editedName'))
        {
            $this->storeData(Fullname::class);
            $this->showProfile(Profile::class);
            $this->changeStatus('registered');
        }
    }
}
