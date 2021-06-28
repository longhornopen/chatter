<script>
import WysiwygEditor from './WysiwygEditor'
export default {
    components: { WysiwygEditor },
    props: ['parent_comment_id', 'post_id'],
    data() {
        return {
            anonymous: false,
            comment_body: '',
        };
    },
    methods: {
        cancel() {
            if (this.comment_body.trim().length === 0) {
                this.$emit('close_comment_editor')
                return;
            }
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
        submit() {
            if (this.comment_body.trim().length === 0) {
                this.$swal.fire({
                    title: "Looks like you forgot to write your comment.",
                    icon: 'warning'
                });
                return;
            }
            this.$store.dispatch('addComment', {
                post_id: this.post_id,
                parent_comment_id: this.parent_comment_id,
                author_anonymous: this.anonymous,
                body: this.comment_body,
            });
            this.$emit('close_comment_editor')
        }
    }
}
</script>

<template>
    <div>
        <div>**{{comment_body}}**</div>
        <wysiwyg-editor v-model="comment_body"></wysiwyg-editor>
        <div class="form-group form-check">
            <input type="checkbox" class="form-check-input" id="postAnonymously" v-model="anonymous">
            <label class="form-check-label" for="postAnonymously">Post Anonymously</label>
        </div>
        <div class="editor-btn-group">
            <button class="btn btn-tertiary" @click="cancel()">Cancel</button>
            <button class="btn btn-primary" @click="submit">Submit</button>
        </div>
    </div>

</template>
