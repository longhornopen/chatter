<script>
import FormattedDate from './FormattedDate.vue'
import PostTagBadge from './PostTagBadge.vue'
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
            let posts = this.$store.getters.search_results_available
                ? this.$store.getters.filtered_posts
                : this.$store.getters.posts;
            return posts.sort((a,b) => {
                if (a.pinned && !b.pinned) { return -1; }
                if (b.pinned && !a.pinned) { return 1; }
                return 0;
            });
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
        post_class(post) {
            let css_class = "";
            if (post.pinned) {
                css_class += " pinned";
            }
            if (this.currently_viewed_post_id === post.id) {
                css_class += " selected";
            }
            return css_class;
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
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
            <div
                class="post"
                v-if="posts_loaded"
                v-for="post in posts"
                :class="post_class(post)">
                <div @click="open_post(post.id)" class="post-clickable-container">
                    <div>
                        <div class="post-misc-info d-flex justify-content-between">
                            <span>{{ poster_name(post) }}</span>
                            <formatted-date :dateIso="post.created_at" italicized="true"></formatted-date>
                        </div>
                        <h5 class="post-title">
                            <post-tag-badge :post_tag_name="post.tag"/> {{ post.title }}
                        </h5>
                    </div>
                    <div class="post-btn-group">
                        <div>
                            <font-awesome-icon
                                class="pin-icon"
                                :class="post.pinned ? '' : 'd-none'"
                                icon="thumbtack"
                            />
                            <font-awesome-icon
                                class="lock-icon"
                                :class="post.locked ? '' : 'd-none'"
                                icon="lock"
                            />
                        </div>
                        <div v-if="post.num_unread_comments === 0">
                            <div class="btn-group" role="group" :aria-label="post.num_comments + 'comments'">
                                <button type="button" class="btn badge-read" :title="post.num_comments + ' total comments'">{{ post.num_comments }}</button>
                            </div>
                        </div>
                        <div v-if="post.num_unread_comments > 0">
                            <div class="btn-group" role="group" :aria-label="post.num_unread_comments + ' unread comments'">
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

<style lang="scss" scoped>
@import '../../sass/_variables.scss';

.app-post-list {
    background-color: $lightgray;
    padding: $post-list-padding;
    padding-bottom: $write-post-btn-height;
    height: 100%;
    position: relative;
    .nav-tabs {
        display: flex;
        .nav-item {
            flex: 1;
            border-top-left-radius: 6px;
            border-top-right-radius: 6px;
            background-color: $bg-dark;
            box-shadow: 0 4px 4px 0 rgba(0, 0, 0, 0.25);
            transition: 0.3s;
            a, a:hover, a:visited {
                color: $white;
            }
            .nav-link {
                height: 100%;
            }
            .nav-link.active {
                background: $darkgray;
                border-top-left-radius: 6px;
                border-top-right-radius: 6px;
            }
        }
        .nav-item:hover {
            background-color: darken($bg-dark, $darken-amount);
        }

    }
    .btn-post-topic {
        width: calc(100% - #{$post-list-padding * 2});
        background-color: $primary;
        color: $white;
        border-radius: 0;
        border-top-left-radius: 6px;
        border-top-right-radius: 6px;
        position: absolute;
        bottom: 0;
    }
    .btn-post-topic:hover {
        background-color: darken($primary, $darken-amount);
    }
    .app-post-list-body {
        background-color: $body-bg;
        height: calc(#{$full-height} - #{$title-bar-height} - #{map-get($app-header-height, 1920)} - #{map-get($tabs-height, 1920)} - #{$post-list-padding} - #{$write-post-btn-height});
        overflow-y: auto;
        .post {
            background-color: $body-bg;
            padding: 10px;
            border-bottom: 0.25px dotted $lightgray;
            .post-misc-info {
                color: $tertiary;
            }
            .post-title {
                margin: 0;
            }
            .post-btn-group {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin: 5px 0 0 0;
                .pin-icon, .lock-icon {
                    color: $tertiary;
                    margin: 0 5px 0 0;
                }
                span {
                    margin: 0 0 0 5px;
                    color: $white;
                }
                .btn {
                    color: $white;
                    box-shadow: none;
                    padding: 0.05rem 0.25rem;
                    font-size: small;
                }
                .badge-read {
                    background-color: $grayvariation;
                }
            }
        }
        .post.pinned {
            background-color: rgba($post-pinned-background-color, $post-pinned-background-opacity);
        }
        .post.selected {
            background-color: #EEEEEE;
        }
        .no-search-results {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
        }
        .post-clickable-container {
            cursor: pointer;
        }
    }
    .app-post-list-body {
        height: 100%;
    }
}
</style>
