---
trigger: glob
globs: "**/*.ts", "**/*.tsx", "**/*.js", "**/*.jsx"
---

---
name: coding-standards
description: Enforces consistent code style and maintainability patterns. Apply when writing or reviewing code.
globs: ["**/*.ts", "**/*.tsx", "**/*.js", "**/*.jsx"]
---

# Coding Standards

## Rules
- Prefer explicit types over `any`
- Avoid magic values — use constants
- Fail loudly — throw errors, don't swallow them
- Log important decisions
- Don't hide complexity behind cleverness

## Naming
- Components: PascalCase
- Functions: camelCase
- Constants: SCREAMING_SNAKE_CASE
- Files: kebab-case.tsx

## Comments
- Explain "why", not "what"
- Delete commented-out code
- TODO format: `// TODO(name): description`