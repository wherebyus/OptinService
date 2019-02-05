<?php

namespace WBU\DTOs;

class OptinDto
{
    public $active;
    public $authorId;
    public $backgroundColor;
    public $conversions;
    public $dismissals;
    public $copy;
    public $id = 1;
    public $imageUrl;
    public $mailChimpList;
    public $pagesWithOptin;
    public $slug;
    public $status;
    public $title;
    public $typeOfOptin;
    public $views;

    public function __construct(\stdClass $object = null)
    {
        if (empty($object)) {
            return;
        }

        $this->active = (bool) $object->active;
        $this->authorId = (int) $object->authorId;
        $this->backgroundColor = $object->backgroundColor;
        $this->conversions = (int) $object->conversions;
        $this->copy = $object->copy;
        $this->dismissals = (int) $object->dismissals;
        $this->id = (int) $object->id;
        $this->imageUrl = $object->imageUrl;
        /**
         * Because the arrays of objects are serialized when
         * the post is created or updated, we need to unserialize them on return;
         */
        $this->mailChimpList = unserialize($object->mailChimpList);
        $this->pagesWithOptin = unserialize($object->pagesWithOptin);
        $this->slug = $object->slug;
        $this->status = $object->status;
        $this->title = $object->title;
        $this->typeOfOptin = (int) $object->typeOfOptin;
        $this->views = (int) $object->views;
    }
}
