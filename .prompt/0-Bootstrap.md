🚀 Learner Brain Bootstrap — Antigravity

Create the full Learner Brain folder structure for this project using Antigravity's auto-discovery conventions.

Generate the following files:

```
.agent/
├── instructions.md          # Project constitution (always loaded)
├── meta/
│   └── upgrade-protocol.md  # How to propose brain upgrades
├── rules/
│   ├── meta-learning/
│   │   └── RULE.md           # alwaysApply: true — governance
│   ├── tech-stack/
│   │   └── RULE.md           # globs: ["**/*.ts","**/*.tsx"] — architecture
│   └── coding-standards/
│       └── RULE.md           # globs: ["**/*.ts","**/*.tsx"] — style
├── skills/
│   └── run-audit/
│       └── SKILL.md          # Security audit procedure
│   └── deploy/
│       └── SKILL.md          # Deployment procedure
└── workflows/
    └── feature-launch.md     # Multi-phase feature shipping
```

For each file:
- Include proper frontmatter (name, description, globs/alwaysApply)
- Follow Antigravity's RULE.md and SKILL.md naming conventions
- Include the Meta-Learning Protocol with 3 triggers
- Customize the tech stack section for THIS project's actual stack
- Add placeholder sections for project-specific patterns

After creating all files, confirm the structure and explain how auto-discovery works.