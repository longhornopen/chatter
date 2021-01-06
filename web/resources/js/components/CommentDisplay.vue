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
            return this.$store.state.user.role == 'teacher'
        },
        comment_is_muted() {
            return !(this.comment.muted_by_user_id === null)
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
        }
    }
}
</script>

<template>
    <div class="single-comment">
        <div>
            <div style="font-style:italic;font-size:80%;">
                <user-name
                    :name="comment.author_user_name"
                    :anonymous="comment.author_anonymous"
                    :user-id="comment.author_user_id"
                ></user-name>
                <formatted-date
                    :date-iso="comment.created_at"
                ></formatted-date>
                <span
                    v-if="comment.is_unread"
                >NEW
                </span>
            </div>
            <div class="row comment-row">
                <div
                    class="endorse-icon"
                    @click="user_is_teacher ? endorse(!comment_is_endorsed) : null">
                    <font-awesome-icon
                    :class="user_is_teacher ? (comment_is_endorsed ? 'endorsed' : 'can-endorse') :
                    (comment_is_endorsed ? 'endorsed' : 'invisible')"
                    icon="award" size="lg" />
                </div>
                <div class="comment-container">
                    <div class="comment-body-group">
                        <div class="comment-body">
                            <div v-if="comment_is_muted">
                                <div class="comment-body-text comment-body-text-muted">
                                    (This comment is hidden.)
                                </div>
                            </div>
                            <div v-else>
                                <div
                                    class="comment-body-text"
                                    v-html="comment.body"
                                ></div>
                            </div>
                            <div
                                class="reply-icon"
                                @click="toggle_comment_editor(!show_editor)"
                                v-if="add_comment_allowed"
                                data-toggle="tooltip"
                                data-placement="top"
                                title="Click to Reply to this Comment"
                                type="button"
                            >
                                <font-awesome-icon icon="reply" size="lg" color="#7d7d7d"/>
                            </div>
                        </div>
                        <div
                            class="hide-btn"
                            @click="mute_comment(comment_is_muted ? false : true)"><u>{{comment_is_muted ? 'Unhide' : 'Hide'}}</u></div>
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
