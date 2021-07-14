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
        let comp = this;
        this.editor = new Editor({
            el: this.$refs.editor.querySelector('div'),
            height: '300px',
            initialValue: this.value,
            initialEditType: 'wysiwyg',
            previewStyle: 'vertical',
            usageStatistics: false,
            plugins: [[codeSyntaxHighlight, { highlighter: Prism }]],
            toolbarItems: [
                ['heading', 'bold', 'italic'],
                ['ul', 'ol'],
                ['table', 'image', 'link'],
                ['codeblock'],
            ],
            events: {
                change() {
                    if (comp.editor === null) {
                        return;
                    }

                    let html = comp.editor.getHTML();
                    comp.$emit('input', html);
                }
            }
        });
    },
    methods: {
    }
}
</script>
