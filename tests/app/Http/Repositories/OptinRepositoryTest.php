<?php

namespace WBU\Tests;

use Mockery;

require __DIR__ . '/../../../../bootstrap/autoload.php';

use PHPUnit\Framework\MockObject\Stub\Exception;
use PHPUnit\Framework\TestCase;
use WBU\BooleanConstants;
use WBU\DTOs\OptinDto;
use WBU\DTOs\OptinSettingsDto;
use WBU\Http\Repositories\OptinRepository;
use WBU\Models\Optin;

class OptinRepositoryTest extends TestCase
{
    private $wpdb;
    private $dbResult;
    private $dto;
    private $repository;

    public function setUp() : void
    {
        \WP_Mock::setUp();
        \WP_Mock::setUsePatchwork(true);
        \WP_Mock::bootstrap();

        $this->dbResult = new \stdClass();
        $this->dbResult->active = 1;
        $this->dbResult->authorId = 3;
        $this->dbResult->backgroundColor = null;
        $this->dbResult->conversions = 4;
        $this->dbResult->dismissals = 2;
        $this->dbResult->copy = 'Hey';
        $this->dbResult->id = 2;
        $this->dbResult->imageUrl = null;
        $this->dbResult->mailChimpList = '';
        $this->dbResult->pagesWithOptin = '';
        $this->dbResult->slug = 'hey';
        $this->dbResult->status = 'publish';
        $this->dbResult->title = 'Test';
        $this->dbResult->typeOfOptin = 5;
        $this->dbResult->views = 2;

        $this->dto = new OptinDto($this->dbResult);

        $this->repository = new OptinRepository();
    }

    public function tearDown() : void
    {
        \WP_Mock::tearDown();
    }

    public function testCanDeleteOptinById_returnsInteger()
    {
        $expectsResults = BooleanConstants::TRUE;
        $id = 5;

        \WP_Mock::userFunction('wp_delete_post', [
            'args' => [
                $id
            ],
            'return' => $this->dbResult,
        ]);

        $actualResults = $this->repository->deleteOptinById($id);

        $this->assertEquals($expectsResults, $actualResults);
    }

    public function testCannotDeleteOptinById_throwsException()
    {
        $expectsResults = -1;
        $id = 5;
        $wpError = Mockery::mock('\WP_Error');

        \WP_Mock::userFunction('wp_delete_post', [
            'args' => [
                $id
            ],
            'return' =>$wpError
        ]);

        $actualResults = $this->repository->deleteOptinById($id);
        $this->assertEquals($expectsResults, $actualResults);
    }

    public function testCanGetPagesAndPosts_returnsArray()
    {
        $pagesAndPostsArray = [];

        \WP_Mock::userFunction('get_posts', [
            'args' => [
                'post_type' => array('page', 'post'),
                'post_status' => array('publish'),
                'posts_per_page' => '-1',
                'orderby' => 'title',
            ],
            'return' => $pagesAndPostsArray,
        ]);

        $actualResults = $this->repository->getPagesAndPosts();

        $this->assertEquals($pagesAndPostsArray, $actualResults);
    }

    public function testCanGetOptinById_returnsOptin()
    {
        global $wpdb;

        $id = 5;

        $wpdb = Mockery::mock('\WPDB');
        $wpdb->posts = 'wp_posts';
        $wpdb->postmeta = 'wp_postmeta';

        $optin = new OptinDto();
        $dbResult = new \stdClass();
        $queryResult = null;

        $wpdb->shouldReceive('get_row')
            ->once()
            ->andReturn($dbResult);

        $actualResults = $this->repository->getOptinById($id);
        $this->assertEquals($optin, $actualResults);
    }

    public function testGetOptinsByPageId_returnsArray()
    {
        global $wpdb;

        $dbResult = [$this->dbResult];

        $wpdb = Mockery::mock('\WPDB');

        $wpdb->posts = 'wp_posts';
        $wpdb->postmeta = 'wp_postmeta';

        $wpdb->shouldReceive('get_results')
            ->once()
            ->andReturn($dbResult);

        \WP_Mock::userFunction('get_the_ID', [
            'return' => '123345',
        ]);
        $actualResults = $this->repository->getOptinsByPageId();

        $this->assertEquals([$this->dto], $actualResults);
    }

    public function testCannotGetOptionsByPageId_throwsException_returnsArray()
    {
        global $wpdb;

        $dbResult = [$this->dbResult];

        $wpdb = Mockery::mock('\WPDB');
        $wpdb->posts = 'wp_posts';
        $wpdb->postmeta = 'wp_postmeta';

        $wpdb->shouldReceive('get_results')
            ->once()
            ->andThrow(new \Exception());

        \WP_Mock::userFunction('get_the_ID', [
            'return' => '123345',
        ]);

        $actualResults = $this->repository->getOptinsByPageId();
        $this->assertEquals([], $actualResults);
    }

    public function testCanGetOptinSettings_returnsOptinSettingsDto()
    {
        global $wpdb;

        $settingsObject = new \stdClass();
        $settingsObject->mailChimpAccountId = '2392323';
        $settingsObject->mailChimpApiKey = '2j23j283233';
        $settingsObject->mailChimpLists = '';

        $settingsDto = new OptinSettingsDto($settingsObject);

        $wpdb = Mockery::mock('\WPDB');
        $wpdb->options = 'wp_options';

        $wpdb->shouldReceive('get_row')
            ->once()
            ->andReturn($settingsObject);

        $actualResults = $this->repository->getOptinSettings();

        $this->assertEquals($settingsDto, $actualResults);
    }
}
