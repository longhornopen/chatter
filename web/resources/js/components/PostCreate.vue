<script>
export default {
    data() {
        return {
            'title': '',
            'body': '',
            'anonymous': false,
        };
    },
    methods: {
        submit_new_post: function() {
            if (this.title.trim().length === 0) {
                this.$swal.fire({
                    title: "Looks like you forgot to write your post title.",
                    icon: 'warning'
                });
                return;
            }
            if (this.body.trim().length === 0) {
                this.$swal.fire({
                    title: "Looks like you forgot to write your post body.",
                    icon: 'warning'
                });
                return;
            }
            this.$store.dispatch('createPost', {
                title: this.title,
                body: this.body,
                author_anonymous: this.anonymous,
            });
        },
        close_post_editor: function() {
            if (this.body.trim().length === 0) {
                this.$store.dispatch('setAppMainPanelMode', {mode: 'welcome'});
                return;
            }
            this.$swal.fire({
                title: "Are you sure you want to abandon this post without saving it?",
                icon: 'warning',
                showCancelButton: true,
            }).then(result => {
                if (result.isConfirmed) {
                    this.$store.dispatch('setAppMainPanelMode', {mode: 'welcome'});
                }
            });
        }
    }
}
</script>

<template>
    <div class="new-post">
        <!-- <form> -->
            <div class="form-group">
                <label for="post-title">Post Title</label>
                <input class="form-control" id="post-title" v-model="title">
            </div>
            <div class="form-group">
                <label for="post-body">Post Body</label>
                <!-- placeholder for wysiwyg editor -->
                <textarea
                    class="form-control"
                    style="width:100%"
                    v-model="body"
                    placeholder="This is a placeholder for WYSIWYG editor"></textarea>
            </div>
            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" id="postAnonymously" v-model="anonymous">
                <label class="form-check-label" for="postAnonymously">Post Anonymously</label>
            </div>
            <div class="editor-btn-group">
                <button
                    class="btn btn-gray"
                    @click="close_post_editor()">Cancel</button>
                <button
                    class="btn btn-blue"
                    @click="submit_new_post()">Post</button>
            </div>

        <!-- </form> -->

    </div>

</template>
