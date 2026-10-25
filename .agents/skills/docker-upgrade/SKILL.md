---
name: docker-image-upgrade
description: "Use when Docker image version needs to be updated or upgraded. Guides through the documented upgrade process for maintaining security and compatibility in Docker images."
license: MIT
metadata:
  author: oki2a24
---

# Docker Image Upgrade

Use this skill when Docker image version needs to be updated or upgraded. This skill guides through the documented upgrade process for maintaining security and compatibility in Docker images.

## When to Use This Skill

Use this skill when:
- Docker image version needs to be updated or upgraded
- Base images need updating for security patches or feature compatibility
- Dockerfile needs modification for version upgrades
- Docker Compose configuration needs adjustment for version changes

## Core Principle

Before updating any Docker image, always consult the documented upgrade process in `docs/DOCKER_UPDATE_GUIDE.md` and `docs/policies/upgrade-policy.md` to ensure consistent version handling and pinning strategies.

## Process Overview

1. Read the upgrade documentation (`docs/DOCKER_UPDATE_GUIDE.md`, `docs/policies/upgrade-policy.md`).
2. Check current Docker image versions in Dockerfiles and docker-compose files.
3. Determine target version from official sources or requirements.
4. Update Dockerfiles with new version specifications.
5. Rebuild and validate images.
6. Verify installed versions and update pinning if needed.

## Workflow

- **Base images**: Pin to latest patch version (e.g., `php:8.5.12`).
- **System packages**: Use major-version wildcard pinning (e.g., `libicu-dev=76.*`).
- **After update**: Inspect installed versions in container (`apt-cache policy`) and adjust pins accordingly.
- **Verify**: Run `docker compose build` and run tests before finalizing.

## Related Skills

- Laravel best practices, testing best practices, and systematic debugging when handling upgrade-related changes.
