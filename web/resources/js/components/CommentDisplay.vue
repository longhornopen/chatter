<script>
import UserName from './UserName'
import FormattedDate from './FormattedDate'
import WysiwygEditor from './WysiwygEditor'
import WysiwygViewer from './WysiwygViewer'
import component_mixins from '../component_mixins'

export default {
    components: { UserName, FormattedDate, WysiwygEditor, WysiwygViewer },
    mixins: [component_mixins.course_closed_mixin],
    props: {
        comment: {
            type: Object,
            default: function () { return {}; },
        }
    },
    data() {
        return {
            comment_editor_visible: false,
            edited_comment_body: null,
            reply_editor_visible: false,
            edit_save_pending: false,
        };
    },
    computed: {
        comment_is_endorsed() {
            return this.comment.endorsed;
        },
        comment_is_upvoted_by_user() {
            return this.$store.state.user_upvoted_comment_ids
                .includes(this.comment.id)
        },
        add_comment_allowed() {
            return !this.$store.state.currently_viewed_post.locked
                && !this.course_is_closed
        },
        user_is_teacher() {
            return this.$store.state.user.role === 'teacher'
        },
        comment_is_muted() {
            return !(this.comment.muted_by_user_id === null)
        },
        can_edit() {
            return this.comment.author_user_id === this.$store.getters.user.id
                && !this.course_is_closed
        },
        can_hide_unhide() {
            return this.user_is_teacher
                && !this.course_is_closed
        },
        can_endorse() {
            return this.user_is_teacher
                && !this.course_is_closed
        },
        can_upvote() {
            return !this.course_is_closed
        },
        should_display_options_menu() {
            return this.can_edit || this.can_hide_unhide;
        },
        num_upvotes() {
            return this.comment.num_upvotes;
        }
    },
    methods: {
        endorse(endorse_action) {
            this.$store.dispatch('endorseComment', {
                comment_id: this.comment.id,
                endorsed: endorse_action
            })
        },
        upvote(upvoted) {
            if (upvoted) {
                this.$store.dispatch('addCommentUpvote', {
                    comment_id: this.comment.id,
                })
            } else {
                this.$store.dispatch('removeCommentUpvote', {
                    comment_id: this.comment.id,
                })
            }
        },
        toggle_reply_editor: function(shown) {
            this.reply_editor_visible = shown
            if (shown) {
                this.$nextTick(() => {this.$refs['replyEditor'].$el.scrollIntoView()})
            }
        },
        post_comments_with_parent_comment_id(pcid) {
            if (!this.$store.state.currently_viewed_post.comments) {
                return [];
            }
            return this.$store.state.currently_viewed_post.comments.filter(c => c.parent_comment_id === pcid);
        },
        mute_comment: function(action) {
            this.$store.dispatch('muteComment', {
                comment_id: this.comment.id,
                muted: action,
            })
        },
        show_comment_editor: function() {
            this.edited_comment_body = this.comment.body
            this.comment_editor_visible = true
            this.$nextTick(() => {this.$refs['commentEditor'].$el.scrollIntoView()})
        },
        hide_comment_editor: function() {
            this.$swal.fire({
                icon: 'warning',
                title: 'Do you want to abandon your edit without saving?',
                showCancelButton: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    this.edited_comment_body = null;
                    this.comment_editor_visible = false
                }
            });
        },
        async save_comment() {
            if (!this.$refs['commentEditor'].hasContents()) {
                this.$swal.fire({
                    title: "Looks like you forgot to write your comment.",
                    icon: 'warning'
                });
                return;
            }
            this.edited_comment_body = this.$refs['commentEditor'].getContents()
            this.$refs['commentEditor'].$el.scrollIntoView();
            this.edit_save_pending = true;
            await this.$store.dispatch('editComment', {
                comment_id: this.comment.id,
                body: this.edited_comment_body,
            })
            this.edit_save_pending = false;
            this.edited_comment_body = null;
            this.comment_editor_visible = false;
        },
    },
}
</script>

<template>
    <div class="single-comment">
        <div>
            <div class="comment-top-row">
                <div class="comment-metadata">
                    <div v-if="comment.is_unread" class="unread-dot">
                    </div>
                    <div>
                        <user-name
                            :name="comment.author_user_name"
                            :anonymous="comment.author_anonymous"
                            :user-id="comment.author_user_id"
                        ></user-name>
                        <formatted-date
                            :date-iso="comment.created_at"
                        ></formatted-date>
                    </div>
                    <div class="endorse-icon" v-if="comment_is_endorsed">
                        <font-awesome-icon
                        class="endorsed"
                        icon="award" size="lg"
                        title="An instructor has endorsed this comment"
                        />
                    </div>
                    <div class="upvote-icon"
                         v-if="num_upvotes > 0"
                         :title="'This comment has been upvoted '+num_upvotes+' times.'"
                    >
                        <font-awesome-icon
                        class="upvotes"
                        icon="arrow-circle-up" size="lg"/> {{num_upvotes}}
                    </div>
                </div>
                <div
                    v-show="should_display_options_menu"
                >
                    <div class="ellipsis" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <font-awesome-icon icon="ellipsis-h" />
                    </div>
                    <div class="dropdown-menu dropdown-menu-right">
                        <button
                            @click="show_comment_editor()"
                            class="dropdown-item"
                            type="button"
                            v-show="can_edit"
                        >Edit</button>
                        <div v-if="can_hide_unhide">
                            <div v-if="!comment_is_muted">
                                <button @click="mute_comment(true)" class="dropdown-item" type="button">Hide</button>
                            </div>
                            <div v-if="comment_is_muted">
                                <button @click="mute_comment(false)" class="dropdown-item" type="button">Un-hide</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <div class="comment-container">
                    <div v-if="comment_editor_visible">
                        <fieldset v-bind:disabled="edit_save_pending">
                        <wysiwyg-editor v-model="edited_comment_body" ref="commentEditor"></wysiwyg-editor>
                        <div class="btn-groups">
                            <div class="left"></div>
                            <div class="right">
                                <div class="editor-btn-group">
                                    <button
                                        class="btn btn-tertiary"
                                        @click="hide_comment_editor()">Cancel</button>
                                    <button
                                        class="btn btn-primary"
                                        @click="save_comment()"
                                    ><font-awesome-icon v-if="edit_save_pending" icon="spinner" spin /> Save</button>
                                </div>
                            </div>
                        </div>
                        </fieldset>
                    </div>

                    <div v-else class="comment-body-group">
                        <div class="comment-body">
                            <div v-if="comment_is_muted">
                                <div class="comment-body-text comment-body-text-muted">
                                    (This comment has been hidden by an instructor.)
                                </div>
                            </div>
                            <div v-else>
                                <div
                                    class="comment-body-text"
                                >
                                    <wysiwyg-viewer v-model="comment.body"></wysiwyg-viewer>
                                </div>
                            </div>
                        </div>
                        <div class="comment-actions">
                            <div class="left-actions">
                                <div
                                    class="endorse-action"
                                    v-if="can_endorse"
                                    @click="endorse(!comment_is_endorsed)"
                                >
                                    <font-awesome-icon
                                        icon="award"
                                        class="action-icon"
                                        :class="comment_is_endorsed ? 'action-icon-active-secondary' : ''"
                                    />
                                    <div
                                        :class="comment_is_endorsed ? 'action-active-secondary' : ''">
                                        {{comment_is_endorsed ? 'Unendorse' : 'Endorse'}}
                                    </div>
                                </div>
                                <div
                                    class="upvote-action"
                                    v-if="can_upvote"
                                    @click="upvote(!comment_is_upvoted_by_user)"
                                >
                                    <font-awesome-icon
                                        icon="arrow-circle-up"
                                        class="action-icon"
                                        :class="comment_is_upvoted_by_user ? 'action-icon-active-secondary' : ''"/>
                                    <div
                                        :class="comment_is_upvoted_by_user ? 'action-icon-active-secondary' : ''"
                                    >
                                        {{comment_is_upvoted_by_user ? 'Upvoted' : 'Upvote'}}
                                    </div>
                                </div>
                            </div>
                            <div class="right-actions">
                                <div
                                    class="reply-action"
                                    @click="toggle_reply_editor(!reply_editor_visible)"
                                    v-if="add_comment_allowed">
                                    <font-awesome-icon icon="comment-alt" class="icon"/>
                                    <div>Reply</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div style="padding-left:20px;">
                        <comment-create
                            v-if="add_comment_allowed && reply_editor_visible"
                            @close_comment_editor="toggle_reply_editor(false)"
                            :parent_comment_id="comment.id"
                            :post_id="comment.post_id"
                            ref="replyEditor"
                        ></comment-create>
                    </div>
                    <div style="padding-left:20px;">
                        <div v-for="child_comment in post_comments_with_parent_comment_id(comment.id)">
                            <comment-display :comment="child_comment"></comment-display>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</template>

<style lang="scss" scoped>
@import '../../sass/_variables.scss';

</style>
