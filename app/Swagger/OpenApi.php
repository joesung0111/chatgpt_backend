<?php

/**
 * @OA\Info(
 *     title="ChatGPT Backend API",
 *     version="1.0.0",
 *     description="API 文件，使用 Sanctum Token 驗證保護登入後操作"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="sanctum",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */
