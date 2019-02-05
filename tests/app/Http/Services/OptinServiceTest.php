<?php

namespace WBU\Tests;

require __DIR__ . '/../../../../bootstrap/autoload.php';

use PHPUnit\Framework\TestCase;
use WBU\DTOs\OptinDto;
use WBU\DTOs\OptinSettingsDto;
use WBU\Models\Optin;
use WBU\Http\Repositories\OptinRepository;
use WBU\Http\Services\OptinService;
use WBU\Models\OptinSettings;

class OptinServiceTest extends TestCase
{
    private $repository;
    private $service;

    public function setUp() : void
    {
        $this->repository = $this->createMock(OptinRepository::class);
        $this->service = new OptinService($this->repository);
    }

    public function testDeleteOptinById_returnsInteger()
    {
        $expectedResult = 5;
        $id = 5;

        $this->repository
            ->expects($this->once())
            ->method('deleteOptinById')
            ->with($id)
            ->willReturn($expectedResult);

        $actualResults = $this->service->deleteOptinById($id);

        $this->assertEquals($expectedResult, $actualResults);
    }

    public function testCanGetPagesAndPosts_returnsArray()
    {
        $expectedResult = ['page' => [
            'id' => 5,
            'title' => 'A Page',
        ]];

        $this->repository
            ->expects($this->once())
            ->method('getPagesAndPosts')
            ->willReturn($expectedResult);

        $actualResults = $this->service->getPagesAndPosts();

        $this->assertEquals($expectedResult, $actualResults);
    }

    public function testCanGetOptinById_returnsOptin()
    {
        $id = 5;
        $dto = new OptinDto();
        $optin = new Optin($dto);

        $this->repository
            ->expects($this->once())
            ->method('getOptinById')
            ->with($id)
            ->willReturn($dto);

        $actualResults = $this->service->getOptinById($id);

        $this->assertEquals($optin, $actualResults);
    }

    public function testCanGetOptinsByPageId_returnsArray()
    {
        $expectedResult = [];

        $this->repository
            ->expects($this->once())
            ->method('getOptinsByPageId')
            ->willReturn([]);

        $actualResults = $this->service->getOptinsByPageId();

        $this->assertEquals($expectedResult, $actualResults);
    }

    public function testCanGetOptinSettings_returnsOptinSettings()
    {
        $optinSettings = new OptinSettings();
        $optinSettingsDto = new OptinSettingsDto();

        $this->repository
            ->expects($this->once())
            ->method('getOptinSettings')
            ->willReturn($optinSettingsDto);

        $actualResults = $this->service->getOptinSettings();

        $this->assertEquals($optinSettings, $actualResults);
    }

    public function testCanUpdateConversionMetric_returnsId()
    {
        $dto = new OptinDto();
        $dto->active = null;
        $dto->conversions = 2;
        $id = 5;
        $updatedDto = new OptinDto();
        $updatedDto->active = 0;
        $updatedDto->conversions = 3;

        $this->repository
            ->expects($this->once())
            ->method('getOptinById')
            ->with($id)
            ->willReturn($dto);

        $this->repository
            ->expects($this->once())
            ->method('updateOptin')
            ->with($updatedDto)
            ->willReturn($id);

        $actualResults = $this->service->updateConversionMetric($id);

        $this->assertEquals($id, $actualResults);
    }

    public function testCanUpdateDismissalMetric_returnsId()
    {
        $dto = new OptinDto();
        $dto->active = null;
        $dto->dismissals = 2;
        $id = 5;
        $updatedDto = new OptinDto();
        $updatedDto->active = 0;
        $updatedDto->dismissals = 3;

        $this->repository
            ->expects($this->once())
            ->method('getOptinById')
            ->with($id)
            ->willReturn($dto);

        $this->repository
            ->expects($this->once())
            ->method('updateOptin')
            ->with($updatedDto)
            ->willReturn($id);

        $actualResults = $this->service->updateDismissalMetric($id);

        $this->assertEquals($id, $actualResults);
    }

    public function testCanUpdateOptin_returnsOptinId()
    {
        $dto = new OptinDto();

        $dto->id = 119;
        $dto->active = 0;
        $dto->authorId = 4;
        $dto->backgroundColor = '';
        $dto->conversions = 0;
        $dto->copy = '';
        $dto->dismissals = 1;
        $dto->imageUrl = '';
        $dto->mailChimpList = [];
        $dto->pagesWithOptin = [];
        $dto->status = 'publish';
        $dto->title = 'Test';
        $dto->typeOfOptin = 1;
        $dto->views = 3;

        $this->repository
            ->expects($this->once())
            ->method('updateOptin')
            ->with($dto)
            ->willReturn($dto->id);

        $actualResults = $this->service->updateOptin(
            $dto->id,
            $dto->active,
            $dto->authorId,
            $dto->backgroundColor,
            $dto->conversions,
            $dto->copy,
            $dto->dismissals,
            $dto->imageUrl,
            $dto->mailChimpList,
            $dto->pagesWithOptin,
            $dto->status,
            $dto->title,
            $dto->typeOfOptin,
            $dto->views
        );

        $this->assertEquals($dto->id, $actualResults);
    }

    public function testCanUpdateOptinSettings_returnsId()
    {
        $id = 5;
        $dto = new OptinSettingsDto();
        $dto->mailChimpLists = [];
        $dto->mailChimpApiKey = '123445';
        $dto->mailChimpAccountId = '123m3';

        $this->repository
            ->expects($this->once())
            ->method('updateOptinSettings')
            ->with($dto)
            ->willReturn($id);

        $actualResults = $this->service->updateOptinSettings(
            $dto->mailChimpApiKey,
            $dto->mailChimpAccountId,
            $dto->mailChimpLists
        );

        $this->assertEquals($id, $actualResults);
    }

    public function testCanUpdateViewMetric_returnsId()
    {
        $dto = new OptinDto();
        $dto->active = null;
        $dto->views = 2;
        $id = 5;
        $updatedDto = new OptinDto();
        $updatedDto->active = 0;
        $updatedDto->views = 3;

        $this->repository
            ->expects($this->once())
            ->method('getOptinById')
            ->with($id)
            ->willReturn($dto);

        $this->repository
            ->expects($this->once())
            ->method('updateOptin')
            ->with($updatedDto)
            ->willReturn($id);

        $actualResults = $this->service->updateViewMetric($id);

        $this->assertEquals($id, $actualResults);
    }
}
