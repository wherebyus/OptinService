<?php

namespace WBU\Models;

use WBU\DTOs\OptinSettingsDto;

class OptinSettings
{
    private $mailChimpAccountId;
    private $mailChimpApiKey;
    private $mailChimpLists;

    public function __construct(OptinSettingsDto $dto = null)
    {
        if (empty($dto)) {
            return;
        }

        $this->mailChimpAccountId = $dto->mailChimpAccountId;
        $this->mailChimpApiKey = $dto->mailChimpApiKey;
        $this->mailChimpLists = $dto->mailChimpLists;
    }

    public function convertToArray() : array
    {
        return get_object_vars($this);
    }

    public function convertToDto() : OptinSettingsDto
    {
        $dto = new OptinSettingsDto();

        $dto->mailChimpAccountId = $this->mailChimpAccountId;
        $dto->mailChimpApiKey = $this->mailChimpApiKey;
        $dto->mailChimpLists = $this->mailChimpLists;

        return $dto;
    }

    public function getMailChimpApiKey() : string
    {
        return $this->mailChimpApiKey;
    }

    public function setMailChimpAccountId(string $mailChimpAccountId)
    {
        $this->mailChimpAccountId = $mailChimpAccountId;
    }

    public function setMailChimpApiKey(string $mailChimpApiKey)
    {
        $this->mailChimpApiKey = $mailChimpApiKey;
    }

    public function setMailChimpLists(array $mailChimpLists)
    {
        $this->mailChimpLists = $mailChimpLists;
    }
}
