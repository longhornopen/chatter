<script>
import UserName from './UserName';
import FormattedDate from './FormattedDate';
export default {
    components: { UserName, FormattedDate },
    props: {
        comment: {
            type: Object,
            default: function () { return {}; },
        }
    },
    data() {
        return {
            show_editor: false,
        };
    },
    computed: {
        comment_is_endorsed() {
            return this.comment.endorsed;
        },
        add_comment_allowed() {
            return !this.$store.state.currently_viewed_post.locked;
        },
        user_is_teacher() {
            return this.$store.state.user.role === 'teacher'
        },
        comment_is_muted() {
            return !(this.comment.muted_by_user_id === null)
        },
        num_upvotes() {
            return 0; // FIXME
        }
    },
    methods: {
        endorse(endorse_action) {
            this.$store.dispatch('endorseComment', {
                comment_id: this.comment.id,
                endorsed: endorse_action
            })
        },
        toggle_comment_editor: function(action) {
            this.show_editor = action
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
        edit_comment: function() {
            this.$swal.fire({
                title: "Editing comments is not yet implemented. Coming soon!",
                icon: 'warning',
            })
        },
    }
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
                        icon="award" size="lg"/>
                    </div>
                    <!-- FIXME: only show if comment has upvotes. Also show number of upvotes here -->
                    <div class="upvote-icon"
                        v-if="num_upvotes > 0"
                    >
                        <font-awesome-icon
                        class="upvotes"
                        icon="arrow-circle-up" size="lg"/> {{num_upvotes}}
                    </div>

                </div>
                <div>
                    <div class="ellipsis" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <font-awesome-icon icon="ellipsis-h" />
                    </div>
                    <div class="dropdown-menu dropdown-menu-right">
                        <button @click="edit_comment()" class="dropdown-item" type="button">Edit</button>
                        <div v-if="user_is_teacher && !comment_is_muted">
                            <button @click="mute_comment(true)" class="dropdown-item" type="button">Hide</button>
                        </div>
                        <div v-if="user_is_teacher && comment_is_muted">
                            <button @click="mute_comment(false)" class="dropdown-item" type="button">Un-hide</button>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <div class="comment-container">
                    <div class="comment-body-group">
                        <div class="comment-body">
                            <div v-if="comment_is_muted">
                                <div class="comment-body-text comment-body-text-muted">
                                    (This comment has been hidden by an instructor.)
                                </div>
                            </div>
                            <div v-else>
                                <div
                                    class="comment-body-text"
                                    v-html="comment.body"
                                ></div>
                            </div>
                        </div>
                        <div class="comment-actions">
                            <div class="left-actions">
                                <div
                                    class="endorse-action" 
                                    v-if="user_is_teacher"
                                    @click="endorse(!comment_is_endorsed)"
                                >
                                    <font-awesome-icon
                                        icon="award"
                                        class="action-icon"
                                        :class="comment_is_endorsed ? 'action-icon-active-secondary' : ''"/>
                                    <div
                                        :class="comment_is_endorsed ? 'action-active-secondary' : ''">
                                        {{comment_is_endorsed ? 'Unendorse' : 'Endorse'}}
                                    </div>
                                </div>
                                <!--
                                <div class="upvote-action">
                                    <font-awesome-icon icon="arrow-circle-up" class="action-icon"/>
                                    <div>Upvote</div>
                                </div>
                                -->
                            </div>
                            <div class="right-actions">
                                <div
                                    class="reply-action"
                                    @click="toggle_comment_editor(!show_editor)"
                                    v-if="add_comment_allowed">
                                    <font-awesome-icon icon="comment-alt" class="icon"/>
                                    <div>Reply</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <comment-create
                        v-if="add_comment_allowed && show_editor"
                        @close_comment_editor="toggle_comment_editor(false)"
                        :parent_comment_id="comment.id"
                        :post_id="comment.post_id"
                    ></comment-create>
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
