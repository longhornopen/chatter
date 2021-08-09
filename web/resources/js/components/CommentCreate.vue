<script>
import WysiwygEditor from './WysiwygEditor'

export default {
    components: { WysiwygEditor },
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
            this.$swal.fire({
                title: "Are you sure you want to abandon this comment without saving it?",
                icon: 'warning',
                showCancelButton: true,
            }).then(result => {
                if (result.isConfirmed) {
                    this.$emit('close_comment_editor')
                }
            });
        },
        async submit() {
            if (!this.$refs['commentEditor'].hasContents()) {
                this.$swal.fire({
                    title: "Looks like you forgot to write your comment.",
                    icon: 'warning'
                });
                return;
            }
            this.comment_body = this.$refs['commentEditor'].getContents()
            this.$refs['commentEditor'].$el.scrollIntoView();
            this.save_pending = true;
            await this.$store.dispatch('addComment', {
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
