# Security Policy

## Supported Versions

Security updates are applied to the currently maintained production version of this project.

| Version / Branch    | Supported                    |
| ------------------- | ---------------------------- |
| `main` / production | Yes                          |
| Other branches      | No, unless explicitly stated |

## Reporting a Vulnerability

Do __**not**__ open a public GitHub issue for security vulnerabilities.

If you believe you have found a vulnerability, report it privately by contacting the project maintainers.

Please include as much detail as possible, including:

* A clear description of the vulnerability
* Steps to reproduce the issue
* The affected page, endpoint, feature, or workflow
* Any relevant logs, screenshots, request/response examples, or proof-of-concept code
* The potential impact, if known

We will make a good-faith effort to acknowledge valid reports promptly and investigate them as quickly as practical.

## Scope

Security reports may include, but are not limited to:

* Authentication or authorization bypasses
* Privilege escalation
* Exposure of sensitive data
* Cross-site scripting, SQL injection, command injection, or similar vulnerabilities
* Insecure file uploads or downloads
* Secrets, credentials, API keys, or tokens committed to the repository
* Misconfigured deployment, CI/CD, or production environment behavior
* Issues affecting admin-only or staff-only functionality

The following are generally out of scope unless they demonstrate a meaningful security impact:

* Automated scanner output without a reproducible exploit
* Reports requiring physical access to a maintainer’s device
* Social engineering attacks
* Denial-of-service attacks against project infrastructure
* Vulnerabilities in outdated local development environments that do not affect production
* Issues caused by intentionally misconfigured local `.env` files

## Disclosure Guidelines

We ask that reporters:

* Give maintainers reasonable time to investigate and fix the issue before public disclosure
* Avoid accessing, modifying, deleting, or exfiltrating data that does not belong to you
* Avoid degrading, interrupting, or attacking production services
* Avoid sharing vulnerability details publicly until a fix or mitigation is available

## Security Expectations for Contributors

Contributors should follow these practices:

* Do not commit secrets, credentials, tokens, private keys, or production `.env` files
* Use environment variables or approved secret storage for sensitive configuration
* Validate and authorize all user actions on the server side
* Protect admin, staff, and privileged routes with explicit authorization checks
* Avoid exposing stack traces, debug output, or sensitive configuration in production
* Keep dependencies up to date where practical
* Add or update tests for security-sensitive behavior when making related changes
* Review database migrations carefully before applying them to shared or production environments

## Secrets and Credentials

Secrets must never be stored in the repository.

This includes, but is not limited to:

* Application keys
* Database credentials
* API keys
* OAuth secrets
* SSH keys
* Deployment tokens
* GitHub Actions secrets
* Third-party service credentials

If a secret is accidentally committed, it should be considered compromised. Remove it from the repository history where appropriate, rotate the secret immediately, and review logs for possible misuse.

## Dependency Security

This project may rely on third-party packages and framework dependencies. Maintainers should review dependency updates and security advisories as part of normal maintenance.

Where applicable, use automated tools such as:

* GitHub Dependabot
* Composer audit tools
* NPM audit tools
* Framework-specific security advisories

Security updates should be prioritized based on exploitability, severity, and whether the vulnerable code is reachable in this project.

## Deployment and CI/CD Security

Deployment workflows should follow least-privilege principles.

Recommended practices include:

* Store deployment credentials only in GitHub Actions secrets or another approved secret manager
* Limit production deployment permissions to the minimum required
* Avoid printing secrets or sensitive environment values in CI logs
* Require tests to pass before deployment
* Cancel superseded deployments when newer commits are pushed
* Keep staging and production configuration separate where practical
* Review workflow changes carefully before merging

## Production Configuration

Production environments should be configured securely.

Recommended practices include:

* Disable debug mode in production
* Use HTTPS for public traffic
* Use secure session and cookie settings
* Restrict administrative access to authorized users only
* Use strong database credentials
* Keep database access limited to trusted application services
* Back up important data regularly
* Monitor logs for authentication failures, authorization failures, and unexpected errors

## Authorization-Sensitive Areas

Features involving admin, staff, moderation, account management, deployments, database changes, or user data should receive extra review.

When changing these areas, contributors should verify that:

* Unauthenticated users are rejected
* Authenticated but unauthorized users are rejected
* Authorized users can perform the intended action
* Authorization is enforced server-side, not only in the UI
* Tests cover both allowed and denied access paths

## Database Changes

Database changes should be made through reviewed migrations whenever practical.

Manual database changes should be avoided in shared or production environments unless there is a clear operational reason. When manual changes are required, they should be documented, reviewed, and reproducible.

Before applying database changes to production, maintainers should consider:

* Whether the migration is reversible
* Whether it may lock large tables
* Whether it affects existing data
* Whether it requires application changes to be deployed at the same time
* Whether backups or rollback steps are needed

## Incident Response

If a security incident is suspected:

1. Preserve relevant logs and evidence.
2. Limit further exposure where possible.
3. Rotate affected secrets or credentials.
4. Patch or mitigate the vulnerability.
5. Review whether data may have been accessed or modified.
6. Notify affected parties if required.
7. Document the incident and any follow-up actions.

## Contact

Security reports should be sent privately to `zjx-wm@vatusa.net`, or to GitHub's private vulnerability reporting feature.
