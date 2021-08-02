<?php

namespace App\Conversation;

use App\Conversation\Flows\AbstractConversation;
use App\Conversation\Flows\CheckIn;
use App\Conversation\Flows\City;
use App\Conversation\Flows\Contacts;
use App\Conversation\Flows\DateOfBirth;
use App\Conversation\Flows\Fullname;
use App\Conversation\Flows\Notification;
use App\Conversation\Flows\Profile;
use App\Conversation\Flows\Search;
use App\Conversation\Flows\Welcome;
use App\Models\Message;
use App\Models\User;

class Conversation extends AbstractConversation


{
    protected $ddmmyyyy = "/^\s*(3[01]|[12][0-9]|0?[1-9])\.(1[012]|0?[1-9])\.((?:19|20)\d{2})\s*$/";

    public function start(User $user, Message $message, $option)
    {
        $this->user = $user;

        $this->message = $message;

        $state = $this->getStatus($user);

        // Самое первое сообщение
        if(hash_equals($state->status, 'first') ||
            hash_equals($message->message_text, '/start'))
        {
            $this->changeStatus('first');
            $this->sendMessage(Welcome::class);
        }

        // Для реализации поиска работы
        if(hash_equals($message->message_text, '/search'))
        {
            $this->sendMessage(Search::class);
        }

        // Принятие условий и запрос имени
        if(hash_equals($option, 'accepted'))
        {
            $this->sendMessage(Fullname::class);
            $this->changeStatus('intro');
        }

        // Сохраняем имя и спрашиваем телефон
        if(hash_equals($state->status, 'intro'))
        {
            $this->storeData(Fullname::class);

            $this->sendMessage(Contacts::class);
            $this->changeStatus('phone');
        }

        // Если отправляется что-то помимо телефона
        if(hash_equals($state->status, 'phone') && $option !== 'contacts') {
            $this->sendMessage(Contacts::class);
        }

        // Если телефон отправлен то сохраняем и спрашиваем город
        if(hash_equals($option, 'contacts'))
        {
            $this->changeStatus('city');
            $this->sendMessage(City::class);
        }

        // Если отправляется что-то помимо города
        if(hash_equals($state->status, 'city') && !str_contains($option, 'city.'))
        {
            $this->sendMessage(City::class);
        }

        // Если нужно сохранить кастомный город
        if(hash_equals($state->status, 'customCity') && hash_equals($option, 'customCity'))
        {
            $this->sendCustomMessage(City::class);
        }

        // Сохраняем город и спрашиваем про дату рождения
        if(hash_equals($state->status, 'customCity') && !hash_equals($option, 'customCity'))
        {
            $this->city = $message->message_text;
            $this->storeCity(City::class);

            $this->sendMessage(DateOfBirth::class);
            $this->changeStatus('dateOfBirth');
        }

        // Сохраняем город и спрашиваем про дату рождения
        if(str_contains($option, 'city.') && $state->status !== 'editedCity') {
            $this->city = str_replace('city.', '', $option);

            $this->storeCity(City::class);

            $this->sendMessage(DateOfBirth::class);
            $this->changeStatus('dateOfBirth');
        }

        // Проверка введенной даты рождения
        if(hash_equals($state->status, 'dateOfBirth') && !preg_match($this->ddmmyyyy, $message->message_text))
        {
            $this->sendMessage(DateOfBirth::class);
        }

        // Проверка введенной даты рождения
        if(hash_equals($state->status, 'dateOfBirth') && preg_match($this->ddmmyyyy, $message->message_text))
        {
            $this->storeData(DateOfBirth::class);
            $this->changeStatus('profile');

            $this->sendMessage(Profile::class);
            $this->changeStatus('registered');
        }

        // Показываем профиль
        if(hash_equals($option, 'profile.data'))
        {
            $this->showProfile(Profile::class);
        }

        // Вводим информацию о приходе
        if(hash_equals($option, 'checkin.data'))
        {
            $this->sendMessage(CheckIn::class);
            $this->changeStatus('checkin');
        }

        // Вывод информации о приходе
        if(hash_equals($option, 'allCheckin.data'))
        {
            $this->sendAllCheckIns(CheckIn::class);

            $this->sendMessage(Profile::class);
            $this->changeStatus('registered');
        }

        // Вводим информацию о приходе
        if(hash_equals($state->status, 'checkin'))
        {
            $this->checkIn(Notification::class);

            $this->sendMessage(Profile::class);
            $this->changeStatus('registered');
        }

        // Редактирование имени
        if(hash_equals($state->status, 'editName'))
        {
            $this->sendMessage(Fullname::class);
            $this->changeStatus('editedName');
        }

        // Редактирование имени
        if(hash_equals($state->status, 'editedName'))
        {
            $this->storeData(Fullname::class);
            $this->sendMessage(Notification::class);
            $this->showProfile(Profile::class);
            $this->changeStatus('registered');
        }

        // Редактирование даты рождения
        if(hash_equals($state->status, 'editYear'))
        {
            $this->sendMessage(DateOfBirth::class);
            $this->changeStatus('editedYear');
        }

        // Редактирование даты рождения
        if(hash_equals($state->status, 'editedYear') && !preg_match($this->ddmmyyyy, $message->message_text))
        {
            $this->sendMessage(DateOfBirth::class);
        }

        // Редактирование даты рождения
        if(hash_equals($state->status, 'editedYear') && preg_match($this->ddmmyyyy, $message->message_text))
        {
            $this->storeData(DateOfBirth::class);
            $this->sendMessage(Notification::class);
            $this->showProfile(Profile::class);
            $this->changeStatus('registered');
        }

        // Редактирование Город
        if(hash_equals($state->status, 'editCity'))
        {
            $this->sendMessage(City::class);
            $this->changeStatus('editedCity');
        }

        // Редактирование Город
        if(hash_equals($state->status, 'editedCity') && !hash_equals($option, 'customCity'))
        {
            if(str_contains($option, 'city.'))
            {
                $this->city = str_replace('city.', '', $option);
            } else {
                $this->city = $message->message_text;
            }
            $this->storeCity(City::class);
            $this->sendMessage(Notification::class);
            $this->showProfile(Profile::class);
            $this->changeStatus('registered');
        }

        // Редактирование Город
        if(hash_equals($state->status, 'editedCity') && hash_equals($option, 'customCity'))
        {
            $this->sendCustomMessage(City::class);
        }
    }
}
