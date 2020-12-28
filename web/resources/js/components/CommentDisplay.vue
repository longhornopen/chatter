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
        };
    }
}
</script>

<template>
    <div>
        <div style="font-style:italic;font-size:80%;">
            {{ this_comment.author_user_name }}
            {{ this_comment.created_at }}
        </div>
        <div class="comment-body" v-html="this_comment.body"></div>
        <div style="padding-left:20px;">
            <div v-for="child_comment in this_comment.child_comments">
                <comment-display :comment="child_comment"></comment-display>
            </div>
        </div>
    </div>
</template>
