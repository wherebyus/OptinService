<?php

namespace WBU\Http\Repositories;

use WBU\DTOs\OptinDto;
use WBU\DTOs\OptinSettingsDto;

interface OptinRepositoryInterface
{
    public function deleteOptinById(int $id) : int;
    public function getPagesAndPosts() : array;
    public function getOptinById(int $id) : OptinDto;
    public function getOptinsByPageId() : array;
    public function getOptinSettings() : OptinSettingsDto;
    public function updateOptinSettings(OptinSettingsDto $dto) : int;
    public function updateOptin(OptinDto $dto) : int;
}
