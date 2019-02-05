<?php

namespace WBU\Http\Services;

use WBU\Http\Repositories\OptinRepositoryInterface;
use WBU\Models\Optin;
use WBU\Models\OptinSettings;

class OptinService implements OptinServiceInterface
{
    private $repository;

    public function __construct(OptinRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function deleteOptinById(int $id): int
    {
        return $this->repository->deleteOptinById($id);
    }

    public function getPagesAndPosts(): array
    {
        return $this->repository->getPagesAndPosts();
    }

    public function getOptinById(int $id) : Optin
    {
        return new Optin($this->repository->getOptinById($id));
    }

    public function getOptinsByPageId() : array
    {
        return $this->toModelArray($this->repository->getOptinsByPageId());
    }

    public function getOptinSettings() : OptinSettings
    {
        return new OptinSettings($this->repository->getOptinSettings());
    }

    private function toModelArray(array $dtos): array
    {
        if (empty($dtos)) {
            return [];
        }

        $modelArray = [];

        foreach ($dtos as $dto) {
            $modelArray[] = new Optin($dto);
        }

        return $modelArray;
    }

    public function updateConversionMetric(int $optinId) : int
    {
        $optin = $this->getOptinById($optinId);

        $currentConversionCount = $optin->getConversions();
        $updatedConversionCount = ++$currentConversionCount;

        $optin->setConversions($updatedConversionCount);

        $updatedOptinDto = $optin->convertToDto();

        return $this->repository->updateOptin($updatedOptinDto);
    }

    public function updateDismissalMetric(int $optinId) : int
    {
        $optin = $this->getOptinById($optinId);

        $currentDismissalCount = $optin->getDismissals();
        $updatedDismissalCount = ++$currentDismissalCount;

        $optin->setDismissals($updatedDismissalCount);

        $updatedOptinDto = $optin->convertToDto();

        return $this->repository->updateOptin($updatedOptinDto);
    }

    public function updateOptin(
        int $optinId,
        bool $active,
        int $authorId,
        string $backgroundColor,
        int $conversions,
        string $copy,
        int $dismissals,
        string $imageUrl,
        array $mailChimpList,
        array $pagesWithOptin,
        string $status,
        string $title,
        int $type,
        int $views
    ) : int {
        $optin = new Optin();

        $optin->setActive($active);
        $optin->setAuthorId($authorId);
        $optin->setBackgroundColor($backgroundColor);
        $optin->setConversions($conversions);
        $optin->setCopy($copy);
        $optin->setDismissals($dismissals);
        $optin->setImageUrl($imageUrl);
        $optin->setMailChimpList($mailChimpList);
        $optin->setPagesWithOptin($pagesWithOptin);
        $optin->setId($optinId);
        $optin->setStatus($status);
        $optin->setTitle($title);
        $optin->setType($type);
        $optin->setViews($views);

        $dto = $optin->convertToDto();

        return $this->repository->updateOptin($dto);
    }

    public function updateOptinSettings(
        string $mailChimpApiKey,
        string $mailChimpAccountId,
        array $mailChimpLists
    ) : int
    {
        $optinSettings = new OptinSettings();

        $optinSettings->setMailChimpAccountId($mailChimpAccountId);
        $optinSettings->setMailChimpApiKey($mailChimpApiKey);
        $optinSettings->setMailChimpLists($mailChimpLists);

        return $this->repository->updateOptinSettings($optinSettings->convertToDto());
    }

    public function updateViewMetric(int $optinId) : int
    {
        $optin = $this->getOptinById($optinId);

        $currentViewCount = $optin->getViews();
        $updatedViewCount = ++$currentViewCount;

        $optin->setViews($updatedViewCount);

        $updatedOptinDto = $optin->convertToDto();

        return $this->repository->updateOptin($updatedOptinDto);
    }
}
