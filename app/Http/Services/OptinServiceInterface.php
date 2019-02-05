<?php

namespace WBU\Http\Services;

use WBU\Models\Optin;
use WBU\Models\OptinSettings;

interface OptinServiceInterface
{
    public function deleteOptinById(int $id);
    public function getPagesAndPosts() : array;
    public function getOptinById(int $id) : Optin;
    public function getOptinsByPageId();
    public function getOptinSettings() : OptinSettings;
    public function updateConversionMetric(int $optinId) : int;
    public function updateDismissalMetric(int $optinId) : int;
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
    ) : int;
    public function updateOptinSettings(
        string $mailChimpApiKey,
        string $mailChimpAccountId,
        array $mailChimpLists
    ) : int;
    public function updateViewMetric(int $optinId) : int;
}
