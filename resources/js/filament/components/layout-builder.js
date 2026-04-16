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
        themeObserver: null,

        init() {
            this._initGrid()
            this.observeTheme()

            this.$watch('state', (value) => {
                const normalized = this._normalizeGrid(value)

                if (JSON.stringify(normalized) !== JSON.stringify(this.grid)) {
                    this.grid = normalized
                    this.nodeCounter = this._maxNodeId(this.grid)
                    this.$nextTick(() => this.render())
                }
            })

            this.$watch('orientation', () => {
                this.$nextTick(() => this.render())
            })

            this.$nextTick(() => this.render())
        },

        observeTheme() {
            this.themeObserver?.disconnect()

            this.themeObserver = new MutationObserver(() => {
                this.$nextTick(() => this.render())
            })

            this.themeObserver.observe(document.documentElement, {
                attributes: true,
                attributeFilter: ['class', 'style'],
            })
        },

        stageStyle() {
            if (this.standalone) {
                if (this.orientation === 'portrait') {
                    return 'aspect-ratio: 9 / 16; height: 100%; width: auto;'
                }

                return 'aspect-ratio: 16 / 9; width: 100%; max-height: 100%;'
            }

            if (this.orientation === 'portrait') {
                return 'aspect-ratio: 540 / 960; max-width: 360px; max-height: 520px;'
            }

            return 'aspect-ratio: 960 / 540; max-width: 100%; max-height: 520px;'
        },

        _clone(value) {
            return JSON.parse(JSON.stringify(value))
        },

        _initGrid() {
            this.grid = this._normalizeGrid(this.state)
            this.nodeCounter = this._maxNodeId(this.grid)
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
            }

            return normalized
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
            if (this.baseComponents.some((component) => component.key === key)) {
                return 'base'
            }

            if (/^custom:\d+$/.test(key)) {
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
            }
        },

        _save() {
            const next = this._clone(this.grid)
            next.version = this.emptyGrid.version ?? 1
            this.state = next
        },

        findNode(node, id) {
            if (node.id === id) {
                return node
            }

            for (const child of node.children ?? []) {
                const hit = this.findNode(child, id)

                if (hit) {
                    return hit
                }
            }

            return null
        },

        findParent(node, id) {
            for (const child of node.children ?? []) {
                if (child.id === id) {
                    return node
                }

                const hit = this.findParent(child, id)

                if (hit) {
                    return hit
                }
            }

            return null
        },

        slice(nodeId, direction) {
            const node = this.findNode(this.grid, nodeId)

            if (!node || (node.children ?? []).length > 0) {
                return
            }

            const inheritedComponent = node.component
            const inheritedComponentType = node.componentType
            const inheritedComponentConfig = this._clone(node.componentConfig ?? {})

            node.direction = direction
            node.split = 50
            node.component = null
            node.componentType = null
            node.componentConfig = {}
            node.children = [this._makeNode(), this._makeNode()]
            node.children[0].component = inheritedComponent
            node.children[0].componentType = inheritedComponentType
            node.children[0].componentConfig = inheritedComponentConfig
            this.selectedId = node.children[0].id

            this._save()
            this.render()
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
                parent.children = survivor.children
            }

            this.selectedId = null
            this._save()
            this.render()
        },

        clearCanvas() {
            this.nodeCounter = 0
            this.generatedNodeIndex = 0
            this.grid = this._emptyGrid()
            this.selectedId = null
            this._save()
            this.render()
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
            this.render()
        },

        isCustomComponent(type) {
            return typeof type === 'string' && type.startsWith('custom:')
        },

        getCustomComponent(type) {
            if (!this.isCustomComponent(type)) {
                return null
            }

            return this.customComponents.find((component) => component.key === type) ?? null
        },

        getComponentIcon(type) {
            if (!type) {
                return null
            }

            const baseComponent = this.baseComponents.find((component) => component.key === type)

            if (baseComponent) {
                return baseComponent.icon
            }

            return this.getCustomComponent(type)?.icon ?? null
        },

        selectedNode() {
            if (!this.selectedId || !this.grid) {
                return null
            }

            return this.findNode(this.grid, this.selectedId)
        },

        selectedComponentKey() {
            return this.selectedNode()?.component ?? null
        },

        themeColors() {
            const styles = getComputedStyle(this.$root)
            const read = (name, fallback) =>
                styles.getPropertyValue(name).trim() || fallback

            return {
                dashIdle: read('--lb-accent-border', 'rgba(245, 158, 11, 0.3)'),
                dashHover: read('--lb-accent-outline', 'rgba(245, 158, 11, 0.7)'),
                dashActive: read('--lb-accent', 'rgba(245, 158, 11, 1)'),
                leafBg: read('--lb-node-bg', 'rgba(17, 17, 20, 0.55)'),
                leafHoverBg: read('--lb-node-hover-bg', 'rgba(245, 158, 11, 0.1)'),
                iconColor: read('--lb-node-icon', 'rgba(245, 158, 11, 0.35)'),
                iconColorSoft: read('--lb-node-icon-soft', 'rgba(245, 158, 11, 0.5)'),
                customLabelColor: read('--lb-node-label', 'rgba(245, 158, 11, 0.72)'),
                pillText: read('--lb-pill-text', 'rgba(161, 161, 170, 0.7)'),
                pillBg: read('--lb-pill-bg', 'rgba(17, 17, 20, 0.7)'),
                pillBorder: read('--lb-pill-border', 'rgba(161, 161, 170, 0.12)'),
                selection: read('--lb-accent-outline', 'rgba(245, 158, 11, 0.75)'),
            }
        },

        fmtPill(wPct, hPct) {
            const real = this.realSizes[this.orientation] ?? this.realSizes.landscape

            return `${Math.round((real.width * wPct) / 100)}x${Math.round((real.height * hPct) / 100)}px`
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

        applyCornerRadius(el, corners) {
            el.style.borderTopLeftRadius = corners.topLeft ? this.canvasCornerRadius : '0'
            el.style.borderTopRightRadius = corners.topRight ? this.canvasCornerRadius : '0'
            el.style.borderBottomRightRadius = corners.bottomRight
                ? this.canvasCornerRadius
                : '0'
            el.style.borderBottomLeftRadius = corners.bottomLeft
                ? this.canvasCornerRadius
                : '0'
        },

        render() {
            const container = this.$refs.gridContainer

            if (!container || !this.grid) {
                return
            }

            container.innerHTML = ''

            const rootEl = this.buildEl(
                this.grid,
                100,
                100,
                this.leafOrder(this.grid),
                { topLeft: true, topRight: true, bottomRight: true, bottomLeft: true },
                this.themeColors(),
            )
            rootEl.style.position = 'absolute'
            rootEl.style.inset = '0'
            container.appendChild(rootEl)

            const selectedNode = this.selectedId ? this.findNode(this.grid, this.selectedId) : null
            const hasSelection = !!this.selectedId
            const overlay = this.$refs.canvasOverlay

            if (overlay) {
                overlay.classList.toggle('is-hidden', !hasSelection)

                if (hasSelection) {
                    const isLeaf = selectedNode && (selectedNode.children ?? []).length === 0
                    this.$refs.btnSliceH?.classList.toggle('is-hidden', !isLeaf)
                    this.$refs.btnSliceV?.classList.toggle('is-hidden', !isLeaf)
                    this.$refs.btnDelete?.classList.toggle('is-hidden', this.selectedId === 'root')
                }
            }
        },

        buildEl(
            node,
            wPct = 100,
            hPct = 100,
            order = new Map(),
            corners = { topLeft: true, topRight: true, bottomRight: true, bottomLeft: true },
            theme = this.themeColors(),
        ) {
            const el = document.createElement('div')
            el.dataset.nodeId = node.id
            el.style.cssText =
                'width:100%;height:100%;position:relative;overflow:hidden;box-sizing:border-box;'

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

                el.style.display = 'flex'
                el.style.flexDirection = isHorizontal ? 'column' : 'row'

                const firstWrapper = document.createElement('div')
                firstWrapper.style.cssText = isHorizontal
                    ? `flex:0 0 ${split}%;min-height:0;position:relative;overflow:hidden;`
                    : `flex:0 0 ${split}%;min-width:0;position:relative;overflow:hidden;`

                const dashedBg = (color, direction) =>
                    direction === 'h'
                        ? `repeating-linear-gradient(to right,${color} 0,${color} 4px,transparent 4px,transparent 8px)`
                        : `repeating-linear-gradient(to bottom,${color} 0,${color} 4px,transparent 4px,transparent 8px)`

                const handle = document.createElement('div')
                handle.style.cssText = isHorizontal
                    ? 'box-sizing:content-box;flex:0 0 1px;padding:7px 0;margin:-7px 0;cursor:row-resize;position:relative;z-index:10;display:flex;align-items:center;'
                    : 'box-sizing:content-box;flex:0 0 1px;padding:0 7px;margin:0 -7px;cursor:col-resize;position:relative;z-index:10;display:flex;justify-content:center;'

                const line = document.createElement('div')
                line.style.cssText = isHorizontal
                    ? 'width:100%;height:1px;transition:background 0.15s;'
                    : 'height:100%;width:1px;transition:background 0.15s;'
                line.style.background = dashedBg(theme.dashIdle, node.direction)
                handle.appendChild(line)

                handle.addEventListener('mouseenter', () => {
                    line.style.background = dashedBg(theme.dashHover, node.direction)
                })
                handle.addEventListener('mouseleave', () => {
                    if (!handle._drag) {
                        line.style.background = dashedBg(theme.dashIdle, node.direction)
                    }
                })
                handle.addEventListener('click', (event) => event.stopPropagation())

                handle.addEventListener('mousedown', (event) => {
                    event.stopPropagation()
                    event.preventDefault()
                    handle._drag = true
                    this.isDragging = true
                    line.style.background = dashedBg(theme.dashActive, node.direction)

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
                        handle._drag = false
                        this.isDragging = false
                        line.style.background = dashedBg(theme.dashIdle, node.direction)
                        this._save()
                        document.removeEventListener('mousemove', onMove)
                        document.removeEventListener('mouseup', onUp)
                    }

                    document.addEventListener('mousemove', onMove)
                    document.addEventListener('mouseup', onUp)
                })

                const secondWrapper = document.createElement('div')
                secondWrapper.style.cssText = isHorizontal
                    ? 'flex:1;min-height:0;position:relative;overflow:hidden;'
                    : 'flex:1;min-width:0;position:relative;overflow:hidden;'

                firstWrapper.appendChild(
                    this.buildEl(
                        node.children[0],
                        isHorizontal ? wPct : (wPct * split) / 100,
                        isHorizontal ? (hPct * split) / 100 : hPct,
                        order,
                        child1Corners,
                        theme,
                    ),
                )
                secondWrapper.appendChild(
                    this.buildEl(
                        node.children[1],
                        isHorizontal ? wPct : (wPct * (100 - split)) / 100,
                        isHorizontal ? (hPct * (100 - split)) / 100 : hPct,
                        order,
                        child2Corners,
                        theme,
                    ),
                )

                el.appendChild(firstWrapper)
                el.appendChild(handle)
                el.appendChild(secondWrapper)
            } else {
                this.applyCornerRadius(el, corners)
                el.style.backgroundColor = theme.leafBg
                el.style.cursor = 'pointer'
                el.style.transition = 'background-color 0.15s'

                const center = document.createElement('div')
                center.style.cssText =
                    'position:absolute;inset:0;display:flex;align-items:center;justify-content:center;pointer-events:none;z-index:4;user-select:none;'

                const componentIcon = this.getComponentIcon(node.component)

                if (componentIcon) {
                    center.innerHTML = `<svg viewBox="0 0 20 20" fill="none" style="width:56px;height:56px;color:${theme.iconColorSoft}">${componentIcon}</svg>`

                    if (this.isCustomComponent(node.component)) {
                        const label = this.getCustomComponent(node.component)?.title ?? 'Custom'
                        center.style.flexDirection = 'column'
                        center.style.gap = '8px'

                        const labelEl = document.createElement('div')
                        labelEl.style.cssText = `max-width:80%;font-size:12px;font-weight:600;line-height:1.2;color:${theme.customLabelColor};text-align:center;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;`
                        labelEl.textContent = label
                        center.appendChild(labelEl)
                    }
                } else {
                    center.style.fontSize = '48px'
                    center.style.fontWeight = '700'
                    center.style.color = theme.iconColor
                    center.style.fontFamily = 'monospace'
                    center.textContent = order.get(node.id) ?? ''
                }

                el.appendChild(center)

                const pill = document.createElement('div')
                pill.dataset.pctLabel = ''
                pill.textContent = this.fmtPill(wPct, hPct)
                pill.style.cssText = `position:absolute;bottom:5px;left:6px;font-size:10px;line-height:1;color:${theme.pillText};background:${theme.pillBg};border:1px solid ${theme.pillBorder};border-radius:999px;padding:3px 8px;pointer-events:none;z-index:5;user-select:none;font-family:monospace;white-space:nowrap;`
                el.appendChild(pill)

                el.addEventListener('mouseenter', () => {
                    if (!this.isDragging && node.id !== this.selectedId) {
                        el.style.backgroundColor = theme.leafHoverBg
                    }
                })
                el.addEventListener('mouseleave', () => {
                    if (!this.isDragging && node.id !== this.selectedId) {
                        el.style.backgroundColor = theme.leafBg
                    }
                })
            }

            if (node.id === this.selectedId) {
                el.style.outline = `2px solid ${theme.selection}`
                el.style.outlineOffset = '-2px'
            }

            el.addEventListener('click', (event) => {
                event.stopPropagation()
                this.selectedId = node.id
                this.render()
            })

            return el
        },
    }
}
