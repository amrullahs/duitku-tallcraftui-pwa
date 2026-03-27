---
name: run-audit
description: Runs a security audit on the codebase, checking for vulnerabilities and best practice violations in TypeScript files.
---

# Run Audit

Analyze the codebase for security vulnerabilities and best practice violations.

## When to Use
- Before a release or deployment
- After adding new dependencies
- When refactoring security-sensitive code

## Steps
1. Scan all TypeScript/TSX files in the project
2. Check for common vulnerabilities:
   - Exposed secrets or API keys
   - SQL injection patterns
   - XSS vulnerabilities
   - Unsafe type assertions
3. Validate against the project's coding standards
4. Generate a structured report

## Output Format
Return findings as:
- **Critical**: Must fix before deploy
- **Warning**: Should fix soon
- **Info**: Suggestions for improvement

## Edge Cases
- No files found → Return empty report with explanation
- Permission denied → Log the file and continue
- Large codebase → Process in batches, report progress