<template>
    <div>
        <div ref="editor"><div></div></div>
        <modal ref="math-help-modal" title="Math/LaTeX help" ok-only>
            <p>To enter math in LaTeX form, surround it with <code>$$latex</code> and <code>$$</code> on separate lines.</p>
            <h4>Example</h4>
            <pre style="border:solid 1px black; padding: 10px; margin:10px;" ref="latex-example">
$$latex
e = mc^2
$$</pre>
            <p>A <a href="https://katex.org/docs/supported.html" target="_blank">list of supported LaTeX functions is available.</a></p>
        </modal>
    </div>
</template>

<script>
import Prism from 'prismjs';
import 'prismjs/themes/prism.css';
import codeSyntaxHighlight from '@toast-ui/editor-plugin-code-syntax-highlight/dist/toastui-editor-plugin-code-syntax-highlight-all.js';

import Editor from '@toast-ui/editor';
import '@toast-ui/editor/dist/toastui-editor.css';

import katex from 'katex/dist/katex';

import Modal from './Modal.vue';

// export
export default {
    components: { Modal },
    props: {
        value: {
            type: String,
            default: "",
        }
    },
    data() {
        return {
            current_value: "",
            editor: null,
        }
    },
    mounted() {
        let comp = this;
        function createMathButton() {
            const button = document.createElement('button');
            button.className = 'toastui-editor-toolbar-icons';
            button.style.backgroundImage = 'none';
            button.style.margin = '0';
            button.innerHTML = `<i>f(x)</i>`;
            button.addEventListener('click', () => {
                comp.$refs['math-help-modal'].show();
            });
            return button;
        }
        this.editor = new Editor({
            el: this.$refs.editor.querySelector('div'),
            height: '300px',
            initialValue: this.value,
            initialEditType: 'markdown',
            hideModeSwitch: true,
            previewStyle: 'tab',
            usageStatistics: false,
            plugins: [[codeSyntaxHighlight, { highlighter: Prism }]],
            toolbarItems: [
                ['heading', 'bold', 'italic'],
                ['ul', 'ol'],
                ['table', 'image', 'link'],
                ['codeblock'],
                [{
                    el: createMathButton(),
                    command: 'math',
                    tooltip: 'Math (as LaTeX)',
                }],
            ],
            hooks: {
                addImageBlobHook: (blob, callback) => {
                    const url = '/api/course/' + comp.$store.getters.user.course_id + '/upload_file'
                    const payload = new FormData();
                    payload.append('image', blob);
                    axios.post(url, payload).then(resp=>{
                        callback(resp.data.url,'image')
                    });
                }
            },
            customHTMLRenderer: {
                latex(node) {
                    let html = katex.renderToString(node.literal, {
                        throwOnError: false
                    });

                    return [
                        { type: 'openTag', tagName: 'div', outerNewLine: true },
                        { type: 'html', content: html },
                        { type: 'closeTag', tagName: 'div', outerNewLine: true }
                    ]
                }
            },
        });
    },
    destroyed() {
        this.editor.destroy();
    },
    methods: {
        hasContents() {
            return this.editor.getMarkdown().trim().length !== 0;
        },
        getContents() {
            return this.editor.getMarkdown();
        }
    }
}
</script>
