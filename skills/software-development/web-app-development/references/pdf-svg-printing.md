# SVG and PDF Rendering in Web Apps

Specifically for Laravel/Tailwind environments where the GD extension might be missing.

## Pitfalls
- **Blade Parser Regex Error**: Using complex regex (e.g., `['\\\"]` character classes) inside `@php` blocks in Blade templates can trigger \"Unclosed '(' does not match ']'\" errors. Move regex logic to the Controller.
- **Invalid SVG Attributes**: Using `str_replace` on an SVG string can result in duplicate attributes (e.g., `<svg width=\"300\" width=\"250\" ...>`), which causes PDF engines to fail.
- **PDF Dimension Issues**: Many PDF engines ignore SVGs if they lack explicit `width` and `height` attributes.
- **\"Nuclear\" CSS Failure**: Using `display: none !important` on main layout elements during `@media print` can accidentally hide the `@push('print-content')` block if the selectors are too broad.

## Recommended Workflow
1. **Controller-Side Generation**:
   - Generate the SVG using a dedicated writer (e.g., `Endroid\\QrCode\\Writer\\SvgWriter`).
   - Use `getString()` to get the raw XML.
   - Perform attribute cleanup (like injecting `width` and `height`) in the Controller using `preg_replace` to avoid duplicate attributes.
2. **View-Side Rendering**:
   - Pass the cleaned SVG string to the view.
   - Render using `{!! $variable !!}` to avoid HTML encoding.
3. **Print-Specific Layout**:
   - Use `@push('print-content')` to isolate the printable element.
   - Define `@page { size: 4in 4in; margin: 0; }` in a minimal `@media print` block.
   - Ensure the pushed element is explicitly set to `display: block !important` in the print CSS.

## Robust Print Strategies
- **Push vs. Direct Print**:
  - **@push/@stack**: Good for standard content, but can be unreliable in some headless browser print engines. It relies on the template hierarchy being correctly traversed by the printer.
  - **Direct Print (Recommended for Stickers/QR)**: For high-priority, single-item printouts (like a 4x4 inch sticker), place a dedicated `div` directly in the main view. Use `visibility: hidden` on the `body *` and `visibility: visible` on the target element and its children. This is more resilient to layout complexities and ensures the element is definitely in the document tree during the print pass.
