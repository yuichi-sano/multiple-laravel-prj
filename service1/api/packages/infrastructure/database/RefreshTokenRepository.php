<?php
namespace packages\infrastructure\database;
use packages\domain\model\authentication\authorization\AuthenticationRefreshToken;
use packages\domain\model\authentication\authorization\RefreshToken;

interface RefreshTokenRepository {
	public function save(AuthenticationRefreshToken  $authenticationRefreshToken): void;
    public function findByToken(RefreshToken  $refreshToken): AuthenticationRefreshToken;
}
