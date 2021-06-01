<script>
import UserName from './UserName';
import FormattedDate from './FormattedDate';
import _ from 'lodash';
import WysiwygEditor from './WysiwygEditor'

export default {
    components: { UserName, FormattedDate, WysiwygEditor },
    data() {
        return {
            comment_editor_visible: false,
            post_editor_visible: false,
            edited_post_body: null,
        };
    },
    computed: {
        post() {
            return this.$store.getters.currently_viewed_post;
        },
        post_loaded() {
            return !_.isEmpty(this.$store.getters.currently_viewed_post);
        },
        user_is_teacher() {
            return this.$store.getters.user.role === 'teacher';
        },
        can_edit() {
            return this.$store.getters.currently_viewed_post.author_user_id === this.$store.getters.user.id;
        },
        can_delete() {
            return this.user_is_teacher;
        },
        should_display_options_menu() {
            return this.can_edit || this.can_delete;
        },
        add_comment_allowed() {
            return !this.$store.state.currently_viewed_post.locked;
        },
        in_mobile_mode() {
            return this.$store.getters.mobile;
        }
    },
    methods: {
        pin(pinned) {
            this.$store.dispatch('pinPost', {
                post_id: this.$store.getters.currently_viewed_post.id,
                pinned: pinned
            });
        },
        remove() {
            this.$swal.fire({
                title: "Are you sure you want to remove this post and all its comments?",
                icon: 'warning',
                showCancelButton: true,
            }).then(result => {
                if (result.isConfirmed) {
                    this.$store.dispatch('deletePost', {
                        post_id: this.$store.getters.currently_viewed_post.id,
                    });
                }
            });
        },
        lock(locked) {
            this.$store.dispatch('lockPost', {
                post_id: this.$store.getters.currently_viewed_post.id,
                locked: locked
            });
        },
        post_comments_with_parent_comment_id(pcid) {
            if (!this.$store.state.currently_viewed_post.comments) {
                return [];
            }
            return this.$store.state.currently_viewed_post.comments.filter(c => c.parent_comment_id === pcid);
        },
        open_post_editor() {
            this.post_editor_visible = true;
            this.comment_editor_visible = false;
            this.edited_post_body = "" + this.post.body; //copy
        },
        close_post_editor() {
            this.$swal.fire({
                icon: 'warning',
                title: 'Do you want to abandon your edit without saving?',
                showCancelButton: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    this.edited_post_body = null;
                    this.post_editor_visible = false;
                }
            });
        },
        async update_post_body(new_body) {
            await this.$store.dispatch('editPost', {
                post_id: this.$store.getters.currently_viewed_post.id,
                body: new_body,
            })
            this.post_editor_visible = false;
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
}
</script>

<template>
    <div>
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
                    {{ post.title }}
                </h2>
                <div class="post-top-row">
                    <div>
                        <user-name
                            :name="post.author_user_name"
                            :anonymous="post.author_anonymous"
                            :user-id="post.author_user_id"
                        ></user-name>
                        <formatted-date
                            :date-iso="post.created_at"
                        ></formatted-date>
                        <span v-if="post.created_at !== post.updated_at">
                        <i style="font-size:90%;">
                            (Edited <formatted-date :date-iso="post.updated_at"></formatted-date>)
                        </i>
                        </span>
                    </div>
                    <div
                        v-show="should_display_options_menu"
                    >
                        <div class="ellipsis" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
                    <wysiwyg-editor v-model="edited_post_body"></wysiwyg-editor>
                    <div class="btn-groups">
                        <div class="left"></div>
                        <div class="right">
                            <button
                                class="btn btn-tertiary"
                                @click="close_post_editor()">Cancel</button>
                            <button
                                class="btn btn-secondary"
                                @click="update_post_body(edited_post_body)"
                            >Save</button>
                        </div>
                    </div>
                </div>
                <div v-if="!post_editor_visible">
                    <div class="post-display-body" v-html="post.body"></div>
                    <div class="btn-groups">
                        <div class="left">
                            <button
                                class="btn btn-secondary"
                                :class="user_is_teacher && !comment_editor_visible?'':'d-none'"
                                @click="pin(!post.pinned)"
                            >{{post.pinned ? "Unpin" : "Pin"}}</button>
                            <button
                                class="btn btn-secondary"
                                :class="user_is_teacher && !comment_editor_visible?'':'d-none'"
                                @click="lock(!post.locked)"
                            >{{post.locked ? "Unlock" : "Lock"}}</button>
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
                        :comment="comment"
                    ></comment-display>
                </div>
            </div>
        </div>
    </div>
</template>
