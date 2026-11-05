# changelog
## [Unreleased]
### Added
- Login con token de Sanctum (POST /api/v1/auth/login)
- Perfil del usuario autenticado (GET /api/v1/auth/me)
- Cierre de sesión que revoca el token (POST /api/v1/auth/logout)
- Bloqueo de usuarios inactivos y límite de 5 intentos de login por minuto
- Esquema Bearer en la documentación

### Changed
- El endpoint de salud pasa a un controlador invocable