<script>
import UserName from './UserName.vue'
import FormattedDate from './FormattedDate.vue'
import PostTagBadge from './PostTagBadge.vue'
import WysiwygEditor from './WysiwygEditor.vue'
import WysiwygViewer from './WysiwygViewer.vue'
import component_mixins from '../component_mixins'
import Modal from './Modal.vue'
import CommentDisplay from "./CommentDisplay.vue";
import CommentCreate from "./CommentCreate.vue";

export default {
    components: { UserName, FormattedDate, WysiwygEditor, WysiwygViewer, PostTagBadge, Modal, CommentDisplay, CommentCreate },
    mixins: [component_mixins.course_closed_mixin],
    props: {
        post_id: { type: Number, required: true },
    },
    data() {
        return {
            comment_editor_visible: false,
            post_editor_visible: false,
            edited_post_body: null,
            edit_save_pending: false,
            pin_pending: false,
            lock_pending: false,
            post_loaded: false,
        };
    },
    computed: {
        post() {
            return this.$store.state.currently_viewed_post
        },
        user_is_teacher() {
            return this.$store.getters.user.role === 'teacher';
        },
        can_edit() {
            return this.post.author_user_id === this.$store.getters.user.id
                && !this.course_is_closed
        },
        can_delete() {
            return this.user_is_teacher
                && !this.course_is_closed
        },
        can_pin() {
            return this.user_is_teacher && !this.comment_editor_visible
                && !this.course_is_closed
        },
        can_lock() {
            return this.user_is_teacher && !this.comment_editor_visible
                && !this.course_is_closed
        },
        should_display_options_menu() {
            return this.can_edit || this.can_delete;
        },
        add_comment_allowed() {
            return !this.post.locked
                && !this.course_is_closed
        },
        in_mobile_mode() {
            return this.$store.getters.mobile;
        }
    },
    methods: {
        async pin(pinned) {
            this.pin_pending = true;
            await this.$store.dispatch('pinPost', {
                post_id: this.post_id,
                pinned: pinned
            });
            this.pin_pending = false;
        },
        remove() {
            this.$refs['delete_post'].show();
        },
        handle_remove_ok() {
            this.$store.dispatch('deletePost', {
                post_id: this.post_id,
            });
            this.$router.push('/')
        },
        async lock(locked) {
            this.lock_pending = true;
            await this.$store.dispatch('lockPost', {
                post_id: this.post_id,
                locked: locked
            });
            this.lock_pending = false;
        },
        post_comments_with_parent_comment_id(pcid) {
            if (!this.post.comments) {
                return [];
            }
            return this.post.comments.filter(c => c.parent_comment_id === pcid);
        },
        open_post_editor() {
            this.post_editor_visible = true;
            this.comment_editor_visible = false;
            this.edited_post_body = "" + this.post.body; //copy
        },
        close_post_editor() {
            this.$refs['abandon_post'].show();
        },
        handle_close_post_editor_ok() {
            this.edited_post_body = null;
            this.post_editor_visible = false;
            this.$refs['abandon_post'].hide();
        },
        async save_post() {
            if (!this.$refs['postEditor'].hasContents()) {
                this.$refs['missing_body'].show();
                return;
            }
            let new_body = this.$refs['postEditor'].getContents()
            this.$refs['postEditor'].$el.scrollIntoView();
            this.edit_save_pending = true;
            await this.$store.dispatch('editPost', {
                post_id: this.post_id,
                body: new_body,
            })
            this.post_editor_visible = false;
            this.edit_save_pending = false;
        },
        switch_screen() {
            if (this.$store.getters.mobile) {
                this.$store.dispatch('switchScreen', {
                    view_post_list: true,
                    view_post_display: false,
                    view_post_create: false,
                })
            }
        },
    },
    async mounted() {
        await this.$store.dispatch('setCurrentlyViewedPost', {'post_id': this.post_id});
        this.post_loaded = true;
    },
}
</script>

<template>
    <div>
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
        <modal ref="delete_post" title="Remove Post?"
                 header-bg-variant="warning"
                 header-text-variant="light"
        >
            <template v-slot:body>
            <p>Are you sure you want to remove this post and all its comments?</p>
            </template>
            <template v-slot:footer>
                <button class="btn btn-primary" @click="handle_remove_ok()">Ok</button>
            </template>
        </modal>
        <div v-if="!post_loaded" class="d-flex justify-content-center mt-5">
            <div class="spinner-border" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <div v-if="post_loaded" class="app-post-display">
            <div class="back-group" v-if="in_mobile_mode" @click="switch_screen()">
                <font-awesome-icon class="back-icon" icon="chevron-left" size="2x"/>
                <h5>Back</h5>
            </div>
            <div>
                <h2>
                    <post-tag-badge :post_tag_name="post.tag"/> {{ post.title }}
                </h2>
                <div class="post-top-row">
                    <div>
                        <user-name
                            :name="post.author_user_name"
                            :anonymous="post.author_anonymous"
                            :user-id="post.author_user_id"
                            :role="post.author_user_role"
                        ></user-name>
                        <formatted-date
                            :date-iso="post.created_at"
                        ></formatted-date>
                        <span v-if="post.edited_at">
                        <i style="font-size:90%;">
                            (Edited
                            <formatted-date
                            :date-iso="post.edited_at"
                        ></formatted-date>
                            )
                        </i>
                        </span>
                    </div>
                    <div
                        v-show="should_display_options_menu"
                    >
                        <div class="ellipsis" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <font-awesome-icon icon="ellipsis-h" />
                        </div>
                        <div class="dropdown-menu dropdown-menu-right">
                            <button
                                @click="open_post_editor()"
                                class="dropdown-item"
                                type="button"
                                v-show="can_edit"
                            >Edit</button>
                            <button
                                @click="remove()"
                                class="dropdown-item"
                                type="button"
                                v-show="can_delete"
                            >Delete</button>
                        </div>
                    </div>
                </div>

                <div v-if="post_editor_visible">
                    <fieldset v-bind:disabled="edit_save_pending">
                    <wysiwyg-editor v-model="edited_post_body" ref="postEditor"></wysiwyg-editor>
                    <div class="btn-groups">
                        <div class="left"></div>
                        <div class="right">
                            <button
                                class="btn btn-tertiary me-1"
                                @click="close_post_editor()">Cancel</button>
                            <button
                                class="btn btn-secondary"
                                @click="save_post()"
                            ><font-awesome-icon v-if="edit_save_pending" icon="spinner" spin /> Save</button>
                        </div>
                    </div>
                    </fieldset>
                </div>
                <div v-if="!post_editor_visible">
                    <div class="post-display-body">
                        <wysiwyg-viewer v-model="post.body" :key="'postbody_'+post.id"></wysiwyg-viewer>
                    </div>
                    <div class="btn-groups">
                        <div class="left">
                            <button
                                class="btn btn-secondary"
                                v-if="can_pin"
                                @click="pin(!post.pinned)"
                                :disabled="pin_pending"
                            ><font-awesome-icon v-if="pin_pending" icon="spinner" spin /> {{post.pinned ? "Unpin" : "Pin"}}</button>
                            <button
                                class="btn btn-secondary"
                                v-if="can_lock"
                                @click="lock(!post.locked)"
                                :disabled="lock_pending"
                            ><font-awesome-icon v-if="lock_pending" icon="spinner" spin /> {{post.locked ? "Unlock" : "Lock"}}</button>
                        </div>

                        <div class="right" v-if="add_comment_allowed">
                            <button
                                class="btn btn-primary"
                                :class="!comment_editor_visible?'':'d-none'"
                                @click="comment_editor_visible=true">Comment</button>
                        </div>
                    </div>
                </div>

                    <comment-create
                        v-if="comment_editor_visible"
                        @close_comment_editor="comment_editor_visible = false"
                        :parent_comment_id="null"
                        :post_id="this.post.id"
                    ></comment-create>
            </div>
            <div class="comments">
                <div v-for="comment in post_comments_with_parent_comment_id(null)">
                    <comment-display
                        :key="comment.id"
                        :comment="comment"
                    ></comment-display>
                </div>
            </div>
        </div>
    </div>
</template>

<style lang="scss" scoped>
@import '../../sass/_variables.scss';

</style>
