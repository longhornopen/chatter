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
    data() {
        return {
            title: '',
            body: '',
            post_tag_uuid: null,
            anonymous: false,
            save_pending: false,
        };
    },
    computed: {
        in_mobile_mode() {
            return this.store.mobile;
        },
        post_tag_options() {
            let user_is_teacher = this.store.user.role === 'teacher'
            let post_tags = this.store.course_summary.post_tags
                .filter(t => {return user_is_teacher || !t.teacher_only})
                .map(t => {return { value: t.uuid, text: t.name }})
            post_tags.unshift({ value: null, text: '(no tag)' })
            return post_tags;
        },
    },
    methods: {
        async submit_new_post() {
            if (this.title.trim().length === 0) {
                this.$refs['missing_title'].show();
                return;
            }
            if (!this.$refs['postEditor'].hasContents()) {
                this.$refs['missing_body'].show();
                return;
            }
            this.body = this.$refs['postEditor'].getContents()
            this.$refs['postEditor'].$el.scrollIntoView();
            this.save_pending = true;
            let post = await this.store.createPost({
                title: this.title,
                body: this.body,
                tag: this.post_tag_uuid,
                author_anonymous: this.anonymous,
            });
            this.save_pending = false;
            await this.$router.push('/post/'+post.id);
        },
        close_post_editor() {
            this.$refs['abandon_post'].show();
        },
        handle_close_post_editor_ok() {
            if (this.in_mobile_mode) {
                this.store.switchScreen({
                    view_post_create: false,
                    view_post_list: true,
                    view_post_display: false,
                })
            }
            this.$router.push('/')
        },
        switch_screen() {
            if (this.store.mobile) {
                this.store.switchScreen({
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
        <modal ref="missing_title" title="Missing Title" :ok-only="true"
                 header-bg-variant="warning"
                 header-text-variant="light"
        >
            <template v-slot:body>
            <p>It looks like you forgot to write your post title.</p>
            </template>
        </modal>
        <modal ref="missing_body" title="Missing Body" :ok-only="true"
                 header-bg-variant="warning"
                 header-text-variant="light"
        >
            <template v-slot:body>
            <p>It looks like you forgot to write your post body.</p>
            </template>
        </modal>
        <modal ref="abandon_post" title="Abandon Post?"
                 header-bg-variant="warning"
                 header-text-variant="light"
        >
            <template v-slot:body>
            <p>Are you sure you want to abandon this post without saving it?</p>
            </template>
            <template v-slot:footer>
                <button class="btn btn-primary" @click="handle_close_post_editor_ok()">Ok</button>
            </template>
        </modal>
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
                <select class="form-select" v-model="post_tag_uuid">
                    <option v-for="option in post_tag_options" :key="option.value" :value="option.value">
                        {{ option.text }}
                    </option>
                </select>
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

.new-post {
    padding: 30px;
}
</style>
