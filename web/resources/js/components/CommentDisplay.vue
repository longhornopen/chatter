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
            this_comment: this.comment,
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
                    :date-iso="this_comment.created_at"
                ></formatted-date>
            </div>
            <div class="row">
                <div
                    class="endorse-icon col-1"
                    @click="endorse(!comment_is_endorsed)">
                    <font-awesome-icon
                    :class="comment_is_endorsed ? 'endorsed' : 'can-endorse'" icon="award" size="2x" />
                </div>
                <div class="col-11">
                    <div class="comment-body">
                        <div
                            class="comment-body-text"
                            v-html="this_comment.body"
                        ></div>
                        <div
                            class="reply-icon"
                            @click="toggle_comment_editor(!show_editor)"
                            v-if="add_comment_allowed"
                        >
                            <font-awesome-icon icon="reply" size="lg" color="#7d7d7d"/>
                        </div>
                    </div>
                    <comment-create
                        v-if="add_comment_allowed && show_editor"
                        @close_comment_editor="toggle_comment_editor(false)"
                        :parent_comment_id="this_comment.id"
                        :post_id="this_comment.post_id"
                    ></comment-create>
                    <div style="padding-left:20px;">
                        <div v-for="child_comment in post_comments_with_parent_comment_id(this_comment.id)">
                            <comment-display :comment="child_comment"></comment-display>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</template>
