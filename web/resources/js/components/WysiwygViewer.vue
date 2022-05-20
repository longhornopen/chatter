<template>
    <div ref="viewer">
        <div></div>
    </div>
</template>

<script>
import Prism from 'prismjs';
import 'prismjs/themes/prism.css';
import codeSyntaxHighlight from '@toast-ui/editor-plugin-code-syntax-highlight/dist/toastui-editor-plugin-code-syntax-highlight-all.js';

import Viewer from '@toast-ui/editor/viewer';
import '@toast-ui/editor/dist/toastui-editor-viewer.css';

import katex from 'katex/dist/katex';

// export
export default {
    props: {
        value: {
            type: String,
            default: "",
        }
    },
    data() {
        return {
            current_value: "",
            viewer: null,
        }
    },
    mounted() {
        this.viewer = new Viewer({
            el: this.$refs.viewer.querySelector('div'),
            initialValue: this.value,
            usageStatistics: false,
            plugins: [[codeSyntaxHighlight, { highlighter: Prism }]],
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
        this.viewer.destroy();
    },
    methods: {

    }
}
</script>
