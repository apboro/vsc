<?php

namespace App\Http\Controllers\API\Settings;

use App\Current;
use App\Http\APIResponse;
use App\Http\Controllers\ApiEditController;
use App\Settings;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SettingsController extends ApiEditController
{
    protected array $settings = [
        'general' => [
            'fields' => [],
            'titles' => [],
            'rules' => [],
        ],
    ];

    /**
     * Get general settings.
     *
     * @param Request $request
     *
     * @return  JsonResponse
     */
    public function getGeneral(Request $request): JsonResponse
    {
        return $this->get($request, 'general');
    }

    /**
     * Set general settings.
     *
     * @param Request $request
     *
     * @return  JsonResponse
     */
    public function setGeneral(Request $request): JsonResponse
    {
        return $this->set($request, 'general');
    }

    /**
     * Get settings for a section.
     *
     * @param Request $request
     * @param string $section
     *
     * @return  JsonResponse
     */
    protected function get(Request $request, string $section): JsonResponse
    {
        $values = [];
        $current = Current::get($request);

        foreach ($this->settings[$section]['fields'] as $key => $type) {
            $values[$key] = Settings::get($current->organizationId(), $key, null, $type);
        }

        return APIResponse::form($values, $this->settings[$section]['rules'], $this->settings[$section]['titles']);
    }

    /**
     * Set settings for a section.
     *
     * @param Request $request
     * @param string $section
     *
     * @return  JsonResponse
     */
    protected function set(Request $request, string $section): JsonResponse
    {
        $data = $this->getData($request);

        if ($errors = $this->validate($data, $this->settings[$section]['rules'], $this->settings[$section]['titles'])) {
            return APIResponse::validationError($errors);
        }

        $current = Current::get($request);

        foreach ($this->settings[$section]['fields'] as $key => $type) {
            Settings::set($current->organizationId(), $key, $data[$key], $type);
        }

        Settings::save();

        return APIResponse::success('Настройки сохранены');
    }
}
