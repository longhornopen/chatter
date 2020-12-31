<script>
export default {
    data () {
        return {
            'post_order': 'newest',
            'search_results_empty': false,
        }
    },
    computed: {
        posts() {
            var posts = null

            if (this.$store.getters.course_summary.search_results_available) {
                // has searched
                if (this.$store.getters.course_summary.filtered_posts.length === 0) {
                    this.search_results_empty = true
                }
                posts = this.$store.getters.course_summary.filtered_posts
            } else {
                // hasn't searched
                posts = this.$store.getters.course_summary.posts
                this.search_results_empty = false
            }

            // filtering by tabs
            if (this.post_order === 'newest') {
                var posts_copy = [...posts]
                posts_copy.sort((a, b) => (
                    a.created_at === b.created_at ? 0 : 
                    (a.created_at < b.created_at ? -1 : 1)))
                return posts_copy
            }
            if (this.post_order === 'pinned') {
                var posts_copy = [...posts]
                posts_copy.sort((a, b) => (
                    (a.pinned && b.pinned) || (!a.pinned && !b.pinned) ? 0 : 
                    (a.pinned ? -1 : 1)))
                return posts_copy
            }
            if (this.post_order === 'unread') {
                // FIXME filter by unread, then newest
                return posts
            }
            if (this.post_order === 'my_posts') {
                const user_name = this.$store.getters.user.name
                var posts_copy = [...posts]

                posts_copy.sort((a, b) => (
                    (a.creator_user_name === b.creator_user_name) ? 0 : (
                        (a.creator_user_name === user_name ? -1 : 1)
                    )
                ))
                return posts_copy
            }
        },
    },
    methods: {
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
                    <li class="nav-item active">
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

            <div class="post" v-for="post in posts">
                <div>
                    <div class="post-misc-info">
                        <span>{{ post.creator_user_name }}</span>
                        <i>{{ post.created_at }}</i>
                        <!-- <span class="badge badge-danger"
                              :title="post.num_unread_comments + ' unread comments'"
                        >{{ post.num_unread_comments }}</span>
                        <span class="badge badge-secondary"
                              :title="post.num_comments + ' total comments'"
                        >{{ post.num_comments }}</span> -->
                    </div>
                    <h5 class="post-title">
                        {{ post.title }}
                    </h5>
                    <div>
                        {{ post.body }}
                    </div>
                </div>
                <div class="post-btn-group">
                    <span class="badge badge-unread"
                        :title="post.num_unread_comments + ' unread comments'"
                    >{{ post.num_unread_comments }}</span>
                    <span class="badge badge-read"
                            :title="post.num_comments + ' total comments'"
                    >{{ post.num_comments }}</span>
                </div>
                
            </div>
            <div v-if="search_results_empty" class="no-search-results">No Search Results</div>
            <div>
                <button
                    class="btn btn-post-topic"
                    @click="open_post_editor()"
                >
                    <font-awesome-icon icon="plus"/>
                    Write a Post
                </button>
            </div>
        </div>

        
    </div>
</template>
