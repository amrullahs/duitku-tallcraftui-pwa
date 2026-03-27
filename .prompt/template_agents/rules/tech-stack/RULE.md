---
trigger: glob
globs: "**/*.ts", "**/*.tsx", "package.json"
---

---
name: tech-stack
description: Defines the technology constraints and patterns for this project. Apply when making architectural decisions or adding dependencies.
globs: ["**/*.ts", "**/*.tsx", "package.json"]
---

# Tech Stack

## Stack
- Framework: [e.g., Next.js 14 with App Router]
- Database: [e.g., Supabase with Row Level Security]
- Styling: [e.g., Tailwind CSS + shadcn/ui]
- State: [e.g., React Query for server state]

## Patterns
- Use server components by default
- Client components only when needed (interactivity, hooks)
- All database access through server actions or API routes
- Never expose API keys in client code

## File Structure
- `/app` — Routes and pages
- `/components` — Reusable UI components
- `/lib` — Utilities and helpers
- `/hooks` — Custom React hooks