<script>
import FormattedDate from './FormattedDate'
import PostTagBadge from './PostTagBadge'
import component_mixins from '../component_mixins'

export default {
    components: { FormattedDate, PostTagBadge },
    mixins: [component_mixins.course_closed_mixin],
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
        posts_loaded() {
            return !this.$store.getters.posts_loading;
        },
        currently_viewed_post_id() {
            return this.$store.state.currently_viewed_post?.id;
        },
    },
    methods: {
        set_post_sort_order(order) {
            this.$store.dispatch('setFilterOrder', {filter_order: order});
            this.$store.dispatch('search');
        },
        open_post(post_id) {
            if (this.$store.getters.mobile) {
                this.$store.dispatch('switchScreen', {
                    view_post_list: false,
                    view_post_display: true,
                    view_post_create: false,
                })
            }
            this.$router.push('/post/'+post_id).catch(error => {
                if (error.name !== "NavigationDuplicated") {
                    throw error;
                }
            });
        },
        open_new_post_editor() {
            if (this.$store.getters.mobile) {
                this.$store.dispatch('switchScreen', {
                    view_post_list: false,
                    view_post_display: false,
                    view_post_create: true,
                })
            }
            this.$router.push('/post/new')
        },
        poster_name(post) {
            return post.author_anonymous ? '(anonymous)' : post.author_user_name;
        },
        post_style(post) {
            // FIXME make this a class instead of a style
            if (this.currently_viewed_post_id === post.id) {
                return "background-color: #EEEEEE"
            }
            return "";
        },
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
            <div v-if="!posts_loaded" class="d-flex justify-content-center mt-5">
                <div class="spinner-border" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
            <div
                class="post"
                v-if="posts_loaded"
                v-for="post in posts"
                :style="post_style(post)">
                <div @click="open_post(post.id)" class="post-clickable-container">
                    <div>
                        <div class="post-misc-info">
                            <span>{{ poster_name(post) }}</span>
                            <formatted-date :dateIso="post.created_at" italicized="true"></formatted-date>
                        </div>
                        <h5 class="post-title">
                            <post-tag-badge :post_tag_name="post.tag"/> {{ post.title }}
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
                            <font-awesome-icon
                            class="lock-icon"
                            :class="post.locked ? '' : 'd-none'"
                            icon="lock" />
                        </div>
                        <div v-if="post.num_unread_comments === 0">
                            <div class="btn-group" role="group"     aria-label="Basic example">
                                <button type="button" class="btn badge-read" :title="post.num_comments + ' total comments'">{{ post.num_comments }}</button>
                            </div>
                        </div>
                        <div v-if="post.num_unread_comments > 0">
                            <div class="btn-group" role="group"     aria-label="Basic example">
                                <button type="button" class="btn btn-primary badge-unread" :title="post.num_unread_comments + ' unread comments'">{{ post.num_unread_comments }}</button>
                                <button type="button" class="btn badge-read" :title="post.num_comments + ' total comments'">{{ post.num_comments }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div v-if="search_returned_zero_posts" class="no-search-results">No Search Results</div>
        </div>
        <div v-if="!course_is_closed">
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
