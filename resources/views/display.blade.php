<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $slideshow->title }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        html, body {
            width: 100%;
            height: 100%;
            overflow: hidden;
            background: #000;
            cursor: none;
        }

        #display-canvas {
            position: relative;
            overflow: hidden;
            background: #000;
        }

        #display-canvas.landscape {
            width: 100vw;
            height: 100vh;
        }

        #display-canvas.portrait {
            width: 100vw;
            height: 100vh;
        }

        .slide-frame {
            position: absolute;
            inset: 0;
            display: none;
        }

        .slide-frame.active {
            display: block;
        }

        .grid-node {
            position: absolute;
            overflow: hidden;
        }

        .grid-node-content {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .grid-node-content img,
        .grid-node-content video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .grid-node-content .text-content {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            color: #fff;
            font-family: sans-serif;
            font-size: clamp(1rem, 3vw, 3rem);
            text-align: center;
            overflow: hidden;
        }

        #fallback {
            display: none;
            position: absolute;
            inset: 0;
            align-items: center;
            justify-content: center;
            background: #111;
            color: #444;
            font-family: sans-serif;
            font-size: 1.5rem;
        }

        #fallback.visible {
            display: flex;
        }
    </style>
</head>
<body>
    <div id="display-canvas" class="{{ $orientation }}">
        <div id="fallback">No active slides</div>
    </div>

    <script>
        const CUSTOMER_ID  = {{ $customerId }};
        const SLIDESHOW_ID = {{ $slideshowId }};
        const POLL_INTERVAL = 30_000; // 30 seconds
        const API_URL = `/api/display/${CUSTOMER_ID}/${SLIDESHOW_ID}/slides`;

        let slides       = [];
        let currentIndex = 0;
        let playTimer    = null;
        let pollTimer    = null;

        const canvas   = document.getElementById('display-canvas');
        const fallback = document.getElementById('fallback');

        // ------------------------------------------------------------------ //
        //  Slide rendering
        // ------------------------------------------------------------------ //

        function renderGrid(node, containerEl) {
            const el = document.createElement('div');
            el.classList.add('grid-node');
            el.dataset.nodeId = node.id;

            el.style.width  = node.w || '100%';
            el.style.height = node.h || '100%';

            if (!node.children || node.children.length === 0) {
                const contentEl = document.createElement('div');
                contentEl.classList.add('grid-node-content');
                renderNodeContent(node, contentEl, currentSlideContent());
                el.appendChild(contentEl);
            } else {
                el.style.display        = 'flex';
                el.style.flexDirection  = detectSplitDirection(node.children) === 'horizontal' ? 'row' : 'column';
                node.children.forEach(child => renderGrid(child, el));
            }

            containerEl.appendChild(el);
        }

        function detectSplitDirection(children) {
            if (!children || children.length < 2) return 'horizontal';
            const first  = parseFloat(children[0].w);
            const second = parseFloat(children[1].w);
            return first + second > 150 ? 'horizontal' : 'vertical';
        }

        function renderNodeContent(node, el, slideContent) {
            const content = slideContent?.[String(node.id)];

            if (!content || !content.type) return;

            switch (content.type) {
                case 'image':
                    if (content.value) {
                        const img = document.createElement('img');
                        img.src = content.value;
                        img.alt = '';
                        el.appendChild(img);
                    }
                    break;

                case 'video':
                    if (content.value) {
                        const video = document.createElement('video');
                        video.src      = content.value;
                        video.autoplay = true;
                        video.muted    = true;
                        video.loop     = true;
                        video.playsInline = true;
                        el.appendChild(video);
                    }
                    break;

                case 'text':
                    if (content.value) {
                        const textEl = document.createElement('div');
                        textEl.classList.add('text-content');
                        textEl.innerHTML = content.value;
                        el.appendChild(textEl);
                    }
                    break;
            }
        }

        function currentSlideContent() {
            return slides[currentIndex]?.slide_content ?? {};
        }

        function buildSlideFrame(slide, index) {
            const frame = document.createElement('div');
            frame.classList.add('slide-frame');
            frame.dataset.index = index;

            const grid = slide.layout?.grid;

            if (grid) {
                renderGrid(grid, frame);
            }

            return frame;
        }

        // ------------------------------------------------------------------ //
        //  Playback
        // ------------------------------------------------------------------ //

        function showSlide(index) {
            document.querySelectorAll('.slide-frame').forEach(f => f.classList.remove('active'));

            const frame = document.querySelector(`.slide-frame[data-index="${index}"]`);
            if (frame) {
                frame.classList.add('active');
                fallback.classList.remove('visible');
            } else {
                fallback.classList.add('visible');
            }
        }

        function scheduleNext(durationSeconds) {
            clearTimeout(playTimer);
            playTimer = setTimeout(advanceSlide, durationSeconds * 1000);
        }

        function advanceSlide() {
            if (!slides.length) {
                fallback.classList.add('visible');
                return;
            }

            currentIndex = (currentIndex + 1) % slides.length;
            showSlide(currentIndex);
            scheduleNext(slides[currentIndex].duration_in_seconds ?? 10);
        }

        // ------------------------------------------------------------------ //
        //  Data fetching
        // ------------------------------------------------------------------ //

        async function fetchSlides() {
            try {
                const res = await fetch(API_URL, { credentials: 'same-origin' });

                if (res.status === 401) {
                    window.location.reload();
                    return;
                }

                const data = await res.json();
                applySlides(data.slides ?? []);
            } catch (e) {
                console.error('Failed to fetch slides', e);
            }
        }

        function applySlides(newSlides) {
            if (!newSlides.length) {
                fallback.classList.add('visible');
                clearTimeout(playTimer);
                return;
            }

            // Rebuild frames
            document.querySelectorAll('.slide-frame').forEach(f => f.remove());
            newSlides.forEach((slide, i) => {
                canvas.appendChild(buildSlideFrame(slide, i));
            });

            slides = newSlides;

            // Keep position if possible, else reset
            if (currentIndex >= slides.length) currentIndex = 0;

            showSlide(currentIndex);
            scheduleNext(slides[currentIndex].duration_in_seconds ?? 10);
        }

        // ------------------------------------------------------------------ //
        //  Boot
        // ------------------------------------------------------------------ //

        fetchSlides();
        pollTimer = setInterval(fetchSlides, POLL_INTERVAL);
    </script>
</body>
</html>
