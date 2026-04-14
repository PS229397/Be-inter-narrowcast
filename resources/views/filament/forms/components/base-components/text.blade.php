{{-- Text component input panel --}}
<div x-show="getSelectedLeafComponent() === 'text'" class="flex h-full flex-col gap-3">
    <p class="shrink-0 text-xs font-medium text-zinc-400">Text content</p>
    <div class="flex min-h-0 flex-1 flex-col overflow-hidden rounded-xl border border-white/10 bg-[#24242a]">
        {{-- Toolbar --}}
        <div class="flex shrink-0 flex-wrap items-center gap-0.5 border-b border-white/10 px-2 py-1.5">
            <button type="button" onmousedown="event.preventDefault()" onclick="document.execCommand('bold')"
                class="rounded px-2 py-0.5 text-xs font-bold text-zinc-400 transition hover:bg-white/5 hover:text-white">B</button>
            <button type="button" onmousedown="event.preventDefault()" onclick="document.execCommand('italic')"
                class="rounded px-2 py-0.5 text-xs italic text-zinc-400 transition hover:bg-white/5 hover:text-white">I</button>
            <button type="button" onmousedown="event.preventDefault()" onclick="document.execCommand('underline')"
                class="rounded px-2 py-0.5 text-xs underline text-zinc-400 transition hover:bg-white/5 hover:text-white">U</button>
            <div class="mx-1 h-4 w-px bg-white/10"></div>
            <button type="button" onmousedown="event.preventDefault()" onclick="document.execCommand('formatBlock', false, 'h1')"
                class="rounded px-2 py-0.5 text-xs text-zinc-400 transition hover:bg-white/5 hover:text-white">H1</button>
            <button type="button" onmousedown="event.preventDefault()" onclick="document.execCommand('formatBlock', false, 'h2')"
                class="rounded px-2 py-0.5 text-xs text-zinc-400 transition hover:bg-white/5 hover:text-white">H2</button>
            <button type="button" onmousedown="event.preventDefault()" onclick="document.execCommand('formatBlock', false, 'p')"
                class="rounded px-2 py-0.5 text-xs text-zinc-400 transition hover:bg-white/5 hover:text-white">¶</button>
            <div class="mx-1 h-4 w-px bg-white/10"></div>
            <button type="button" onmousedown="event.preventDefault()" onclick="document.execCommand('justifyLeft')"
                class="rounded px-1.5 py-0.5 text-zinc-400 transition hover:bg-white/5 hover:text-white">
                <svg class="size-3.5" viewBox="0 0 16 16" fill="currentColor"><path d="M2 4h12v1.5H2zM2 7h8v1.5H2zM2 10h12v1.5H2zM2 13h8v1.5H2z"/></svg>
            </button>
            <button type="button" onmousedown="event.preventDefault()" onclick="document.execCommand('justifyCenter')"
                class="rounded px-1.5 py-0.5 text-zinc-400 transition hover:bg-white/5 hover:text-white">
                <svg class="size-3.5" viewBox="0 0 16 16" fill="currentColor"><path d="M2 4h12v1.5H2zM4 7h8v1.5H4zM2 10h12v1.5H2zM4 13h8v1.5H4z"/></svg>
            </button>
            <button type="button" onmousedown="event.preventDefault()" onclick="document.execCommand('justifyRight')"
                class="rounded px-1.5 py-0.5 text-zinc-400 transition hover:bg-white/5 hover:text-white">
                <svg class="size-3.5" viewBox="0 0 16 16" fill="currentColor"><path d="M2 4h12v1.5H2zM6 7h8v1.5H6zM2 10h12v1.5H2zM6 13h8v1.5H6z"/></svg>
            </button>
            <div class="mx-1 h-4 w-px bg-white/10"></div>
            {{-- Font size --}}
            <select
                onchange="document.execCommand('fontSize', false, this.value); this.value=''"
                class="h-6 rounded bg-transparent px-1 text-xs text-zinc-400 outline-none transition hover:bg-white/5"
            >
                <option value="">Size</option>
                <option value="1">XS</option>
                <option value="3">SM</option>
                <option value="4">MD</option>
                <option value="5">LG</option>
                <option value="6">XL</option>
                <option value="7">2XL</option>
            </select>
            <div class="mx-1 h-4 w-px bg-white/10"></div>
            {{-- Color --}}
            <label class="flex cursor-pointer items-center gap-1 rounded px-1.5 py-0.5 text-zinc-400 transition hover:bg-white/5" title="Text color">
                <svg class="size-3.5" viewBox="0 0 16 16" fill="currentColor"><path d="M8 1L2 14h2.5L6 11h4l1.5 3H14L8 1zm0 3.5L10 10H6L8 4.5z"/></svg>
                <input type="color" value="#ffffff"
                    onchange="document.execCommand('foreColor', false, this.value)"
                    class="h-4 w-4 cursor-pointer rounded-sm border-0 bg-transparent p-0 outline-none">
            </label>
        </div>
        {{-- Editable area --}}
        <div
            contenteditable="true"
            x-ref="textEditor"
            class="min-h-[120px] flex-1 overflow-y-auto p-3 text-sm text-zinc-200 outline-none [&>h1]:text-2xl [&>h1]:font-bold [&>h2]:text-lg [&>h2]:font-semibold"
            x-on:input="updateContent(selectedId, 'html', $el.innerHTML)"
            x-effect="
                if (getSelectedLeafComponent() === 'text') {
                    const val = slideContent[selectedId]?.html ?? '';
                    if ($el.innerHTML !== val) $el.innerHTML = val;
                }
            "
        ></div>
    </div>
</div>
