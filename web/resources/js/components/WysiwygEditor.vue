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
        // FIXME latex: https://github.com/nhn/tui.editor/issues/1600
        return {
            current_value: "",
            editor: null,
            // As of ToastUI Editor 3.0.0, getHTML() causes the `change` handler to be fired...
            //    https://github.com/nhn/tui.editor/issues/1587
            // ...which leads to an infinite loop for us.  getMarkdown() does not; use it as a
            // to refuse to get into an infinite loop calling getHTML() if contents have not changed.
            old_markdown: "",
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

                    // see 'oldMarkdown' comment above
                    let newMarkdown = comp.editor.getMarkdown();
                    if (newMarkdown === comp.oldMarkdown) {
                        return;
                    }
                    comp.oldMarkdown = newMarkdown;

                    //console.log('editor' in comp);
                    //if (! 'editor' in comp || comp) {
                    //    return;
                    //}

                    //console.log(comp);
                    //let html = comp.editor.getHTML();
                    //console.log(html);
                    //comp.$emit('input', html);
                }
            }
        });
    },
    methods: {
    }
}
</script>
