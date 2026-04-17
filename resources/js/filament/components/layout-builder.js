export default function layoutBuilder(config) {
    return {
        state: config.state,
        orientation: config.orientation ?? 'landscape',
        emptyGrid: config.emptyGrid ?? {
            version: 1,
            id: 'root',
            direction: null,
            split: 50,
            children: [],
            component: null,
            componentType: null,
            componentConfig: {},
            customCss: null,
            customJs: null,
        },
        baseComponents: config.baseComponents ?? [],
        customComponents: config.customComponents ?? [],
        standalone: config.standalone ?? false,
        pageTitle: config.pageTitle ?? '',
        isEditing: config.isEditing ?? false,

        realSizes: {
            landscape: { width: 1920, height: 1080 },
            portrait: { width: 1080, height: 1920 },
        },

        canvasCornerRadius: '0.75rem',
        grid: null,
        nodeCounter: 0,
        generatedNodeIndex: 0,
        selectedId: null,
        isDragging: false,
        stateSignature: null,
        nodesById: new Map(),
        parentsById: new Map(),
        elementsById: new Map(),
        leafOrderMap: new Map(),
        baseComponentsByKey: new Map(),
        customComponentsByKey: new Map(),
        customJsCleanups: new Map(),
        openCustomizeHandler: null,
        saveCustomizeHandler: null,
        previewCustomizeHandler: null,
        previewNodeId: null,
        previewCss: null,

        init() {
            this._initComponentIndexes()
            this._initGrid()

            if (this.openCustomizeHandler) {
                window.removeEventListener('lb-open-customize', this.openCustomizeHandler)
            }

            this.openCustomizeHandler = () => {
                this._dispatchCustomizePayload()
            }

            window.addEventListener('lb-open-customize', this.openCustomizeHandler)

            if (this.saveCustomizeHandler) {
                window.removeEventListener('lb-save-customize', this.saveCustomizeHandler)
            }

            this.saveCustomizeHandler = (event) => {
                this.saveCustomizeCode(event?.detail ?? {})
            }

            window.addEventListener('lb-save-customize', this.saveCustomizeHandler)

            if (this.previewCustomizeHandler) {
                window.removeEventListener(
                    'lb-preview-customize',
                    this.previewCustomizeHandler,
                )
            }

            this.previewCustomizeHandler = (event) => {
                this.previewCustomizeCode(event?.detail ?? {})
            }

            window.addEventListener(
                'lb-preview-customize',
                this.previewCustomizeHandler,
            )

            this.$watch('state', (value) => {
                const normalized = this._normalizeGrid(value)
                const signature = this._serializeGrid(normalized)

                if (signature === this.stateSignature) {
                    return
                }

                this.grid = normalized
                this.nodeCounter = this._maxNodeId(this.grid)
                this.stateSignature = signature
                this._rebuildGridIndexes()

                this.$nextTick(() => this.renderStructure())
            })

            this.$watch('orientation', () => {
                this.$nextTick(() => this.renderStructure())
            })

            this.$nextTick(() => this.renderStructure())
            this._dispatchCustomizePayload()
        },

        stageStyle() {
            if (this.standalone) {
                if (this.orientation === 'portrait') {
                    return 'aspect-ratio: 9 / 16; height: 100%; max-width: 488.25px; width: auto;'
                }

                return 'aspect-ratio: 16 / 9; width: 100%; max-height: 520px;'
            }

            if (this.orientation === 'portrait') {
                return 'aspect-ratio: 540 / 960; max-width: 360px; max-height: 520px;'
            }

            return 'aspect-ratio: 960 / 540; max-width: 100%; max-height: 520px;'
        },

        _clone(value) {
            return JSON.parse(JSON.stringify(value))
        },

        _serializeGrid(value) {
            return JSON.stringify(value)
        },

        _initComponentIndexes() {
            this.baseComponentsByKey = new Map(
                this.baseComponents.map((component) => [component.key, component]),
            )
            this.customComponentsByKey = new Map(
                this.customComponents.map((component) => [component.key, component]),
            )
        },

        _initGrid() {
            this.grid = this._normalizeGrid(this.state)
            this.nodeCounter = this._maxNodeId(this.grid)
            this.stateSignature = this._serializeGrid(this.grid)
            this._rebuildGridIndexes()
        },

        _rebuildGridIndexes() {
            const nodesById = new Map()
            const parentsById = new Map()
            const leafOrderMap = new Map()
            let leafIndex = 0

            const walk = (node, parent = null) => {
                nodesById.set(node.id, node)

                if (parent) {
                    parentsById.set(node.id, parent)
                }

                const children = node.children ?? []

                if (children.length === 0) {
                    leafOrderMap.set(node.id, ++leafIndex)

                    return
                }

                children.forEach((child) => walk(child, node))
            }

            walk(this.grid)

            if (this.selectedId && !nodesById.has(this.selectedId)) {
                this.selectedId = null
            }

            this.nodesById = nodesById
            this.parentsById = parentsById
            this.leafOrderMap = leafOrderMap
            this.elementsById = new Map()
        },

        _emptyGrid() {
            return this._clone(this.emptyGrid)
        },

        _normalizeGrid(raw) {
            this.generatedNodeIndex = 0

            if (!raw || (Array.isArray(raw) && raw.length === 0)) {
                return this._emptyGrid()
            }

            try {
                const parsed =
                    typeof raw === 'string' ? JSON.parse(raw) : this._clone(raw)

                if (!parsed || Array.isArray(parsed) || typeof parsed !== 'object') {
                    return this._emptyGrid()
                }

                return this._normalizeNode(parsed, 'root', true)
            } catch (_) {
                return this._emptyGrid()
            }
        },

        _normalizeNode(node, fallbackId = 'root', isRoot = false) {
            const component =
                typeof node.component === 'string' && node.component !== ''
                    ? node.component
                    : null

            const normalized = {
                id: isRoot ? 'root' : this._normalizeNodeId(node.id, fallbackId),
                direction:
                    node.direction === 'h' || node.direction === 'v'
                        ? node.direction
                        : null,
                split: this._normalizeSplit(node.split),
                children: [],
                component,
                componentType: component ? this._resolveComponentType(component) : null,
                componentConfig:
                    node.componentConfig &&
                    typeof node.componentConfig === 'object' &&
                    !Array.isArray(node.componentConfig)
                        ? this._clone(node.componentConfig)
                        : {},
                customCss: this._normalizeCustomCode(node.customCss),
                customJs: this._normalizeCustomCode(node.customJs),
            }

            if (isRoot) {
                normalized.version = this.emptyGrid.version ?? 1
            }

            if (Array.isArray(node.children) && node.children.length > 0) {
                normalized.children = node.children
                    .slice(0, 2)
                    .filter((child) => child && typeof child === 'object' && !Array.isArray(child))
                    .map((child) => this._normalizeNode(child))
            }

            if (normalized.children.length !== 2) {
                normalized.children = []
                normalized.direction = null
            }

            if (normalized.children.length) {
                normalized.component = null
                normalized.componentType = null
                normalized.componentConfig = {}
                normalized.customCss = null
                normalized.customJs = null
            }

            return normalized
        },

        _normalizeCustomCode(value) {
            if (typeof value !== 'string') {
                return null
            }

            return value.trim() === '' ? null : value
        },

        _defaultCustomizeCssTemplate() {
            return [
                'background-color: transparent;',
                'background-image: none;',
                'background-size: auto;',
                'background-position: 0% 0%;',
                'background-repeat: repeat;',
            ].join('\n')
        },

        _extractCssDeclarations(css) {
            const raw = this._normalizeCustomCode(css)
            if (!raw) {
                return null
            }

            const start = raw.indexOf('{')
            const end = raw.lastIndexOf('}')

            if (start === -1 || end === -1 || end <= start) {
                return raw
            }

            const inner = raw
                .slice(start + 1, end)
                .split('\n')
                .map((line) => line.trim())
                .filter((line) => line.length > 0)
                .join('\n')

            return inner || null
        },

        _canonicalizeCssDeclarations(css) {
            const declarations = this._extractCssDeclarations(css)
            if (!declarations) {
                return null
            }

            const normalizedLines = declarations
                .split('\n')
                .map((line) => line.trim().toLowerCase())
                .filter((line) => line.length > 0)
                .map((line) => line.replace(/\s+/g, ' '))
                .map((line) => line.replace(/\s*:\s*/g, ': '))
                .map((line) => (line.endsWith(';') ? line : `${line};`))
                .sort()

            return normalizedLines.join('\n')
        },

        _hasMeaningfulCustomCss(css) {
            const normalized = this._canonicalizeCssDeclarations(css)
            if (!normalized) {
                return false
            }

            const template = this._canonicalizeCssDeclarations(
                this._defaultCustomizeCssTemplate(),
            )

            return normalized !== template
        },

        _hasMeaningfulCustomJs(js) {
            const normalized = this._normalizeCustomCode(js)
            if (!normalized) {
                return false
            }

            return normalized.trim() !== '// Customize this section'
        },

        _nodeHasCustomCode(node) {
            if (!node || (node.children ?? []).length > 0) {
                return false
            }

            const hasSavedCustomCss = this._hasMeaningfulCustomCss(node.customCss)
            const hasSavedCustomJs = this._hasMeaningfulCustomJs(node.customJs)
            const hasPreviewCustomCss =
                this.previewNodeId === node.id &&
                this._hasMeaningfulCustomCss(this.previewCss)

            return hasSavedCustomCss || hasSavedCustomJs || hasPreviewCustomCss
        },

        _createCodeIndicatorElement() {
            const badge = document.createElement('div')
            badge.className = 'lb-node-code-indicator'

            const icon = document.createElementNS('http://www.w3.org/2000/svg', 'svg')
            icon.setAttribute('class', 'lb-node-code-icon')
            icon.setAttribute('viewBox', '0 0 20 20')
            icon.setAttribute('fill', 'none')
            icon.setAttribute('aria-hidden', 'true')

            const left = document.createElementNS('http://www.w3.org/2000/svg', 'path')
            left.setAttribute('d', 'M7 7L3 10L7 13')
            left.setAttribute('stroke', 'currentColor')
            left.setAttribute('stroke-width', '1.5')
            left.setAttribute('stroke-linecap', 'round')
            left.setAttribute('stroke-linejoin', 'round')

            const right = document.createElementNS('http://www.w3.org/2000/svg', 'path')
            right.setAttribute('d', 'M13 7L17 10L13 13')
            right.setAttribute('stroke', 'currentColor')
            right.setAttribute('stroke-width', '1.5')
            right.setAttribute('stroke-linecap', 'round')
            right.setAttribute('stroke-linejoin', 'round')

            const slash = document.createElementNS('http://www.w3.org/2000/svg', 'path')
            slash.setAttribute('d', 'M11 5L9 15')
            slash.setAttribute('stroke', 'currentColor')
            slash.setAttribute('stroke-width', '1.5')
            slash.setAttribute('stroke-linecap', 'round')
            slash.setAttribute('stroke-linejoin', 'round')

            icon.appendChild(left)
            icon.appendChild(right)
            icon.appendChild(slash)
            badge.appendChild(icon)

            return badge
        },

        _normalizeNodeId(id, fallbackId = null) {
            if (typeof id === 'string' && id !== '' && id !== 'root') {
                return id
            }

            return fallbackId ?? `n${++this.generatedNodeIndex}`
        },

        _normalizeSplit(split) {
            const numeric = Number(split)

            if (!Number.isFinite(numeric)) {
                return 50
            }

            return Math.max(5, Math.min(95, Math.round(numeric)))
        },

        _resolveComponentType(key) {
            if (this.baseComponentsByKey.has(key)) {
                return 'base'
            }

            if (this.customComponentsByKey.has(key) || /^custom:\d+$/.test(key)) {
                return 'custom'
            }

            return null
        },

        _maxNodeId(node) {
            let max = 0

            const walk = (current) => {
                const match = parseInt((current.id ?? '').replace(/\D/g, ''), 10) || 0

                if (match > max) {
                    max = match
                }

                ;(current.children ?? []).forEach(walk)
            }

            walk(node)

            return max
        },

        _makeNode() {
            return {
                id: `n${++this.nodeCounter}`,
                direction: null,
                split: 50,
                children: [],
                component: null,
                componentType: null,
                componentConfig: {},
                customCss: null,
                customJs: null,
            }
        },

        _save() {
            const next = this._clone(this.grid)
            next.version = this.emptyGrid.version ?? 1
            this.stateSignature = this._serializeGrid(next)
            this.state = next
        },

        findNode(_node, id) {
            return this.nodesById.get(id) ?? null
        },

        findParent(_node, id) {
            return this.parentsById.get(id) ?? null
        },

        slice(nodeId, direction) {
            const node = this.findNode(this.grid, nodeId)

            if (!node || (node.children ?? []).length > 0) {
                return
            }

            const inheritedComponent = node.component
            const inheritedComponentType = node.componentType
            const inheritedComponentConfig = this._clone(node.componentConfig ?? {})
            const inheritedCustomCss = node.customCss ?? null
            const inheritedCustomJs = node.customJs ?? null

            node.direction = direction
            node.split = 50
            node.component = null
            node.componentType = null
            node.componentConfig = {}
            node.customCss = null
            node.customJs = null
            node.children = [this._makeNode(), this._makeNode()]
            node.children[0].component = inheritedComponent
            node.children[0].componentType = inheritedComponentType
            node.children[0].componentConfig = inheritedComponentConfig
            node.children[0].customCss = inheritedCustomCss
            node.children[0].customJs = inheritedCustomJs
            this.selectedId = node.children[0].id

            this._rebuildGridIndexes()
            this._save()
            this.renderStructure()
        },

        deleteNode(nodeId) {
            if (nodeId === 'root') {
                return
            }

            const parent = this.findParent(this.grid, nodeId)

            if (!parent) {
                return
            }

            parent.children = parent.children.filter((child) => child.id !== nodeId)

            if (parent.children.length === 1) {
                const survivor = parent.children[0]

                parent.direction = survivor.direction
                parent.split = survivor.split
                parent.component = survivor.component
                parent.componentType = survivor.componentType
                parent.componentConfig = this._clone(survivor.componentConfig ?? {})
                parent.customCss = survivor.customCss ?? null
                parent.customJs = survivor.customJs ?? null
                parent.children = survivor.children
            }

            this.selectedId = null
            this._rebuildGridIndexes()
            this._save()
            this.renderStructure()
        },

        clearCanvas() {
            this.nodeCounter = 0
            this.generatedNodeIndex = 0
            this.grid = this._emptyGrid()
            this.selectedId = null

            this._rebuildGridIndexes()
            this._save()
            this.renderStructure()
        },

        assignComponent(type) {
            if (!this.selectedId) {
                return
            }

            const node = this.findNode(this.grid, this.selectedId)

            if (!node || (node.children ?? []).length > 0) {
                return
            }

            if (node.component === type) {
                node.component = null
                node.componentType = null
                node.componentConfig = {}
            } else {
                node.component = type
                node.componentType = this._resolveComponentType(type)
                node.componentConfig = node.componentConfig ?? {}
            }

            this._save()
            this.renderStructure()
        },

        isCustomComponent(type) {
            return typeof type === 'string' && type.startsWith('custom:')
        },

        getCustomComponent(type) {
            if (!this.isCustomComponent(type)) {
                return null
            }

            return (
                this.customComponentsByKey.get(type) ??
                this.customComponents.find((component) => component.key === type) ??
                null
            )
        },

        getComponentIcon(type) {
            if (!type) {
                return null
            }

            const baseComponent =
                this.baseComponentsByKey.get(type) ??
                this.baseComponents.find((component) => component.key === type)

            if (baseComponent) {
                return baseComponent.icon
            }

            return this.getCustomComponent(type)?.icon ?? null
        },

        selectedNode() {
            if (!this.selectedId || !this.grid) {
                return null
            }

            return this.nodesById.get(this.selectedId) ?? null
        },

        selectedComponentKey() {
            return this.selectedNode()?.component ?? null
        },

        isLeafSelected() {
            const node = this.selectedNode()

            return node !== null && (node.children ?? []).length === 0
        },

        saveCustomizeCode(payload = {}) {
            const targetId =
                typeof payload.nodeId === 'string' && payload.nodeId !== ''
                    ? payload.nodeId
                    : this.selectedId

            if (!targetId) {
                return
            }

            const node = this.findNode(this.grid, targetId)

            if (!node || (node.children ?? []).length > 0) {
                return
            }

            node.customCss = this._normalizeCustomCode(payload.css)
            node.customJs = this._normalizeCustomCode(payload.js)

            if (this.previewNodeId === targetId) {
                this.previewNodeId = null
                this.previewCss = null
            }

            this.selectedId = targetId
            this._save()
            this.renderStructure()
            this._dispatchCustomizePayload(node)
        },

        previewCustomizeCode(payload = {}) {
            const previousPreviewNodeId = this.previewNodeId
            const nextPreviewNodeId =
                typeof payload.nodeId === 'string' && payload.nodeId !== ''
                    ? payload.nodeId
                    : null
            const nextPreviewCss = this._normalizeCustomCode(payload.css)

            this.previewNodeId = nextPreviewNodeId
            this.previewCss = nextPreviewCss

            if (previousPreviewNodeId && previousPreviewNodeId !== nextPreviewNodeId) {
                const previousNode = this.nodesById.get(previousPreviewNodeId)
                const previousEl = this.elementsById.get(previousPreviewNodeId)

                if (previousNode && previousEl) {
                    this._applyCustomCss(previousNode, previousEl)
                    this._syncNodeCodeBadge(previousNode, previousEl)
                }
            }

            if (!nextPreviewNodeId) {
                return
            }

            const node = this.nodesById.get(nextPreviewNodeId)
            const el = this.elementsById.get(nextPreviewNodeId)

            if (!node || !el || (node.children ?? []).length > 0) {
                return
            }

            this._applyPreviewCssToElement(node, el)
            this._syncNodeCodeBadge(node, el)
        },

        _dispatchCustomizePayload(node = this.selectedNode()) {
            const isLeaf = node !== null && (node.children ?? []).length === 0
            const hasCustomCss =
                isLeaf &&
                typeof node.customCss === 'string' &&
                node.customCss.trim() !== ''
            const computedCssPreset =
                isLeaf && !hasCustomCss
                    ? this._buildComputedCssPreset(node.id)
                    : ''

            window.dispatchEvent(
                new CustomEvent('lb-load-customize', {
                    detail: {
                        nodeId: isLeaf ? node.id : null,
                        css: isLeaf ? node.customCss ?? computedCssPreset : '',
                        js: isLeaf ? node.customJs ?? '' : '',
                        hasCustomCss,
                    },
                }),
            )
        },

        _buildComputedCssPreset(nodeId) {
            const node = this.nodesById.get(nodeId)
            const el = this.elementsById.get(nodeId)

            if (!node || !el) {
                return ''
            }

            const isPreviewingNode =
                this.previewNodeId === nodeId &&
                typeof this.previewCss === 'string' &&
                this.previewCss.trim() !== ''

            // Read computed defaults from persisted state, not transient preview state.
            this._applyCustomCss(node, el)
            const styles = getComputedStyle(el)
            const properties = [
                'background-color',
                'background-image',
                'background-size',
                'background-position',
                'background-repeat',
                'color',
                'opacity',
                'border-radius',
                'padding',
                'margin',
                'box-shadow',
            ]

            const declarations = properties
                .map((property) => `${property}: ${styles.getPropertyValue(property).trim()};`)
                .filter((declaration) => !declaration.endsWith(': ;'))

            if (isPreviewingNode) {
                el.setAttribute('style', this.previewCss)
            }

            return declarations.join('\n')
        },

        fmtPill(wPct, hPct) {
            const real = this.realSizes[this.orientation] ?? this.realSizes.landscape

            return `${Math.round((real.width * wPct) / 100)}x${Math.round((real.height * hPct) / 100)}px`
        },

        updateAllPills() {
            const container = this.$refs.gridContainer

            if (!container) {
                return
            }

            const rootRect = container.getBoundingClientRect()

            if (rootRect.width <= 0 || rootRect.height <= 0) {
                return
            }

            for (const [nodeId, el] of this.elementsById) {
                const node = this.nodesById.get(nodeId)

                if (!node || (node.children ?? []).length > 0) {
                    continue
                }

                const pill = el.querySelector('[data-pct-label]')

                if (!pill) {
                    continue
                }

                const rect = el.getBoundingClientRect()
                const wPct = (rect.width / rootRect.width) * 100
                const hPct = (rect.height / rootRect.height) * 100

                pill.textContent = this.fmtPill(
                    Math.max(0, Math.min(100, wPct)),
                    Math.max(0, Math.min(100, hPct)),
                )
            }
        },

        leafOrder(node, map = new Map(), counter = { n: 0 }) {
            if ((node.children ?? []).length === 0) {
                map.set(node.id, ++counter.n)
            } else {
                for (const child of node.children) {
                    this.leafOrder(child, map, counter)
                }
            }

            return map
        },

        selectNode(nodeId) {
            this.selectedId = nodeId
            this.renderSelection()
            this._dispatchCustomizePayload()
        },

        clearSelection() {
            if (this.isDragging) {
                return
            }

            this.selectedId = null
            this.renderSelection()
            this._dispatchCustomizePayload()
        },

        renderStructure() {
            const container = this.$refs.gridContainer

            if (!container || !this.grid) {
                return
            }

            this._cleanupAllCustomJs()

            this._initComponentIndexes()
            this.leafOrderMap = this.leafOrder(this.grid)
            this.elementsById = new Map()

            container.innerHTML = ''

            const rootEl = this.buildEl(
                this.grid,
                100,
                100,
                this.leafOrderMap,
                { topLeft: true, topRight: true, bottomRight: true, bottomLeft: true },
            )
            rootEl.style.position = 'absolute'
            rootEl.style.inset = '0'
            container.appendChild(rootEl)

            this._applyCustomCode()
            this.updateAllPills()
            this.renderSelection()
        },

        _applyCustomCode() {
            for (const [nodeId, el] of this.elementsById) {
                const node = this.nodesById.get(nodeId)

                if (!node || (node.children ?? []).length > 0) {
                    continue
                }

                this._applyCustomCss(node, el)
                this._applyPreviewCssToElement(node, el)
                this._runNodeCustomJs(node, el)
                this._syncNodeCodeBadge(node, el)
            }
        },

        _syncNodeCodeBadge(node, el) {
            const center = el.querySelector('[data-node-center]')
            if (!center) {
                return
            }

            // Cleanup any legacy badge nodes that were previously mounted
            // directly on the leaf element.
            for (const child of Array.from(el.children)) {
                if (child.classList?.contains('lb-node-code-badge')) {
                    child.remove()
                }
            }

            const hasCustomCode = this._nodeHasCustomCode(node)

            center.classList.toggle('lb-node-center--with-code', hasCustomCode)

            let badge = center.querySelector('.lb-node-code-indicator')

            if (!hasCustomCode) {
                if (badge) {
                    badge.remove()
                }

                return
            }

            if (!badge) {
                badge = this._createCodeIndicatorElement()
            }

            // Keep code indicator as the last center child so it renders beneath
            // the primary icon/number/label stack.
            center.appendChild(badge)
        },

        _applyCustomCss(node, el) {
            const css = this._normalizeCustomCode(node.customCss)

            if (!css) {
                el.removeAttribute('style')

                return
            }

            el.setAttribute('style', css)
        },

        _runNodeCustomJs(node, el) {
            const js = this._normalizeCustomCode(node.customJs)

            if (!js) {
                return
            }

            try {
                const runner = new Function('el', 'node', 'builder', js)
                const cleanup = runner(el, node, this)

                if (typeof cleanup === 'function') {
                    this.customJsCleanups.set(node.id, cleanup)
                }
            } catch (error) {
                console.error(`Custom JS error for node ${node.id}`, error)
            }
        },

        _applyPreviewCssToElement(node, el) {
            if (this.previewNodeId !== node.id) {
                return
            }

            if (!this.previewCss) {
                this._applyCustomCss(node, el)

                return
            }

            el.setAttribute('style', this.previewCss)
        },

        _cleanupAllCustomJs() {
            for (const cleanup of this.customJsCleanups.values()) {
                if (typeof cleanup !== 'function') {
                    continue
                }

                try {
                    cleanup()
                } catch (error) {
                    console.error('Custom JS cleanup failed', error)
                }
            }

            this.customJsCleanups.clear()
        },

        renderSelection() {
            for (const [nodeId, el] of this.elementsById) {
                el.classList.toggle('is-selected', nodeId === this.selectedId)
            }
        },

        renderNodeContent(nodeId) {
            const node = this.nodesById.get(nodeId)
            const el = this.elementsById.get(nodeId)

            if (!node || !el || (node.children ?? []).length > 0) {
                return
            }

            this._renderLeafCenter(el, node, this.leafOrderMap)
            this.renderSelection()
        },

        _renderLeafCenter(el, node, order = this.leafOrderMap) {
            let center = el.querySelector('[data-node-center]')

            if (!center) {
                center = document.createElement('div')
                center.dataset.nodeCenter = ''
                el.appendChild(center)
            }

            center.innerHTML = ''
            center.className = 'lb-node-center'

            const componentIcon = this.getComponentIcon(node.component)

            if (componentIcon) {
                center.innerHTML = `<svg viewBox="0 0 20 20" fill="none" class="lb-node-icon">${componentIcon}</svg>`

                if (this.isCustomComponent(node.component)) {
                    const label = this.getCustomComponent(node.component)?.title ?? 'Custom'
                    center.classList.add('lb-node-center--labeled')

                    const labelEl = document.createElement('div')
                    labelEl.className = 'lb-node-label'
                    labelEl.textContent = label
                    center.appendChild(labelEl)
                }

                if (this._nodeHasCustomCode(node)) {
                    center.classList.add('lb-node-center--with-code')
                    center.appendChild(this._createCodeIndicatorElement())
                }

                return
            }

            center.classList.add('lb-node-number')
            center.textContent = order.get(node.id) ?? ''

            if (this._nodeHasCustomCode(node)) {
                center.classList.add('lb-node-center--with-code')
                center.appendChild(this._createCodeIndicatorElement())
            }
        },

        buildEl(
            node,
            wPct = 100,
            hPct = 100,
            order = this.leafOrderMap,
            corners = { topLeft: true, topRight: true, bottomRight: true, bottomLeft: true },
        ) {
            const el = document.createElement('div')
            el.dataset.nodeId = node.id
            el.classList.add('lb-node')
            this.elementsById.set(node.id, el)

            if ((node.children ?? []).length > 0) {
                const isHorizontal = node.direction === 'h'
                const split = node.split ?? 50

                const child1Corners = isHorizontal
                    ? {
                          topLeft: corners.topLeft,
                          topRight: corners.topRight,
                          bottomRight: false,
                          bottomLeft: false,
                      }
                    : {
                          topLeft: corners.topLeft,
                          topRight: false,
                          bottomRight: false,
                          bottomLeft: corners.bottomLeft,
                      }
                const child2Corners = isHorizontal
                    ? {
                          topLeft: false,
                          topRight: false,
                          bottomRight: corners.bottomRight,
                          bottomLeft: corners.bottomLeft,
                      }
                    : {
                          topLeft: false,
                          topRight: corners.topRight,
                          bottomRight: corners.bottomRight,
                          bottomLeft: false,
                      }

                el.classList.add(isHorizontal ? 'lb-node--split-h' : 'lb-node--split-v')

                const firstWrapper = document.createElement('div')
                firstWrapper.className = isHorizontal
                    ? 'lb-node-child--first-h'
                    : 'lb-node-child--first-v'
                firstWrapper.style.flex = `0 0 ${split}%`

                const handle = document.createElement('div')
                handle.className = `lb-handle lb-handle--${isHorizontal ? 'h' : 'v'}`

                const line = document.createElement('div')
                line.className = `lb-handle-line lb-handle-line--${isHorizontal ? 'h' : 'v'}`
                handle.appendChild(line)

                handle.addEventListener('click', (event) => event.stopPropagation())

                handle.addEventListener('mousedown', (event) => {
                    event.stopPropagation()
                    event.preventDefault()
                    handle.classList.add('is-dragging')
                    this.isDragging = true
                    this.$refs.gridContainer?.classList.add('is-dragging')

                    const rect = el.getBoundingClientRect()
                    const onMove = (moveEvent) => {
                        const raw = isHorizontal
                            ? ((moveEvent.clientY - rect.top) / rect.height) * 100
                            : ((moveEvent.clientX - rect.left) / rect.width) * 100
                        const clamped = Math.max(5, Math.min(95, Math.round(raw / 5) * 5))

                        firstWrapper.style.flex = `0 0 ${clamped}%`
                        node.split = clamped

                        const labelOne = firstWrapper.querySelector('[data-pct-label]')
                        const labelTwo = secondWrapper.querySelector('[data-pct-label]')

                        if (labelOne) {
                            labelOne.textContent = this.fmtPill(
                                isHorizontal ? wPct : (wPct * clamped) / 100,
                                isHorizontal ? (hPct * clamped) / 100 : hPct,
                            )
                        }

                        if (labelTwo) {
                            labelTwo.textContent = this.fmtPill(
                                isHorizontal ? wPct : (wPct * (100 - clamped)) / 100,
                                isHorizontal ? (hPct * (100 - clamped)) / 100 : hPct,
                            )
                        }
                    }

                    const onUp = () => {
                        handle.classList.remove('is-dragging')
                        this.isDragging = false
                        this.$refs.gridContainer?.classList.remove('is-dragging')
                        this.updateAllPills()
                        this._save()
                        document.removeEventListener('mousemove', onMove)
                        document.removeEventListener('mouseup', onUp)
                    }

                    document.addEventListener('mousemove', onMove)
                    document.addEventListener('mouseup', onUp)
                })

                const secondWrapper = document.createElement('div')
                secondWrapper.className = isHorizontal
                    ? 'lb-node-child--second-h'
                    : 'lb-node-child--second-v'

                firstWrapper.appendChild(
                    this.buildEl(
                        node.children[0],
                        isHorizontal ? wPct : (wPct * split) / 100,
                        isHorizontal ? (hPct * split) / 100 : hPct,
                        order,
                        child1Corners,
                    ),
                )
                secondWrapper.appendChild(
                    this.buildEl(
                        node.children[1],
                        isHorizontal ? wPct : (wPct * (100 - split)) / 100,
                        isHorizontal ? (hPct * (100 - split)) / 100 : hPct,
                        order,
                        child2Corners,
                    ),
                )

                el.appendChild(firstWrapper)
                el.appendChild(handle)
                el.appendChild(secondWrapper)
            } else {
                el.classList.add('lb-node--leaf')
                el.classList.toggle('lb-node--tl', corners.topLeft)
                el.classList.toggle('lb-node--tr', corners.topRight)
                el.classList.toggle('lb-node--br', corners.bottomRight)
                el.classList.toggle('lb-node--bl', corners.bottomLeft)

                this._renderLeafCenter(el, node, order)

                const pill = document.createElement('div')
                pill.dataset.pctLabel = ''
                pill.className = 'lb-node-pill'
                pill.textContent = this.fmtPill(wPct, hPct)
                el.appendChild(pill)
            }

            el.addEventListener('click', (event) => {
                event.stopPropagation()
                this.selectNode(node.id)
            })

            return el
        },
    }
}
