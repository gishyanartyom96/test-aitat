<?php

namespace App\Services\Api\Vivarolls;

use Ixudra\Curl\Facades\Curl;

class VivaRollsApi
{
    private static array $instances = [];
    private const VIVA_ROLLS_BASE_URL = 'https://vivarolls.magic-of-numbers.ru:8686/';
    private const USER_TOKENS = 'user/token';
    private const REPORTS = 'reports';
    private const REPORT_RUN = 'report/%s/run';
    private const APPLICATION_JSON = 'application/json';

    // Логин и пароль должны храниться в файле .env для обеспечения конфиденциальности,
    // но они хранятся в константе, чтобы мы могли получить выходные данные сразу после клонирования проекта.
    private const LOGIN = 'test@com.ru';
    private const PASSWORD = '!Sdc987gS5d@3';

    private string $authType = '';
    private string $accessToken = '';
    private array $headers = [];

    public static function getInstance(): VivaRollsApi
    {
        $cls = static::class;

        if (!isset(self::$instances[$cls])) {
            self::$instances[$cls] = new static();
        }

        return self::$instances[$cls];
    }

    public function setType(string $type): self
    {
        $this->authType = $type;

        return $this;
    }

    public function setToken(string $token): self
    {
        $this->accessToken = $token;

        return $this;
    }

    public function initHeaders(): void
    {
        $this->headers = [
            'Authorization' => $this->authType . ' ' . $this->accessToken,
        ];
    }

    private function getLoginParams(): array
    {
        return [
            'username' => self::LOGIN,
            'password' => self::PASSWORD,
        ];
    }

    private function setHeadersApplicationJson(): void
    {
        $this->headers['Content-Type'] = self::APPLICATION_JSON;
        $this->headers['accept'] = self::APPLICATION_JSON;
    }

    public function login(): array
    {
        $url = self::VIVA_ROLLS_BASE_URL . self::USER_TOKENS;

        $params = $this->getLoginParams();

        $response = Curl::to($url)
            ->withData($params)
            ->post();

        return json_decode($response, true);
    }

    public function reports(): array
    {
        $url = self::VIVA_ROLLS_BASE_URL . self::REPORTS;

        $response = Curl::to($url)
            ->withHeaders($this->headers)
            ->get();

        return json_decode($response, true);
    }

    public function report(string $id): array
    {
        $url = self::VIVA_ROLLS_BASE_URL . sprintf(self::REPORT_RUN, $id);

        $this->setHeadersApplicationJson();

        $response = Curl::to($url)
            ->withHeaders($this->headers)
            ->withData(json_encode([]))
            ->post();

        return json_decode($response, true);
    }
}
