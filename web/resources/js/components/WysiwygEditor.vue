<template>
    <div class="quill-editor" ref="editor_container">
        <slot name="toolbar"></slot>
        <div ref="editor"></div>
    </div>
</template>

<script>
import _Quill from 'quill'
import 'quill/dist/quill.core.css'
import 'quill/dist/quill.snow.css'

import QuillMarkdown from 'quilljs-markdown'
import 'quilljs-markdown/dist/quilljs-markdown-common-style.css'

const Quill = window.Quill || _Quill
const defaultOptions = {
    theme: 'snow',
    bounds: '.quill-editor',
    modules: {
        toolbar: [
            ['bold', 'italic', 'underline', 'strike'],
            ['code-block'],
            [{ 'list': 'ordered' }, { 'list': 'bullet' }],
            [{ 'script': 'sub' }, { 'script': 'super' }],
            //[{ 'indent': '-1' }, { 'indent': '+1' }],
            //[{ 'direction': 'rtl' }],
            //[{ 'size': ['small', false, 'large', 'huge'] }],
            [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
            [{ 'color': [] }, { 'background': [] }],
            //[{ 'font': [] }],
            //[{ 'align': [] }],
            //['clean'],
            ['formula', 'link'], //['formula', 'link', 'image']
            // FIXME: use something like https://github.com/benwinding/quill-image-compress if you let people upload images
        ]
    },
    placeholder: 'Insert text here ...',
    readOnly: false
}

// pollfill
if (typeof Object.assign != 'function') {
    Object.defineProperty(Object, 'assign', {
        value (target, varArgs) {
            if (target == null) {
                throw new TypeError('Cannot convert undefined or null to object')
            }
            const to = Object(target)
            for (let index = 1; index < arguments.length; index++) {
                const nextSource = arguments[index]
                if (nextSource != null) {
                    for (const nextKey in nextSource) {
                        if (Object.prototype.hasOwnProperty.call(nextSource, nextKey)) {
                            to[nextKey] = nextSource[nextKey]
                        }
                    }
                }
            }
            return to
        },
        writable: true,
        configurable: true
    })
}

// export
export default {
    name: 'quill-editor',
    data () {
        return {
            _options: {},
            _content: '',
            defaultOptions
        }
    },
    props: {
        content: String,
        value: String,
        disabled: {
            type: Boolean,
            default: false
        },
        options: {
            type: Object,
            required: false,
            default: () => ({})
        },
        globalOptions: {
            type: Object,
            required: false,
            default: () => ({})
        }
    },
    mounted () {
        this.initialize()
    },
    beforeDestroy () {
        this.quill = null
        delete this.quill
    },
    methods: {
        // Init Quill instance
        initialize () {
            if (this.$el) {

                // Options
                this._options = Object.assign({}, this.defaultOptions, this.globalOptions, this.options)

                // Instance
                this.quill = new Quill(this.$refs.editor, this._options)

                this.quill.enable(false)

                // Set editor content
                if (this.value || this.content) {
                    this.quill.pasteHTML(this.value || this.content)
                }

                // Disabled editor
                if (!this.disabled) {
                    this.quill.enable(true)
                }

                // Mark model as touched if editor lost focus
                this.quill.on('selection-change', range => {
                    if (!range) {
                        this.$emit('blur', this.quill)
                    } else {
                        this.$emit('focus', this.quill)
                    }
                })

                // Update model if text changes
                this.quill.on('text-change', (delta, oldDelta, source) => {
                    let html = this.$refs.editor.children[0].innerHTML
                    const quill = this.quill
                    const text = this.quill.getText()
                    if (html === '<p><br></p>') html = ''
                    this._content = html
                    this.$emit('input', this._content)
                    this.$emit('change', { html, text, quill })
                })

                new QuillMarkdown(this.quill, {});

                // Emit ready event
                this.$emit('ready', this.quill)
            }
        }
    },
    watch: {
        // Watch content change
        content (newVal, oldVal) {
            if (this.quill) {
                if (newVal && newVal !== this._content) {
                    this._content = newVal
                    this.quill.pasteHTML(newVal)
                } else if (!newVal) {
                    this.quill.setText('')
                }
            }
        },
        // Watch content change
        value (newVal, oldVal) {
            if (this.quill) {
                if (newVal && newVal !== this._content) {
                    this._content = newVal
                    this.quill.pasteHTML(newVal)
                } else if (!newVal) {
                    this.quill.setText('')
                }
            }
        },
        // Watch disabled change
        disabled (newVal, oldVal) {
            if (this.quill) {
                this.quill.enable(!newVal)
            }
        }
    }
}
</script>
