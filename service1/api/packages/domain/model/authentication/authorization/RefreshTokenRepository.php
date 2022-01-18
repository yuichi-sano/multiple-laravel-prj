<?php
namespace packages\domain\model\authentication\authorization;

interface RefreshTokenRepository {
	public function save(AuthenticationRefreshToken  $authenticationRefreshToken): void;
    public function findByToken(RefreshToken  $refreshToken): AuthenticationRefreshToken;
}
