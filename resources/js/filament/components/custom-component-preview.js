export default function customComponentPreview({
    blade = '',
    php = '',
    js = '',
    scss = '',
}) {
    return {
        blade,
        php,
        js,
        scss,

        escapeHtml(value) {
            return String(value ?? '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/'/g, '&#39;')
        },

        previewDoc() {
            const bladeMarkup = (this.blade ?? '').trim()
            const css = (this.scss ?? '').trim()
            const customJs = (this.js ?? '').trim()
            const customPhp = (this.php ?? '').trim()

            const markup =
                bladeMarkup !== ''
                    ? bladeMarkup
                    : "<div class='cc-empty'>No Blade markup yet.</div>"

            const safeJs = customJs.replace(/<\/script>/gi, '<\\/script>')
            const phpHint =
                customPhp !== ''
                    ? `<div class='cc-note'>PHP preview is not executed in-browser.</div><pre class='cc-php'>${this.escapeHtml(customPhp)}</pre>`
                    : ''

            if (/<html[\s>]/i.test(markup) || /<!doctype/i.test(markup)) {
                return markup
            }

            return `<!doctype html>
<html>
<head>
  <style>
    :root { color-scheme: light; }
    html, body { margin: 0; padding: 0; font-family: ui-sans-serif, system-ui, sans-serif; }
    body { min-height: 100%; background: #f3f4f6; color: #111827; }
    .cc-stage { width: 100%; min-height: 100%; box-sizing: border-box; padding: 12px; }
    .cc-empty { border: 1px dashed #cbd5e1; border-radius: 10px; padding: 12px; background: #fff; color: #64748b; font-size: 12px; }
    .cc-note { margin-top: 12px; font-size: 11px; color: #6b7280; }
    .cc-php { margin: 6px 0 0; padding: 8px; border-radius: 8px; background: #0f172a; color: #cbd5e1; font-size: 11px; overflow: auto; }
    ${css}
  </style>
</head>
<body>
  <div class='cc-stage'>${markup}${phpHint}</div>
  <script>${safeJs}</script>
</body>
</html>`
        },
    }
}
