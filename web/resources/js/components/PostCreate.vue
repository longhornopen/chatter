<script>
import WysiwygEditor from './WysiwygEditor'

export default {
    components: { WysiwygEditor },
    data() {
        return {
            title: '',
            body: '',
            post_tag: null,
            anonymous: false,
            save_pending: false,
        };
    },
    computed: {
        in_mobile_mode() {
            return this.$store.getters.mobile;
        },
        post_tag_options() {
            let user_is_teacher = this.$store.getters.user.role === 'teacher'
            let post_tags = this.$store.getters.course_summary.post_tags
                .filter(t => {return user_is_teacher || !t.teacher_only})
                .map(t => {return { value: t.name, text: t.name }})
            post_tags.unshift({ value: null, text: '(no tag)' })
            return post_tags;
        },
    },
    methods: {
        async submit_new_post() {
            if (this.title.trim().length === 0) {
                this.$swal.fire({
                    title: "Looks like you forgot to write your post title.",
                    icon: 'warning'
                });
                return;
            }
            if (!this.$refs['postEditor'].hasContents()) {
                this.$swal.fire({
                    title: "Looks like you forgot to write your post body.",
                    icon: 'warning'
                });
                return;
            }
            this.body = this.$refs['postEditor'].getContents()
            this.$refs['postEditor'].$el.scrollIntoView();
            this.save_pending = true;
            let post = await this.$store.dispatch('createPost', {
                title: this.title,
                body: this.body,
                tag: this.post_tag,
                author_anonymous: this.anonymous,
            });
            this.save_pending = false;
            await this.$router.push('/post/'+post.id);
        },
        close_post_editor() {
            this.$swal.fire({
                title: "Are you sure you want to abandon this post without saving it?",
                icon: 'warning',
                showCancelButton: true,
            }).then(result => {
                if (result.isConfirmed) {
                    if (this.in_mobile_mode) {
                        this.$store.dispatch('switchScreen', {
                            view_post_create: false,
                            view_post_list: true,
                            view_post_display: false,
                        })
                    }
                    this.$router.push('/')
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
            }
            this.$router.push('/')
        },
    }
}
</script>

<template>
    <div class="new-post">
        <fieldset v-bind:disabled="save_pending">
            <div class="back-group" v-if="in_mobile_mode" @click="switch_screen()">
                <font-awesome-icon class="back-icon" icon="chevron-left" size="2x"/>
                <h5>Back</h5>
            </div>
            <div class="form-group">
                <label for="post-title">Post Title</label>
                <input class="form-control" id="post-title" v-model="title">
            </div>
            <div class="form-group">
                <label for="post-tag">Tag post as:</label>
                <b-form-select v-model="post_tag" :options="post_tag_options"></b-form-select>
            </div>
            <div class="form-group">
                <label for="post-body">Post Body</label>
                <wysiwyg-editor v-model="body" ref="postEditor"></wysiwyg-editor>
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
                    @click="submit_new_post()"><font-awesome-icon v-if="save_pending" icon="spinner" spin /> Post</button>
            </div>

        </fieldset>

    </div>

</template>


<style lang="scss" scoped>
@import '../../sass/_variables.scss';

</style>
