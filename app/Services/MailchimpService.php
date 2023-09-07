<?php

declare(strict_types=1);

namespace App\Services;

use MailchimpMarketing\ApiClient;

class MailchimpService
{
    private ApiClient $mailchimp;

    private $listId;

    public function __construct(ApiClient $mailchimp)
    {
        $this->mailchimp = $mailchimp;

        $this->setListId();
    }

    public function getListId()
    {
        return $this->listId;
    }

    private function setListId()
    {
        /**
         * TODO: Replace to fetch from admin settings.
         */
        $this->listId = '6a2515a99e';
    }

    public function subscribe($email)
    {
        $response = $this->mailchimp->lists->addListMember($this->getListId(), [
            'email_address' => $email,
            'status' => 'subscribed',
        ]);

        return $response;
    }

    public function getAllLists()
    {
        $response = $this->mailchimp->lists->getAllLists();

        return $response;
    }
}
