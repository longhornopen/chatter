<script>
import FormattedDate from './FormattedDate.vue';
export default {
    data () {
        return {
            posts_styles: {}
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
        posts_loaded() {
            return !this.$store.getters.posts_loading;
        }
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
        },
        convert_date: function(date_string) {
            const date = new Date(date_string)
            // console.log(date.getDate())
            // console.log(date.getMonth())
            // console.log(date.getFullYear())
        },
        poster_name(post) {
            return post.author_anonymous ? '(anonymous)' : post.author_user_name;
        },
        get_height_for_posts() {
            console.log(this.$ref)
            // var height_string = 'calc(100vh - 60px - 58px - 10px) - ' + this.$ref.posts_tabs.clientHeight + 'px'
            // Vue.set(this.posts_styles, 'height', height_string)
        }
        
    },
}
</script>

<template>
    <div class="app-post-list">
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
        <div class="app-post-list-body">
            <!-- <div @click="get_height_for_posts()">Hi</div> -->
            <!-- <div>
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
            </div> -->

            <div v-if="!posts_loaded" class="d-flex justify-content-center mt-5">
                <div class="spinner-border" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
            <div class="post" v-if="posts_loaded" v-for="post in posts">
                <div @click="open_post(post.id)" class="post-clickable-container">
                    <div>
                        <div class="post-misc-info">
                            <!-- <span>{{ post.author_user_name }}</span> -->
                            <!-- <i @click="convert_date(post.created_at)">{{ post.created_at }}</i>
                            -->
                            <span>{{ poster_name(post) }}</span>
                            <formatted-date :dateIso="post.created_at" italicized="true"></formatted-date>
                        </div>
                        <h5 class="post-title">
                            {{ post.title }}
                        </h5>
                        <!--
                        // Removing this until we find a way of effectively producing a one-line summary of
                        // a post body (which may contain images, formulas, links, etc.  So it's not as simple
                        // as showing the first N characters of the post body.
                        <div>
                            {{ post.body }}
                        </div>
                        -->
                    </div>
                    <div class="post-btn-group">
                        <div>
                            <font-awesome-icon 
                            class="pin-icon"
                            :class="post.pinned ? '' : 'd-none'"
                            icon="thumbtack" />
                            <!-- <div>{{post.locked}}</div> -->
                            <font-awesome-icon 
                            class="lock-icon"
                            :class="post.locked ? '' : 'd-none'"
                            icon="lock" />
                        </div>
                        <div>
                            <!-- <span class="badge badge-unread"
                                :title="post.num_unread_comments + ' unread comments'"
                            >{{ post.num_unread_comments }}</span>
                            <span class="badge badge-read"
                                    :title="post.num_comments + ' total comments'"
                            >{{ post.num_comments }}</span> -->
                            <div class="btn-group" role="group"     aria-label="Basic example">
                                <button type="button" class="btn badge-unread" :title="post.num_unread_comments + ' unread comments'">{{ post.num_unread_comments }}</button>
                                <button type="button" class="btn badge-read" :title="post.num_comments + ' total comments'">{{ post.num_comments }}</button>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
            <div v-if="search_returned_zero_posts" class="no-search-results">No Search Results</div>
            <!-- <div>
                <button
                    class="btn btn-post-topic"
                    @click="open_new_post_editor()"
                >
                    <font-awesome-icon icon="plus"/>
                    Write a Post
                </button>
            </div> -->
        </div>
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
</template>
