<script>
export default {
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
        user_is_teacher() {
            return this.$store.state.user.role == 'teacher'
            // return false;
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
        toggle_comment_editor: function(action) {
            this.show_editor = action
        }
    }
}
</script>

<template>
    <div class="single-comment">
        <div>
            <div style="font-style:italic;font-size:80%;">
                {{ this_comment.author_user_name }}
                {{ this_comment.created_at }}
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
                    <div class="comment-body">
                        <div
                            class="comment-body-text"
                            v-html="this_comment.body"
                        ></div>
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
