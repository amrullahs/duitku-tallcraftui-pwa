🧠 Brain Harvest — Antigravity

Review our current conversation and recent code changes. Identify patterns worth preserving as permanent rules, skills, or workflows in the `.agent/` folder.

Scan for these 6 categories:

1. **Rules** — Preferences or constraints stated (e.g., "never use any types", "always use shadcn components")
2. **Skills** — Reusable procedures or multi-step workflows (e.g., "how to add a new page with SEO")
3. **Error Solutions** — Bugs fixed and their root causes (e.g., "infinite re-render caused by object in useEffect deps")
4. **Stack Conventions** — Architecture or tooling decisions (e.g., "use React Query for all server state")
5. **Anti-Patterns** — Things to avoid learned the hard way (e.g., "don't use localStorage for auth tokens")
6. **Calculations / Logic** — Formulas, business rules, or domain logic worth preserving

For each finding, output in Antigravity's native format:

---
**Category:** [one of the 6 above]
**Status:** 🆕 New File | ✏️ Update to `[existing file path]`
**File Path:** `.agent/rules/[slug]/RULE.md` or `.agent/skills/[slug]/SKILL.md`
**Content:**
```markdown
---
name: [slug]
description: [one-line description]
alwaysApply: [true/false]
globs: [optional glob patterns]
---

[Ready-to-save file content]
```
---

If nothing worth capturing was found, say so — don't force it.

At the end, summarize:
- Total findings by category
- Which existing entries should be updated vs. new ones to create