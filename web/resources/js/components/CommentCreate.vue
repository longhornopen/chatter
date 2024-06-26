<script>
import WysiwygEditor from './WysiwygEditor.vue'
import Modal from './Modal.vue'

import { useMainStore } from '@/store'

export default {
    setup() {
        const store = useMainStore()
        return { store }
    },
    components: { WysiwygEditor, Modal },
    props: ['parent_comment_id', 'post_id'],
    data() {
        return {
            anonymous: false,
            comment_body: '',
            save_pending: false,
        };
    },
    methods: {
        cancel() {
            this.$refs['abandon_edit'].show();
        },
        handle_cancel_ok() {
            this.$emit('close_comment_editor')
        },
        async submit() {
            if (!this.$refs['commentEditor'].hasContents()) {
                this.$refs['missing_comment'].show();
                return;
            }
            this.comment_body = this.$refs['commentEditor'].getContents()
            this.$refs['commentEditor'].$el.scrollIntoView();
            this.save_pending = true;
            await this.store.addComment( {
                post_id: this.post_id,
                parent_comment_id: this.parent_comment_id,
                author_anonymous: this.anonymous,
                body: this.comment_body,
            });
            this.save_pending = false;
            this.$emit('close_comment_editor')
        }
    }
}
</script>

<template>
    <div>
        <modal ref="missing_comment" title="Missing Comment" :ok-only="true"
                 header-bg-variant="warning"
                 header-text-variant="light"
        >
            <template v-slot:body>
            <p>It looks like you forgot to write your comment.</p>
            </template>
        </modal>
        <modal ref="abandon_edit" title="Abandon Edit?"
                 header-bg-variant="warning"
                 header-text-variant="light"
        >
            <template v-slot:body>
            <p>Are you sure you want to abandon your edit without saving?</p>
            </template>
            <template v-slot:footer>
                <button class="btn btn-primary" @click="handle_cancel_ok()">Ok</button>
            </template>
        </modal>
        <fieldset v-bind:disabled="save_pending">
            <wysiwyg-editor v-model="comment_body" ref="commentEditor"></wysiwyg-editor>
            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" id="postAnonymously" v-model="anonymous">
                <label class="form-check-label" for="postAnonymously">Post Anonymously</label>
            </div>
            <div class="editor-btn-group">
                <button class="btn btn-tertiary" @click="cancel()">Cancel</button>
                <button class="btn btn-primary" @click="submit"><font-awesome-icon v-if="save_pending" icon="spinner" spin /> Submit</button>
            </div>
        </fieldset>
    </div>

</template>

<style lang="scss" scoped>
@import '../../sass/_variables.scss';

</style>
