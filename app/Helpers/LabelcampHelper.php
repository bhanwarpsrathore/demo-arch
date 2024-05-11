<?php

namespace App\Helpers;

use LabelcampAPI\Session;
use LabelcampAPI\LabelcampAPI;
use LabelcampAPI\LabelcampAPIException;

use Cache;

class LabelcampHelper {

    private LabelcampAPI $api;

    /**
     * Cache key
     */
    protected string $cacheKey = 'labelcamp_token';

    public function __construct() {
        $this->api = new LabelcampAPI();
    }

    /**
     * Get User Access Token
     * 
     */
    public function getUserAccessToken(): mixed {
        return Cache::remember($this->cacheKey, 86000, function () {
            $session = $this->session();

            // Request a access token
            $session->requestAccessToken();
            $access_token = $session->getAccessToken();

            return $access_token;
        });
    }

    public function session(): Session {
        $session = new Session(
            config('services.labelcamp.username'),
            config('services.labelcamp.password')
        );

        return $session;
    }

    // Label Panel Methods
    public function getLabels(string $label_id = '', array $filter = []): array {
        $token = (string) $this->getUserAccessToken();
        if (!$token) {
            return [];
        }

        try {
            $this->api->setAccessToken($token);
            return $this->api->getLabel($label_id, $filter);
        } catch (LabelcampAPIException $e) {
        }

        return [];
    }

    public function createLabel(array $attributes, array $relationships): array {
        $token = (string) $this->getUserAccessToken();

        if (!$token) {
            return [];
        }

        try {
            $this->api->setAccessToken($token);
            return $this->api->createLabel($attributes, $relationships);
        } catch (LabelcampAPIException $e) {
        }

        return [];
    }

    public function updateLabel(string $label_id, array $attributes, array $relationships): array {
        $token = (string) $this->getUserAccessToken();
        if (!$token) {
            return [];
        }

        try {
            $this->api->setAccessToken($token);
            return $this->api->updateLabel($label_id, $attributes, $relationships);
        } catch (LabelcampAPIException $e) {
        }

        return [];
    }

    // Team Panel Methods
    public function getCompany(): array {
        $token = (string) $this->getUserAccessToken();
        if (!$token) {
            return [];
        }

        try {
            $this->api->setAccessToken($token);
            return $this->api->getCompanies();
        } catch (LabelcampAPIException $e) {
        }

        return [];
    }

    public function createCompany(array $attributes, array $relationships): array {
        $token = (string) $this->getUserAccessToken();
        if (!$token) {
            return [];
        }

        try {
            $this->api->setAccessToken($token);
            return $this->api->createCompany($attributes, $relationships);
        } catch (LabelcampAPIException $e) {
        }

        return [];
    }

    public function updateCompany(string $company_id, array $attributes, array $relationships): array {
        $token = (string) $this->getUserAccessToken();
        if (!$token) {
            return [];
        }

        try {
            $this->api->setAccessToken($token);
            return $this->api->updateCompany($company_id, $attributes, $relationships);
        } catch (LabelcampAPIException $e) {
        }

        return [];
    }

    public function getArtist(string $artist_id = '', array $filter = [], array $page = []): array {
        $token = (string) $this->getUserAccessToken();
        if (!$token) {
            return [];
        }

        try {
            $this->api->setAccessToken($token);
            return $this->api->getArtist($artist_id, $filter, $page);
        } catch (LabelcampAPIException $e) {
        }
    }

    public function createArtist(array $attributes, array $relationships): array {
        $token = (string) $this->getUserAccessToken();
        if (!$token) {
            return [];
        }

        try {
            $this->api->setAccessToken($token);
            return $this->api->createArtist($attributes, $relationships);
        } catch (LabelcampAPIException $e) {
        }

        return [];
    }

    public function updateArtist(string $people_id, array $attributes): array {
        $token = (string) $this->getUserAccessToken();
        if (!$token) {
            return [];
        }

        try {
            $this->api->setAccessToken($token);
            return $this->api->updateArtist($people_id, $attributes);
        } catch (LabelcampAPIException $e) {
        }

        return [];
    }

    public function getTrack(string $track_id = '', array $filter = []): array {
        $token = (string) $this->getUserAccessToken();
        if (!$token) {
            return [];
        }

        try {
            $this->api->setAccessToken($token);
            return $this->api->getTracks($track_id, $filter);
        } catch (LabelcampAPIException $e) {
        }

        return [];
    }

    public function createTrack(array $attributes, array $relationships): array {
        $token = (string) $this->getUserAccessToken();
        if (!$token) {
            return [];
        }

        try {
            $this->api->setAccessToken($token);
            return $this->api->createTrack($attributes, $relationships);
        } catch (LabelcampAPIException $e) {
        }

        return [];
    }

    public function updateTrack(string $track_id, array $attributes, array $relationships): array {
        $token = (string) $this->getUserAccessToken();
        if (!$token) {
            return [];
        }

        try {
            $this->api->setAccessToken($token);
            return $this->api->updateTrack($track_id, $attributes, $relationships);
        } catch (LabelcampAPIException $e) {
        }

        return [];
    }

    public function getRelease(string $labelcamp_id = '', array $filter = [], array $includes = [], array $sort = []): array {
        $token = (string) $this->getUserAccessToken();
        if (!$token) {
            return [];
        }

        try {
            $this->api->setAccessToken($token);
            return $this->api->getProduct($labelcamp_id, $filter, $includes, $sort);
        } catch (LabelcampAPIException $e) {
        }

        return [];
    }

    public function createRelease(array $attributes, array $relationships): array {
        $token = (string) $this->getUserAccessToken();
        if (!$token) {
            return [];
        }

        try {
            $this->api->setAccessToken($token);
            return $this->api->createProduct($attributes, $relationships);
        } catch (LabelcampAPIException $e) {
        }

        return [];
    }

    public function updateRelease(string $release_id, array $attributes, array $relationships): array {
        $token = (string) $this->getUserAccessToken();
        if (!$token) {
            return [];
        }

        try {
            $this->api->setAccessToken($token);
            return $this->api->updateProduct($release_id, $attributes, $relationships);
        } catch (LabelcampAPIException $e) {
        }

        return [];
    }
}
