<?php

namespace WBU\Models;

use WBU\BooleanConstants;
use WBU\DTOs\OptinDto;

class Optin
{
    private $active;
    private $authorId;
    private $backgroundColor;
    private $conversions;
    private $copy;
    private $dismissals;
    private $id;
    private $imageUrl;
    private $mailChimpList;
    private $pagesWithOptin;
    private $slug;
    private $status;
    private $title;
    private $typeOfOptin;
    private $views;

    public function __construct(OptinDto $dto = null)
    {
        if (empty($dto)) {
            return;
        }

        $this->active = $dto->active;
        $this->authorId = $dto->authorId;
        $this->backgroundColor = $dto->backgroundColor;
        $this->conversions = $dto->conversions;
        $this->copy = $dto->copy;
        $this->dismissals = $dto->dismissals;
        $this->id = $dto->id;
        $this->imageUrl = $dto->imageUrl;
        $this->mailChimpList = $dto->mailChimpList;
        $this->pagesWithOptin = $dto->pagesWithOptin;
        $this->slug = $dto->slug;
        $this->status = $dto->status;
        $this->title = $dto->title;
        $this->typeOfOptin = $dto->typeOfOptin;
        $this->views = $dto->views;
    }

    public function convertToArray()
    {
       return get_object_vars($this);
    }

    public function convertToDto() : OptinDto
    {
        $dto = new OptinDto();

        $dto->active = ($this->active ? BooleanConstants::TRUE : BooleanConstants::FALSE);
        $dto->authorId = $this->authorId;
        $dto->backgroundColor = $this->backgroundColor;
        $dto->conversions = $this->conversions;
        $dto->copy = $this->copy;
        $dto->dismissals = $this->dismissals;
        $dto->id = $this->id;
        $dto->imageUrl = $this->imageUrl;
        $dto->mailChimpList = $this->mailChimpList;
        $dto->pagesWithOptin = $this->pagesWithOptin;
        $dto->slug = $this->slug;
        $dto->status = $this->status;
        $dto->title = $this->title;
        $dto->typeOfOptin = $this->typeOfOptin;
        $dto->views = $this->views;

        return $dto;
    }

    public function getActive() : bool
    {
        return (bool) $this->active;
    }

    public function getBackgroundColor() : string
    {
        return $this->backgroundColor ?? '';
    }

    public function getConversions() : int
    {
        return $this->conversions ?? 0;
    }

    public function getDismissals() : int
    {
        return $this->dismissals ?? 0;
    }

    public function getCopy() : string
    {
        return $this->copy ?? '';
    }

    public function getImageUrl() : string
    {
        return $this->imageUrl ?? '';
    }

    public function getMailChimpList() : array
    {
        return $this->mailChimpList;
    }

    public function getTitle() : string
    {
        return $this->title;
    }

    public function getType() : int
    {
        return $this->typeOfOptin;
    }

    public function getViews() : int
    {
        return $this->views ?? 0;
    }

    public function setActive(bool $active)
    {
        $this->active = $active;
    }

    public function setAuthorId(int $authorId)
    {
        $this->authorId = $authorId;
    }

    public function setBackgroundColor(string $backgroundColor)
    {
        $this->backgroundColor = $backgroundColor;
    }

    public function setConversions(int $conversions)
    {
        $this->conversions = $conversions;
    }

    public function setCopy(string $copy)
    {
        /**
         * @todo abstract to some utility or other class
         */
        if (!function_exists('wp_kses')) {
            return;
        }

        $allowedHtml = [
            'b' => [],
            'em' => [],
            'i' => [],
            'strong' => [],
        ];
        $sanitizedCopy = wp_kses($copy, $allowedHtml);

        $this->copy = $sanitizedCopy;
    }

    public function setDismissals(int $dismissals)
    {
        $this->dismissals = $dismissals;
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function setImageUrl(string $imageUrl)
    {
        $this->imageUrl = $imageUrl;
    }

    public function setMailChimpList(array $mailChimpList)
    {
        $this->mailChimpList = $mailChimpList;
    }

    public function setPagesWithOptin(array $pagesWithOptin)
    {
        $this->pagesWithOptin = $pagesWithOptin;
    }

    public function setSlug(string $slug)
    {
        $this->slug = $slug;
    }

    public function setStatus(string $status)
    {
        $this->status = $status;
    }

    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    public function setType(int $typeOfOptin)
    {
        $this->typeOfOptin = $typeOfOptin;
    }


    public function setViews(int $views)
    {
        $this->views = $views;
    }
}
