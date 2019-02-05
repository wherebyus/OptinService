<?php

namespace WBU\DTOs;

class OptinSettingsDto
{
    public $mailChimpAccountId;
    public $mailChimpApiKey;
    public $mailChimpLists;

    public function __construct(\stdClass $object = null)
    {
        if (empty($object)) {
            return;
        }

        $this->mailChimpAccountId = $object->mailChimpAccountId;
        $this->mailChimpApiKey = $object->mailChimpApiKey;

        /**
         * Arrays of objects are serialized in the WordPress database,
         * so when we retrieve them we need to unserialize them first.
         */
        $this->mailChimpLists = unserialize($object->mailChimpLists);
    }
}
