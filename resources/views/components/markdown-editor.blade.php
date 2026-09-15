@props(['name', 'content' => ''])

@php($editorId = 'markdown-editor-'.$name)
@php($fieldId = $name.'-field')

<textarea name="{{ $name }}" id="{{ $fieldId }}" hidden></textarea>
<div id="{{ $editorId }}" class="bg-base-100 text-base-content min-h-50">
    {{-- Defense in depth: the caller is expected to store already-sanitized HTML,
         but this is untrusted output either way, so it's purified again here. --}}
    {!! \Stevebauman\Purify\Facades\Purify::clean($content ?? '') !!}
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/quill@2.0.0/dist/quill.snow.css" />
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.0/dist/quill.js"></script>
<script src="https://cdn.jsdelivr.net/npm/quilljs-markdown@latest/dist/quilljs-markdown.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/quilljs-markdown@latest/dist/quilljs-markdown-common-style.css" />
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const editorEl = document.getElementById(@js($editorId));
        const field = document.getElementById(@js($fieldId));

        const quill = new Quill(editorEl, {
            theme: 'snow',
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline', 'strike'],
                    ['link', 'image']
                ]
            }
        });

        // Enable markdown shortcuts
        new QuillMarkdown(quill);

        // Pasted text is frequently numbered in its source (a PDF, a Word doc, a
        // regulation reference like "1. At facilities without...") even when it
        // isn't meant to be an ordered list here. quilljs-markdown's own "1. "
        // shortcut picks that up on paste and converts it to a numbered list.
        // Force every list back to bullets after any paste, whether it came in
        // as real <ol> markup or was reformatted by the markdown shortcut engine.
        quill.clipboard.addMatcher(Node.ELEMENT_NODE, (node, delta) => {
            delta.ops.forEach((op) => {
                if (op.attributes && op.attributes.list === 'ordered') {
                    op.attributes.list = 'bullet';
                }
            });
            return delta;
        });

        quill.on('text-change', (delta, oldDelta, source) => {
            if (source !== 'user') {
                return;
            }

            let offset = 0;
            quill.getContents().ops.forEach((op) => {
                const text = typeof op.insert === 'string' ? op.insert : '';

                if (text.endsWith('\n') && op.attributes && op.attributes.list === 'ordered') {
                    quill.formatLine(offset + text.length - 1, 0, 'list', 'bullet');
                }

                offset += text.length || 1;
            });
        });

        // Keep the hidden textarea in sync so Laravel receives HTML
        field.value = editorEl.innerHTML;

        quill.on('text-change', function () {
            field.value = quill.root.innerHTML;
        });

        // Defensive: sync on submit to capture last keystrokes
        const form = field.form;
        if (form) {
            form.addEventListener('submit', function () {
                field.value = quill.root.innerHTML;
            });
        }
    });
</script>
