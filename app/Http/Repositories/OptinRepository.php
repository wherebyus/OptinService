<?php

namespace WBU\Http\Repositories;

use WBU\BooleanConstants;
use WBU\DTOs\OptinDto;
use WBU\DTOs\OptinSettingsDto;

class OptinRepository implements OptinRepositoryInterface
{
    const OPTION_KEY_MAILCHIMP_ACCOUNT_ID = 'wbu_optin_option_mailChimpAccountId';
    const OPTION_KEY_MAILCHIMP_API = 'options_mcapi_key';
    const OPTION_KEY_MAILCHIMP_LISTS = 'wbu_optin_option_mailChimpLists';
    const POSTMETA_KEY_ACTIVE = 'wbu_optin_active';
    const POSTMETA_KEY_BACKGROUND_COLOR = 'wbu_optin_backgroundColor';
    const POSTMETA_KEY_CONVERSIONS = 'wbu_optin_conversions';
    const POSTMETA_KEY_COPY = 'wbu_optin_copy';
    const POSTMETA_KEY_DISMISSALS = 'wbu_optin_dismissals';
    const POSTMETA_KEY_IMAGE_URL = 'wbu_optin_imageUrl';
    const POSTMETA_KEY_LIST = 'wbu_optin_mailChimpList';
    const POSTMETA_KEY_PAGES = 'wbu_optin_pages';
    const POSTMETA_KEY_TYPE = 'wbu_optin_type';
    const POSTMETA_KEY_VIEWS = 'wbu_optin_views';

    public function __construct()
    {
    }

    public function deleteOptinById(int $id) : int
    {
        $response = null;

        try {
            $results = $this->deletePost($id);
            $response = (is_object($results) ? BooleanConstants::TRUE : BooleanConstants::FALSE);
        } catch (\Exception $e) {
            $response = -1;
        }

        return $response;
    }

    private function deletePost(int $id)
    {
        if (!function_exists('wp_delete_post')) {
            return -1;
        }

        $results = wp_delete_post($id);

        if (is_a($results, 'WP_error')) {
            throw new \Exception();
        }

        return $results;
    }

    public function getPagesAndPosts() : array
    {
        if (!function_exists('get_posts')) {
            return [];
        }

        $args = array(
            'post_type' => array( 'page', 'post' ),
            'post_status' => array( 'publish' ),
            'posts_per_page' => '-1',
            'orderby' => 'title',
        );

        $response = null;

        try {
            $response = get_posts($args);
        } catch (\Exception $e) {
            $response = [];
        }

        return $response;
    }

    public function getOptinById(int $id) : OptinDto
    {
        global $wpdb;

        $metaKeyBackgroundColor = self::POSTMETA_KEY_BACKGROUND_COLOR;
        $metaKeyConversions = self::POSTMETA_KEY_CONVERSIONS;
        $metaKeyDismissals = self::POSTMETA_KEY_DISMISSALS;
        $metaKeyImageUrl = self::POSTMETA_KEY_IMAGE_URL;
        $metaKeyListId = self::POSTMETA_KEY_LIST;
        $metaKeyViews = self::POSTMETA_KEY_VIEWS;

        $query = "
            SELECT 
              optinActive.meta_value AS active, 
              posts.post_author AS authorId,
              optinBackground.meta_value AS backgroundColor,
              optinConversions.meta_value AS conversions,
              optinDismissals.meta_value AS dismissals,
              optinCopy.meta_value AS copy,
              optinImage.meta_value AS imageUrl,
              posts.ID AS id,
              optinList.meta_value AS mailChimpList,
              optinPages.meta_value AS pagesWithOptin,
              posts.post_name AS slug,
              posts.post_status AS status,
              posts.post_title AS title,
              optinType.meta_value AS typeOfOptin,
              optinViews.meta_value AS views            
            FROM {$wpdb->posts} AS posts
            LEFT JOIN {$wpdb->postmeta} AS optinActive 
              ON (optinActive.post_id = posts.id AND optinActive.meta_key = 'wbu_optin_active')
            LEFT JOIN {$wpdb->postmeta} AS optinBackground
              ON (optinBackground.post_id = posts.id AND optinBackground.meta_key = '{$metaKeyBackgroundColor}')
            LEFT JOIN {$wpdb->postmeta} AS optinConversions
              ON (optinConversions.post_id = posts.id AND optinConversions.meta_key = '{$metaKeyConversions}')
            LEFT JOIN {$wpdb->postmeta} AS optinDismissals
              ON (optinDismissals.post_id = posts.id AND optinDismissals.meta_key = '{$metaKeyDismissals}')
            LEFT JOIN {$wpdb->postmeta} AS optinCopy
              ON (optinCopy.post_id = posts.id AND optinCopy.meta_key = 'wbu_optin_copy')
            LEFT JOIN {$wpdb->postmeta} AS optinImage
              ON (optinImage.post_id = posts.id AND optinImage.meta_key = '{$metaKeyImageUrl}')
            LEFT JOIN {$wpdb->postmeta} AS optinList
              ON (optinList.post_id = posts.id AND optinList.meta_key = '{$metaKeyListId}')
            LEFT JOIN {$wpdb->postmeta} AS optinPages
              ON (optinPages.post_id = posts.id AND optinPages.meta_key = 'wbu_optin_pages')
            LEFT JOIN {$wpdb->postmeta} AS optinType
              ON (optinType.post_id = posts.id AND optinType.meta_key = 'wbu_optin_type')
            LEFT JOIN {$wpdb->postmeta} AS optinViews
              ON (optinViews.post_id = posts.id AND optinViews.meta_key = '{$metaKeyViews}')
            WHERE posts.post_type = 'wbu-optin'
              AND posts.id = {$id} 
        ";

        $response = null;

        try {
            $result = $wpdb->get_row($query);
            $response = new OptinDto($result);
        } catch (\Exception $e) {
            $response = new OptinDto();
        }

        return $response;
    }

    public function getOptinsByPageId() : array
    {
        if (!function_exists('get_the_ID')) {
            return [];
        }

        global $wpdb;

        $booleanConstantTrue = BooleanConstants::TRUE;
        $metaKeyBackgroundColor = self::POSTMETA_KEY_BACKGROUND_COLOR;
        $metaKeyConversions = self::POSTMETA_KEY_CONVERSIONS;
        $metaKeyDismissals = self::POSTMETA_KEY_DISMISSALS;
        $metaKeyImageUrl = self::POSTMETA_KEY_IMAGE_URL;
        $metaKeyListId = self::POSTMETA_KEY_LIST;
        $metaKeyViews = self::POSTMETA_KEY_VIEWS;
        $pageId = get_the_ID();

        $query = "
            SELECT 
              optinActive.meta_value AS active, 
              posts.post_author AS authorId,
              optinBackground.meta_value AS backgroundColor,
              optinConversions.meta_value AS conversions,
              optinDismissals.meta_value AS dismissals,
              optinCopy.meta_value AS copy,
              optinImage.meta_value AS imageUrl,
              posts.ID AS id,
              optinList.meta_value AS mailChimpList,
              optinPages.meta_value AS pagesWithOptin,
              posts.post_name AS slug,
              posts.post_status AS status,
              posts.post_title AS title,
              optinType.meta_value AS typeOfOptin,
              optinViews.meta_value AS views              
            FROM {$wpdb->posts} AS posts
            LEFT JOIN {$wpdb->postmeta} AS optinActive 
              ON (optinActive.post_id = posts.id AND optinActive.meta_key = 'wbu_optin_active')
            LEFT JOIN {$wpdb->postmeta} AS optinBackground
              ON (optinBackground.post_id = posts.id AND optinBackground.meta_key = '{$metaKeyBackgroundColor}')
            LEFT JOIN {$wpdb->postmeta} AS optinConversions
              ON (optinConversions.post_id = posts.id AND optinConversions.meta_key = '{$metaKeyConversions}')
            LEFT JOIN {$wpdb->postmeta} AS optinDismissals
              ON (optinDismissals.post_id = posts.id AND optinDismissals.meta_key = '{$metaKeyDismissals}')
            LEFT JOIN {$wpdb->postmeta} AS optinCopy
              ON (optinCopy.post_id = posts.id AND optinCopy.meta_key = 'wbu_optin_copy')
            LEFT JOIN {$wpdb->postmeta} AS optinImage
              ON (optinImage.post_id = posts.id AND optinImage.meta_key = '{$metaKeyImageUrl}')
            LEFT JOIN {$wpdb->postmeta} AS optinList
              ON (optinList.post_id = posts.id AND optinList.meta_key = '{$metaKeyListId}')
            LEFT JOIN {$wpdb->postmeta} AS optinPages
              ON (optinPages.post_id = posts.id AND optinPages.meta_key = 'wbu_optin_pages')
            LEFT JOIN {$wpdb->postmeta} AS optinType
              ON (optinType.post_id = posts.id AND optinType.meta_key = 'wbu_optin_type')
            LEFT JOIN {$wpdb->postmeta} AS optinViews
              ON (optinViews.post_id = posts.id AND optinViews.meta_key = '{$metaKeyViews}')
            WHERE optinPages.meta_value LIKE '%ID\";i:{$pageId}%'
              AND optinActive.meta_value = {$booleanConstantTrue}
              AND posts.post_status = 'publish'
        ";

        $results = null;
        try {
            $response = $wpdb->get_results($query);
        } catch (\Exception $e) {
            $response = [];
        }

        return $this->toDtoArray($response);
    }

    public function getOptinSettings() : OptinSettingsDto
    {
        global $wpdb;

        $optionKeyMailChimpAccountId = self::OPTION_KEY_MAILCHIMP_ACCOUNT_ID;
        $optionKeyMailChimpApi = self::OPTION_KEY_MAILCHIMP_API;
        $optionKeyMailChimpLists = self::OPTION_KEY_MAILCHIMP_LISTS;

        $query = "
            SELECT 
              optionsAccount.option_value AS mailChimpAccountId, 
              optionsApi.option_value AS mailChimpApiKey,
              optionsLists.option_value AS mailChimpLists
            FROM {$wpdb->options} AS optionsApi
            LEFT JOIN {$wpdb->options} AS optionsAccount 
              ON (optionsAccount.option_name = '{$optionKeyMailChimpAccountId}')
            LEFT JOIN {$wpdb->options} AS optionsLists
              ON (optionsLists.option_name = '{$optionKeyMailChimpLists}')
            WHERE optionsApi.option_name = '{$optionKeyMailChimpApi}'
        ";

        try {
            $response = $wpdb->get_row($query);
        } catch (\Exception $e) {
            $response = null;
        }

        return new OptinSettingsDto($response);
    }

    private function toDtoArray(array $resultSet) : array
    {
        if (empty($resultSet)) {
            return [];
        }

        $dtoArray = [];

        foreach ($resultSet as $object) {
            $dtoArray[] = new OptinDto($object);
        }

        return $dtoArray;
    }

    /**
     * @see https://developer.wordpress.org/reference/functions/wp_insert_post/
     */
    public function updateOptin(OptinDto $dto) : int
    {
        if (! function_exists('wp_insert_post')) {
            return -1;
        }

        $postOptions = [
            'ID' => $dto->id,
            'post_author' => $dto->authorId,
            'post_title' => $dto->title,
            'post_status' => $dto->status,
            'post_type' => 'wbu-optin', // @todo add to constant
            'comment_status' => 'closed',
            'post_category' => [],
            'ping_status' => 'closed',
            'post_name' => $dto->title,
            'meta_input' => [
                self::POSTMETA_KEY_ACTIVE => $dto->active,
                self::POSTMETA_KEY_BACKGROUND_COLOR => $dto->backgroundColor,
                self::POSTMETA_KEY_CONVERSIONS => $dto->conversions,
                self::POSTMETA_KEY_COPY => $dto->copy,
                self::POSTMETA_KEY_DISMISSALS => $dto->dismissals,
                self::POSTMETA_KEY_IMAGE_URL => $dto->imageUrl,
                self::POSTMETA_KEY_LIST => $dto->mailChimpList,
                self::POSTMETA_KEY_PAGES => $dto->pagesWithOptin,
                self::POSTMETA_KEY_TYPE => $dto->typeOfOptin,
                self::POSTMETA_KEY_VIEWS => $dto->views,
            ],
            'tags_input' => [],
            'tax_input' => [],
        ];

        $response = null;

        try {
            $response = wp_insert_post($postOptions);
        } catch (\Exception $e) {
            $response = -1;
        }

        return $response;
    }

    public function updateOptinSettings(OptinSettingsDto $dto) : int
    {
        try {
            $this->updateOption(self::OPTION_KEY_MAILCHIMP_ACCOUNT_ID, $dto->mailChimpAccountId);
            $this->updateOption(self::OPTION_KEY_MAILCHIMP_API, $dto->mailChimpApiKey);
            $this->updateOption(self::OPTION_KEY_MAILCHIMP_LISTS, $dto->mailChimpLists);
        } catch (\Exception $e) {
            return -1;
        }

        return 1;
    }

    private function updateOption(string $optionKey, $value) : bool
    {
        if (!function_exists('update_option')) {
            return false;
        }

        return update_option($optionKey, $value);
    }
}
