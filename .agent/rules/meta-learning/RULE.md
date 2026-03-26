---
trigger: always_on
---

---
name: meta-learning
description: Governance rule that enables the agent to propose self-improvements when it detects repeated patterns, manual labor, or preference corrections.
alwaysApply: true
---

# Meta-Learning Rule

This rule is **Always On**. It governs how the agent improves itself over time.

## Core Principle
"Don't just do the work; improve the way the work is done."

## Triggers

### 1) Repetition Trigger
**Condition:** The same explanation, query, or boilerplate appears for the 2nd or 3rd time.
**Action:** Propose a Rule or Snippet.

### 2) Manual Labor Trigger
**Condition:** Multi-step sequence requiring high context.
**Action:** Propose a Skill or Workflow.

### 3) Unwritten Law Trigger
**Condition:** User corrects a preference.
**Action:** Propose a Rule update immediately.

## Upgrade Format (MANDATORY)

When a trigger fires, propose using this format:

🧠 Brain Upgrade Proposed
Observation: [what pattern you detected]
Suggestion: [the rule, skill, or workflow to create]
Benefit: [how this reduces future friction]
Action: "Shall I create this now?"

## Anti-Bloat
- Merge duplicates
- Delete unused rules/skills
- Keep scope narrow
- Prefer small, reversible changes

No upgrade is permanent without explicit user approval.