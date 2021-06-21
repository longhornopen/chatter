<template>
    <editor
        :initialValue="value"
        height="300px"
        initialEditType="wysiwyg"
        previewStyle="vertical"
        :options="editorOptions"
        ref="toastEditor"
        @change="onEditorChange"
    />
</template>

<script>
import '@toast-ui/editor/dist/toastui-editor.css';
import 'prismjs/themes/prism.css';
import { Editor } from '@toast-ui/vue-editor';
import codeSyntaxHighlight from '@toast-ui/editor-plugin-code-syntax-highlight/dist/toastui-editor-plugin-code-syntax-highlight-all.js';
import Prism from 'prismjs';

// export
export default {
    props: {
        value: {
            type: String,
            default: "",
        }
    },
    components: {
        editor: Editor,
    },
    data() {
        return {
            editorOptions: {
                usageStatistics: false,
                plugins: [[codeSyntaxHighlight, { highlighter: Prism }]],
                toolbarItems: [
                    ['heading', 'bold', 'italic'],
                    ['ul', 'ol'],
                    ['table', 'image', 'link'],
                    ['codeblock'],
                ],
            }
        }
    },
    methods: {
        // FIXME: "this.editor is null" when editor opened - upstream bug in toastUI?
        onEditorChange() {
            console.log(this.$refs.toastEditor);
            let html = this.$refs.toastEditor.invoke('getHtml')
            console.log(html);
            this.$emit('input', html)
        },
    }
}
</script>
