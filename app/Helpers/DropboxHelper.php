<?php

namespace App\Helpers;

use DropboxAPI\Session;
use DropboxAPI\DropboxAPI;
use DropboxAPI\DropboxAPIException;

use App\Models\Account;

use GuzzleHttp\Psr7\StreamWrapper;

class DropboxHelper {

    private DropboxAPI $api;

    private array $options = [
        'auto_retry' => true
    ];

    public Account $account;

    public function __construct() {
        $this->api = new DropboxAPI($this->options);
    }

    public static function new(): static {
        return new static();
    }

    public function isConnected(): bool {
        $account = Account::first();

        if ($account && $account->isActive()) {
            $this->account = $account;
            $this->setTeamHeaders();
            return true;
        }

        return false;
    }

    public function isMemberSelected(): bool {
        $account = $this->account;

        if ($account && $account->dropbox_member_id) {
            return true;
        }

        return false;
    }

    public function isFolderSelected(): bool {
        $account = $this->account;

        if ($account && $account->dropbox_namespace_id) {
            return true;
        }

        return false;
    }

    public function isReady(): bool {
        return $this->isConnected() && $this->isMemberSelected() && $this->isFolderSelected();
    }

    public function setTeamHeaders(): void {
        $this->account->dropbox_member_id && $this->api->setTeamMemberId($this->account->dropbox_member_id);
        $this->account->dropbox_namespace_id && $this->api->setNamespaceId($this->account->dropbox_namespace_id);
    }

    public function session(): Session {
        $session = new Session(
            config('services.dropbox.client_id'),
            config('services.dropbox.client_secret'),
            config('services.dropbox.redirect_uri')
        );

        return $session;
    }

    public function authorize(string $state = null): \Illuminate\Http\RedirectResponse {
        $session = $this->session();

        $url = $session->getAuthorizeUrl(array(
            'token_access_type' => 'offline',
            'include_granted_scopes' => 'team',
            'state' => $state
        ));

        return redirect()->away($url);
    }

    public function getUserAccessToken(Account &$account): string {
        if (strtotime($account->expires_at) < time() + 60) {
            $this->refreshUserAccessToken($account);
        }

        return $account->access_token;
    }

    public function refreshUserAccessToken(Account &$account): void {
        $session = $this->session();
        try {
            $result = $session->refreshAccessToken($account->refresh_token);
            if ($result) {
                $account->access_token  = $session->getAccessToken();
                $account->expires_at = date('Y-m-d H:i:s', $session->getTokenExpiration());
                $account->save();
            }
        } catch (DropboxAPIException $e) {
        }
    }

    public function getMyDetails(string $code): array {
        $session = $this->session();

        // Request a access token using the code from Dropbox
        $session->requestAccessToken($code);
        $access_token = $session->getAccessToken();
        $refresh_token = $session->getRefreshToken();
        $token_expires_at = $session->getTokenExpiration();
        $team_id = $session->getTeamId();

        $response = [
            "access_token" => $access_token,
            "refresh_token" => $refresh_token,
            "expires_at" => $token_expires_at,
            "team_id" => $team_id
        ];

        return $response;
    }

    public function metaData(string $path): array {
        $token = $this->getUserAccessToken($this->account);
        if (!$token) {
            return [];
        }

        try {
            $this->api->setAccessToken($token);
            return $this->api->metaData($path);
        } catch (DropboxAPIException $e) {
        }

        return [];
    }

    public function folders(string $path): array {
        $token = $this->getUserAccessToken($this->account);
        if (!$token) {
            return [];
        }

        try {
            $this->api->setAccessToken($token);
            return $this->api->folders($path);
        } catch (DropboxAPIException $e) {
        }

        return [];
    }

    public function createFolder(string $path): array {
        $token = $this->getUserAccessToken($this->account);
        if (!$token) {
            return [];
        }

        try {
            $this->api->setAccessToken($token);
            return $this->api->createFolder($path);
        } catch (DropboxAPIException $e) {
        }

        return [];
    }

    public function upload(string $path, string $contents, string $mode = 'add'): array {
        $token = $this->getUserAccessToken($this->account);
        if (!$token) {
            return [];
        }

        try {
            $this->api->setAccessToken($token);
            return  $this->api->upload($path, $contents, $mode);
        } catch (DropboxAPIException $e) {
        }

        return [];
    }

    public function download(string $path): string {
        $token = $this->getUserAccessToken($this->account);
        if (!$token) {
            return '';
        }

        try {
            $this->api->setAccessToken($token);
            $download_content = $this->api->download($path);

            $stream = StreamWrapper::getResource($download_content);
            $contents = stream_get_contents($stream);
            fclose($stream);

            return $contents;
        } catch (DropboxAPIException $e) {
        }

        return '';
    }

    public function downloadZip(string $path): string {
        $token = $this->getUserAccessToken($this->account);
        if (!$token) {
            return '';
        }

        try {
            $this->api->setAccessToken($token);
            $download_content = $this->api->downloadZip($path);

            $stream = StreamWrapper::getResource($download_content);
            $contents = stream_get_contents($stream);
            fclose($stream);

            return $contents;
        } catch (DropboxAPIException $e) {
        }

        return '';
    }

    public function move(string $from_path, string $to_path): array {
        $token = $this->getUserAccessToken($this->account);
        if (!$token) {
            return [];
        }

        try {
            $this->api->setAccessToken($token);
            return $this->api->move($from_path, $to_path);
        } catch (DropboxAPIException $e) {
        }

        return [];
    }

    public function delete(string $path): array {
        $token = $this->getUserAccessToken($this->account);
        if (!$token) {
            return [];
        }

        try {
            $this->api->setAccessToken($token);
            return $this->api->delete($path);
        } catch (DropboxAPIException $e) {
        }

        return [];
    }

    public function createSharedLink(string $path): array {
        $token = $this->getUserAccessToken($this->account);
        if (!$token) {
            return [];
        }

        try {
            $this->api->setAccessToken($token);
            return $this->api->createSharedLink($path);
        } catch (DropboxAPIException $e) {
        }

        return [];
    }

    public function listSharedLinks(string $path): array {
        $token = $this->getUserAccessToken($this->account);
        if (!$token) {
            return [];
        }

        try {
            $this->api->setAccessToken($token);
            return $this->api->listSharedLinks($path);
        } catch (DropboxAPIException $e) {
        }

        return [];
    }

    public function getTeamMembers(): array {
        $token = $this->getUserAccessToken($this->account);
        if (!$token) {
            return [];
        }

        try {
            $this->api->setAccessToken($token);
            return $this->api->teamMembers();
        } catch (DropboxAPIException $e) {
        }

        return [];
    }

    public function getTeamFolders(): array {
        $token = $this->getUserAccessToken($this->account);
        if (!$token) {
            return [];
        }

        try {
            $this->api->setAccessToken($token);
            return $this->api->teamFolders();
        } catch (DropboxAPIException $e) {
        }

        return [];
    }

    public function createTeamFolder(string $name, string $sync_setting = null): array {
        $token = $this->getUserAccessToken($this->account);
        if (!$token) {
            return [];
        }

        try {
            $this->api->setAccessToken($token);
            return $this->api->createTeamFolder($name, $sync_setting);
        } catch (DropboxAPIException $e) {
        }

        return [];
    }

    public function renameFolder(string $old_folder_path, string $new_folder_path): array {
        if (strtolower($old_folder_path) == strtolower($new_folder_path)) {
            return [];
        }

        return $this->move($old_folder_path, $new_folder_path);
    }
}
