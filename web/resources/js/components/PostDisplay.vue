<script>
export default {
    data() {
        return {
            'post': this.$store.getters.currently_viewed_post,
        };
    }
}
</script>

<template>
    <div>
        <div>
            <div style="font-weight:bold;font-size:120%;">{{ post.title }}</div>
            <div style="font-style:italic;font-size:80%;">{{ post.author_user_name }}, {{ post.created_at }}</div>
            <div>{{ post.body }}</div>
        </div>
        <div style="padding-left:20px;">
            <div v-for="comment in post.comments">
                <comment-display :comment="comment"></comment-display>
            </div>
            <comment-create></comment-create>
        </div>
    </div>
</template>
