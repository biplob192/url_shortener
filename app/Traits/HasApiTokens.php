<?php

namespace App\Traits;

use DateTimeInterface;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens as SanctumHasApiTokens;
use Laravel\Sanctum\NewAccessToken;

trait HasApiTokens
{
    use SanctumHasApiTokens;

    public function createToken(string $name,  DateTimeInterface $expiresAt = null, array $abilities = [])
    {
        $token = $this->tokens()
            ->create([
                'name'       => $name,
                'token'      => hash('sha256', $plainTextToken = Str::random(40)),
                'abilities'  => $abilities,
                'expired_at' => $expiresAt ?? now()->addMinutes(config('sanctum-refresh-token.auth_token_expiration'))
            ]);

        return new NewAccessToken($token, $plainTextToken);
    }

    public function createAuthToken(string $name, DateTimeInterface $expiresAt = null, array $abilities = [])
    {
        return $this->createToken($name, $expiresAt ?? config('sanctum-refresh-token.auth_token_expiration'), array_merge($abilities, ['auth']));
    }

    public function createRefreshToken(string $name, DateTimeInterface $expiresAt = null)
    {
        return $this->createToken($name,$expiresAt ?? now()->addMinutes(config('sanctum-refresh-token.refresh_token_expiration')), ['refresh']);
    }
}
