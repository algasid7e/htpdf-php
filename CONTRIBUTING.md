# Contributing

Thanks for your interest in contributing.

## Ground rules

- Open an issue before a sizeable PR — we'd rather discuss the approach first.
- Match the surrounding style. Where lint or type-check exist, they're authoritative.
- Keep commits focused. Avoid drive-by formatting changes in the same diff as a logic change.
- No secrets, tokens, or PII in commits, ever.

## Workflow

1. Fork (external contributors) or branch (maintainers).
2. Create a feature branch off `main`.
3. Make your changes with tests where it makes sense.
4. Run the lint / type-check / test commands referenced in `.github/workflows/ci.yml`.
5. Open a PR using the [PR template](./.github/PULL_REQUEST_TEMPLATE.md).

## Reporting bugs

Use the [bug report template](./.github/ISSUE_TEMPLATE/bug_report.md). Include the smallest reproduction you can.

## Security

Do **not** open a public issue for security vulnerabilities — see [`SECURITY.md`](./SECURITY.md) for the disclosure path.

## Code of Conduct

Participation is governed by the [Contributor Covenant](./CODE_OF_CONDUCT.md).
