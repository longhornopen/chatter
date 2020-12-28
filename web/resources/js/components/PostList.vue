<script>
export default {
    data () {
        return {
            'posts': this.$store.getters.course_summary.posts,
            'post_order': 'newest'
        }
    },
    methods: {
        ordered_posts: function () {
            if (this.post_order === 'newest') {
                // FIXME sort by pinned, then newest
                return this.posts
            }
            if (this.post_order === 'pinned') {
                // FIXME sort by pinned, then newest
                return this.posts
            }
            if (this.post_order === 'unread') {
                // FIXME filter by pinned, then newest
                return this.posts
            }
            if (this.post_order === 'my_posts') {
                // FIXME filter by mine, then newest
                return this.posts
            }
        },
        set_post_sort_order: function (order) {
            this.post_order = order
        },
        open_post_editor: function() {
            console.log("Now I should open a new-post editor.")
        }
    }
}
</script>

<template>
    <div class="app-post-list">
        <div class="app-post-list-body">
            <div>
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link"
                           :class="post_order==='newest'?'active':''"
                           href="#"
                           @click.prevent="set_post_sort_order('newest')"
                        >
                            All
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"
                           :class="post_order==='pinned'?'active':''"
                           href="#"
                           @click.prevent="set_post_sort_order('pinned')"
                        >
                            Pinned
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"
                           :class="post_order==='unread'?'active':''"
                           href="#"
                           @click.prevent="set_post_sort_order('unread')"
                        >
                            Unread
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"
                           :class="post_order==='my_posts'?'active':''"
                           href="#"
                           @click.prevent="set_post_sort_order('my_posts')"
                        >
                            My Posts
                        </a>
                    </li>
                </ul>
            </div>

            <div v-for="post in posts">
                <div style="margin-bottom:10px;">
                    <div style="font-size:80%;">
                        <span>{{ post.creator_user_name }}</span>
                        <i>{{ post.created_at }}</i>
                        <span class="badge badge-danger"
                              :title="post.num_unread_comments + ' unread comments'"
                        >{{ post.num_unread_comments }}</span>
                        <span class="badge badge-secondary"
                              :title="post.num_comments + ' total comments'"
                        >{{ post.num_comments }}</span>
                    </div>
                    <div>
                        {{ post.title }}
                    </div>
                    <div>
                        {{ post.body }}
                    </div>
                </div>
            </div>
        </div>

        <div>
            <button
                class="btn btn-post-topic"
                @click="open_post_editor()"
            >
                <font-awesome-icon icon="plus"/>
                Post a Topic
            </button>
        </div>
    </div>
</template>
