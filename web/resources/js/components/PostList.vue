<script>
export default {
    data() {
        return {
            'posts': this.$store.getters.course_summary.posts,
            'post_order': 'default'
        };
    },
    methods: {
        ordered_posts: function() {
            // FIXME use post_order to sort posts
            if (this.post_order === 'default') {
                return this.posts;
            }/* else if (this.post_order === 'pinned') {
                return ...
            }*/
        },
        set_post_sort_order: function(order) {
            this.post_order = order;
        }
    }
}
</script>

<template>
    <div>
        <div class="d-flex justify-content-between">
            <div>Posts</div>
            <div>
                <a href="#" @click="set_post_sort_order('newest')">Sort by newest</a>
                <a href="#" @click="set_post_sort_order('pinned')">Sort by pinned</a>
                <!-- ... etc ... -->
            </div>
            <div><button class="btn btn-secondary">Add Post</button></div>
        </div>
        <hr>
        <div v-for="post in posts">
            <div style="margin-bottom:10px;">
                <div style="font-size:80%;">
                    <span>{{ post.creator_user_name }}</span>
                    <i>{{ post.created_at }}</i>
                    <span class="badge badge-danger" :title="post.num_unread_comments + ' unread comments'">{{ post.num_unread_comments }}</span>
                    <span class="badge badge-secondary" :title="post.num_comments + ' total comments'">{{ post.num_comments }}</span>
                </div>
                <div><b>{{ post.title }}</b></div>
                <div>{{ post.body }}</div>
            </div>
        </div>
    </div>
</template>
