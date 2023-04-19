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
            endorse_promise_pending: false,
            upvote_promise_pending: false,
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
        async endorse(endorse_action) {
            if (this.endorse_promise_pending) {
                return;
            }
            this.endorse_promise_pending = true;
            try {
                let store_promise = this.$store.dispatch('endorseComment', {
                    comment_id: this.comment.id,
                    endorsed: endorse_action
                })
                let mintimer_promise = new Promise((resolve, reject) => (setTimeout(() => resolve(true), 1000)))
                await Promise.all([store_promise, mintimer_promise])
            } finally {
                this.endorse_promise_pending = false;
            }
        },
        async upvote(upvoted) {
            if (this.upvote_promise_pending) {
                return;
            }
            this.upvote_promise_pending = true;
            try {
                let action_name = upvoted ? 'addCommentUpvote' : 'removeCommentUpvote';
                let store_promise =  this.$store.dispatch(action_name, {
                    comment_id: this.comment.id,
                })
                let mintimer_promise = new Promise((resolve, reject) => (setTimeout(() => resolve(true), 1000)))
                await Promise.all([store_promise, mintimer_promise])
            } finally {
                this.upvote_promise_pending = false;
            }
        },
        toggle_reply_editor(shown) {
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
        mute_comment(action) {
            this.$store.dispatch('muteComment', {
                comment_id: this.comment.id,
                muted: action,
            })
        },
        show_comment_editor() {
            this.edited_comment_body = this.comment.body
            this.comment_editor_visible = true
            this.$nextTick(() => {this.$refs['commentEditor'].$el.scrollIntoView()})
        },
        hide_comment_editor: function() {
            this.$bvModal.show('abandon_edit');
        },
        handle_hide_comment_editor_ok() {
            this.edited_comment_body = null
            this.comment_editor_visible = false
        },
        async save_comment() {
            if (!this.$refs['commentEditor'].hasContents()) {
                this.$bvModal.show('missing_comment');
                return;
            }
            this.edited_comment_body = this.$refs['commentEditor'].getContents()
            this.$refs['commentEditor'].$el.scrollIntoView();
            this.edit_save_pending = true;
            let comment = await this.$store.dispatch('editComment', {
                comment_id: this.comment.id,
                body: this.edited_comment_body,
            })
            this.comment = comment
            this.edit_save_pending = false;
            this.edited_comment_body = null;
            this.comment_editor_visible = false;
        },
    },
}
</script>

<template>
    <div class="single-comment">
        <b-modal id="missing_comment" title="Missing Comment" :ok-only="true"
                 header-bg-variant="warning"
                 header-text-variant="light"
        >
            <p>It looks like you forgot to write your comment.</p>
        </b-modal>
        <b-modal id="abandon_edit" title="Abandon Edit?" @ok="handle_hide_comment_editor_ok"
                 header-bg-variant="warning"
                 header-text-variant="light"
        >
            <p>Are you sure you want to abandon your edit without saving?</p>
        </b-modal>
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
                            :role="comment.author_user_role"
                        ></user-name>
                        <formatted-date
                            :date-iso="comment.created_at"
                        ></formatted-date>
                        <span v-if="comment.edited_at">
                        <i style="font-size:90%;">
                            (Edited)
                        </i>
                        </span>
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
                                <b-button
                                    class="no-shadow endorse-action"
                                    v-if="can_endorse"
                                    @click="endorse(!comment_is_endorsed)"
                                    ref="endorse_button"
                                    :class="comment_is_endorsed ? 'action-active-secondary' : ''"
                                    :disabled="endorse_promise_pending"
                                >
                                    <font-awesome-icon
                                        icon="award"
                                        v-if="!endorse_promise_pending"
                                    />
                                    <b-spinner small
                                        v-if="endorse_promise_pending"
                                    ></b-spinner>
                                    <span class="left-action-button-text">
                                        {{comment_is_endorsed ? 'Unendorse' : 'Endorse'}}
                                    </span>
                                </b-button>
                                <b-button
                                    class="no-shadow upvote-action"
                                    v-if="can_upvote"
                                    @click="upvote(!comment_is_upvoted_by_user)"
                                    ref="upvote_button"
                                    :class="comment_is_upvoted_by_user ? 'action-active-secondary' : ''"
                                    :disabled="upvote_promise_pending"
                                >
                                    <font-awesome-icon
                                        icon="arrow-circle-up"
                                        v-if="!upvote_promise_pending"
                                    />
                                    <b-spinner small
                                               v-if="upvote_promise_pending"
                                    ></b-spinner>
                                    <span class="left-action-button-text">
                                        {{comment_is_upvoted_by_user ? 'Upvoted' : 'Upvote'}}
                                    </span>
                                </b-button>
                            </div>
                            <div class="right-actions">
                                <div
                                    class="reply-action"
                                    @click="toggle_reply_editor(!reply_editor_visible)"
                                    v-if="add_comment_allowed">
                                    <font-awesome-icon icon="comment-alt" class="icon"/> Reply
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

.comment-container{
    display: flex;
    flex-direction: column;
    width: 100%;
}

.comment-top-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    .ellipsis {
        display: flex;
        align-items: baseline;
        border: none;
    }
}

.comment-metadata {
    font-style: italic;
    font-size: 80%;
    display: flex;
    align-items: center;
    div {
        margin: 0 7px 0 0;
    }
    .endorsed {
        color: $secondary;
    }
    .upvotes {
        color: $tertiary;
    }
}

.unread-dot {
    width: 7px;
    height: 7px;
    background-color: $primary;
    opacity: 70%;
    border-radius: 5px;
}

.comment-body-group {
    display: flex;
    flex-direction: column;
    width: 100%;
}

// the gray comment bubble
.comment-body {
    background-color: $commentgray;
    border-radius: 6px;
    padding: 10px;
    margin: 10px 0 5px 0;
    position: relative;
    width: 100%;
}

.comment-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin: 0 0 10px 0;
    .left-actions, .right-actions {
        display: flex;
        .btn {
            background-color: transparent;
            border-color: transparent;
            margin-right: 7px;
            padding: 3px 6px;
            color: $middlegray;
            .left-action-button-text {
                margin-left: 5px;
            }
        }
        .btn.action-active-secondary {
            color: $secondary;
        }
        .btn:hover {
            transform: translateY(-1px);
        }
    }
    .right-actions div {
        margin-right: 0;
        color: $tertiary;
    }
}

.comment-body-text {
    padding-right: 30px;
}
.comment-body-text p:last-child {
    margin-bottom: 0;
}
.reply-icon {
    position: absolute;
    top: 10px;
    right: 10px;
    cursor: pointer;
}
.single-comment {
    .row {
        .col-11 {
            padding-left: 0;
        }
    }
    .comment-row {
        display: flex;
        margin: 0;
    }
}
</style>
