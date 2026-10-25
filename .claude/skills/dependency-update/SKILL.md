---
name: dependency-update
description: "Use when PHP (composer) or Node (npm) package dependencies need to be updated for security, features, or compatibility."
license: MIT
metadata:
  author: oki2a24
---

# Dependency Update

Use this skill when PHP or Node package dependencies need updating to maintain security and compatibility.

## When to Use This Skill

Use this skill when:
- `composer.json` or `package.json` dependencies have available updates
- Security patches for Laravel, PHP libraries, or frontend dependencies require updates
- Project base image updates may require dependency adjustments
- Dependency versions need verification and testing after updates

## Process Overview

1. Read upgrade documentation (`docs/DOCKER_UPDATE_GUIDE.md`, `docs/policies/upgrade-policy.md`).
2. Check current dependency versions (`composer show`, `npm list`).
3. Update dependencies (`composer update`, `npm update`).
4. Rebuild containers and run tests.
5. Inspect installed versions and adjust pinning if needed.
6. Commit changes.

## Workflow

- Update `composer.json` / `package.json` via `composer update` or `npm update`.
- Rebuild with `docker compose build --no-cache`.
- Inspect installed versions (`apt-cache policy`, `composer show`).
- Verify functionality with `make php-test`.
- Commit with Conventional Commits and Japanese body.

## Related Skills

- `docker-image-upgrade` for Docker base image updates
- `laravel-best-practices` for Laravel code adjustments
- `systematic-debugging` for troubleshooting issues
