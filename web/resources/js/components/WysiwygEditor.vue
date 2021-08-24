<template>
    <div ref="editor">
        <div></div>
    </div>
</template>

<script>
import Prism from 'prismjs';
import 'prismjs/themes/prism.css';
import codeSyntaxHighlight from '@toast-ui/editor-plugin-code-syntax-highlight/dist/toastui-editor-plugin-code-syntax-highlight-all.js';

import Editor from '@toast-ui/editor';
import '@toast-ui/editor/dist/toastui-editor.css';

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
            editor: null,
        }
    },
    mounted() {
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
            ],
        });
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
