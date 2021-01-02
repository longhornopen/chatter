<script>
export default {
    data () {
        return {

        }
    },
    computed: {
        post_order() {
            return this.$store.getters.filter_order;
        },
        search_returned_zero_posts() {
            return this.$store.getters.search_results_available
                && this.$store.getters.filtered_posts.length === 0;
        },
        posts() {
            return this.$store.getters.search_results_available
                ? this.$store.getters.filtered_posts
                : this.$store.getters.posts;
        },
    },
    methods: {
        set_post_sort_order: function (order) {
            this.$store.dispatch('setFilterOrder', {filter_order: order});
            this.$store.dispatch('search');
        },
        open_post: function(post_id) {
            this.$store.dispatch('setAppMainPanelMode', {mode: 'show_post', post_id: post_id});
        },
        open_new_post_editor: function() {
            this.$store.dispatch('setAppMainPanelMode', {mode: 'new_post'});
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
                <div @click="open_post(post.id)" class="post-clickable-container">
                    <div>
                        <div class="post-misc-info">
                            <span>{{ post.author_user_name }}</span>
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
            </div>
            <div v-if="search_returned_zero_posts" class="no-search-results">No Search Results</div>
            <div>
                <button
                    class="btn btn-post-topic"
                    @click="open_new_post_editor()"
                >
                    <font-awesome-icon icon="plus"/>
                    Write a Post
                </button>
            </div>
        </div>


    </div>
</template>
