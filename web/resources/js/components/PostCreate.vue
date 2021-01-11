<script>
import WysiwygEditor from './WysiwygEditor'
export default {
    components: { WysiwygEditor },
    data() {
        return {
            'title': '',
            'body': '',
            'anonymous': false,
        };
    },
    computed: {
        in_mobile_mode() {
            return this.$store.getters.mobile;
        }
    },
    methods: {
        submit_new_post() {
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
        close_post_editor() {
            if (this.body.trim().length === 0) {
                if (!this.in_mobile_mode) {
                    this.$store.dispatch('setAppMainPanelMode', {mode: 'welcome'});
                } else {
                    this.$store.dispatch('switchScreen', {
                        view_post_create: false,
                        view_post_list: true,
                        view_post_display: false,
                    })
                }
                return;
            }
            this.$swal.fire({
                title: "Are you sure you want to abandon this post without saving it?",
                icon: 'warning',
                showCancelButton: true,
            }).then(result => {
                if (result.isConfirmed) {
                    if (!this.in_mobile_mode) {
                        this.$store.dispatch('setAppMainPanelMode', {mode: 'welcome'});
                    } else {
                        this.$store.dispatch('switchScreen', {
                            view_post_create: false,
                            view_post_list: true,
                            view_post_display: false,
                        })
                    }
                    
                }
            });
        },
        switch_screen() {
            if (this.$store.getters.mobile) {
                this.$store.dispatch('switchScreen', {
                    view_post_list: true,
                    view_post_display: false,
                    view_post_create: false,
                })
                this.$store.dispatch('setAppMainPanelMode', {
                    mode: 'show_post',
                })
            }
        },
    }
}
</script>

<template>
    <div class="new-post">
        <!-- <form> -->
            <div class="back-group" v-if="in_mobile_mode" @click="switch_screen()">
                <font-awesome-icon class="back-icon" icon="chevron-left" size="2x"/>
                <h5>Back</h5>
            </div>
            <div class="form-group">
                <label for="post-title">Post Title</label>
                <input class="form-control" id="post-title" v-model="title">
            </div>
            <div class="form-group">
                <label for="post-body">Post Body</label>
                <wysiwyg-editor v-model="body"></wysiwyg-editor>
            </div>
            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" id="postAnonymously" v-model="anonymous">
                <label class="form-check-label" for="postAnonymously">Post Anonymously</label>
            </div>
            <div class="editor-btn-group">
                <button
                    class="btn btn-tertiary"
                    @click="close_post_editor()">Cancel</button>
                <button
                    class="btn btn-secondary"
                    @click="submit_new_post()">Post</button>
            </div>

        <!-- </form> -->

    </div>

</template>
