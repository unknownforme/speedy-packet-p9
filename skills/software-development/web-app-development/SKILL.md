---
name: web-app-development
description: Use when building or debugging web applications.
---

# Web Application Development

Use this skill when performing tasks related to the architecture, configuration, debugging, or expansion of web-based applications.

## Always-on Rules
- Always verify `.env` configuration values (like `APP_URL`) against the actual environment to avoid CSRF and session issues.
- Check for valid CSRF tokens (`@csrf`) in all state-changing forms.
- When updating navigation, ensure changes are applied to both desktop and responsive (mobile) views.
- **User UI Preference**: For the current user, prefer "Cubic" designs (using `rounded-none` instead of `rounded-*`) and "Dark" color palettes (e.g., deep autumn or forest tones) over bright or heavily rounded interfaces.

## Procedures

### Project Scaffolding & Planning
When starting a new project or a large-scale feature, create a comprehensive TODO list (e.g., `TODO.md`) before writing code.
1.  **Scope**: Define the core entities, roles, and user stories.
2.  **Architecture**: Outline the database schema and relationships.
3.  **TODO Structure**: Ensure the TODO includes:
    *   Database Migrations.
    *   Models & Relationships.
    *   **Database Seeders** (explicitly include these in the initial plan to ensure immediate testability with Admin and role-specific data).
    *   Authentication & Authorization (Roles/Permissions).
    *   Core business logic/features.
    *   Frontend/UI components.
    *   API endpoints.
    *   Testing/QA.

### Full-Stack Feature Rollout
When implementing a new feature, follow this sequence to ensure database integrity and UI consistency:
1.  **Schema**: Create a migration to modify the database structure.
2.  **Model**: Update the model's `$fillable` array to allow mass assignment of new fields.
3.  **Logic**: Implement business logic in the Controller (e.g., adding sorting, filtering, or validation).
4.  **View**: Update Blade templates to reflect the new data (e.g., adding dropdown filters, color-coded status badges, or sorting icons).
5.  **Verification**: Create or update Seeders to allow rapid testing of the new feature with realistic data.

## Pitfalls
- **Omit Seeders from the initial project TODO** — manual data entry blocks rapid testing; include seeders for Admin and all relevant roles in the first phase to create a functional test environment immediately.
- **Schema-Seeder Desynchronization** — Changing a column type in a migration (e.g., `string` to `integer`) without updating the corresponding seeder logic or model casting can cause `migrate:fresh --seed` to fail or result in corrupted test data; always update the Migration, Model casting, and Seeder logic in a single pass.
- **Indiscriminate Asset Replacement** — When asked to update or remove visual assets (like SVGs), distinguish between high-impact \"Hero\" assets and repetitive \"Feature\" assets; replacing all assets with a single type or removing the wrong ones can destroy the visual hierarchy requested by the user.
- **display: none on Parent in Print Media Queries** — Use targeted classes (like .print-hide) for non-printable elements instead of blanket display: none on parent containers in `@media print` — `display: none` on a parent removes the entire subtree from the document flow, rendering child `display: block` rules ineffective. Additionally, when printing SVGs, ensure the `<svg>` tag contains explicit `width` and `height` attributes; while browsers scale SVGs to fit containers on-screen, PDF print engines often treat SVGs without dimensions as $0 \\times 0$ size, making them invisible in the final output.
- **Unreliable @push/@stack for Printing** — Relying on Laravel's @push/@stack mechanism for critical print elements (like a QR code) can be unreliable in certain PDF/print rendering contexts. For high-priority, single-item printouts, use a dedicated, direct <div> within the main view and control its visibility using CSS (e.g., applying 'visibility: hidden' to the body and 'visibility: visible' to the target element) to ensure the print engine captures it.
- **Blade Syntax Corruption** — When using Python f-strings within `execute_code` to write or patch Blade templates, the `{{` and `}}` syntax is interpreted as interpolation placeholders; always use standard strings or escape braces to prevent destroying the template syntax.
- **Blade Parser Regex Error** — Using complex regex patterns (e.g., containing `['\\\"]` character classes) inside `@php` directives in Blade templates can trigger \"Unclosed '(' does not match ']'\" errors. Move all heavy string manipulation and regex logic to the Controller to keep the View template parsing stable.
- **SVG Attribute Duplication via str_replace** — When injecting dimensions into an SVG string using str_replace, avoid simply appending new attributes; this can result in duplicate attributes (e.g., <svg width='300' width='250' ...>), creating an invalid tag that many PDF print engines will fail to render. If using regex to fix this, perform the operation in the Controller rather than inside a Blade @php block to avoid parsing errors.

## References
- [Laravel Specific Pitfalls](references/laravel-pitfalls.md)
- [PDF/SVG Printing Reference](references/pdf-svg-printing.md)
