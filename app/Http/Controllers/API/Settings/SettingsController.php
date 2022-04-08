<?php

namespace App\Http\Controllers\API\Settings;

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
        return $this->get('general');
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
     * Get settings for section.
     *
     * @param string $section
     *
     * @return  JsonResponse
     */
    protected function get(string $section): JsonResponse
    {
        $values = [];

        foreach ($this->settings[$section]['fields'] as $key => $type) {
            $values[$key] = Settings::get($key, null, $type);
        }

        return APIResponse::form($values, $this->settings[$section]['rules'], $this->settings[$section]['titles']);
    }

    /**
     * Set settings for section.
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

        foreach ($this->settings[$section]['fields'] as $key => $type) {
            Settings::set($key, $data[$key], $type);
        }

        Settings::save();

        return APIResponse::success('Настройки сохранены');
    }
}
