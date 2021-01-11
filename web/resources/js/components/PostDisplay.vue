<script>
import UserName from './UserName';
import FormattedDate from './FormattedDate';
import _ from 'lodash';
export default {
    components: { UserName, FormattedDate },
    data() {
        return {
            show_editor: false,
        };
    },
    computed: {
        post() {
            return this.$store.getters.currently_viewed_post;
        },
        post_loaded() {
            return !_.isEmpty(this.$store.getters.currently_viewed_post);
        },
        user_is_teacher() {
            return this.$store.getters.user.role === 'teacher';
        },
        add_comment_allowed() {
            return !this.$store.state.currently_viewed_post.locked;
        },
        in_mobile_mode() {
            return this.$store.getters.mobile;
        }
    },
    methods: {
        pin(pinned) {
            this.$store.dispatch('pinPost', {
                post_id: this.$store.getters.currently_viewed_post.id,
                pinned: pinned
            });
        },
        remove() {
            this.$swal.fire({
                title: "Are you sure you want to remove this post and all its comments?",
                icon: 'warning',
                showCancelButton: true,
            }).then(result => {
                if (result.isConfirmed) {
                    this.$store.dispatch('deletePost', {
                        post_id: this.$store.getters.currently_viewed_post.id,
                    });
                }
            });
        },
        lock(locked) {
            this.$store.dispatch('lockPost', {
                post_id: this.$store.getters.currently_viewed_post.id,
                locked: locked
            });
        },
        post_comments_with_parent_comment_id(pcid) {
            if (!this.$store.state.currently_viewed_post.comments) {
                return [];
            }
            return this.$store.state.currently_viewed_post.comments.filter(c => c.parent_comment_id === pcid);
        },
        edit_post() {
            this.$swal.fire({
                title: "Editing posts is not yet implemented. Coming soon!",
                icon: 'warning',
            })
        },
        switch_screen() {
            if (this.$store.getters.mobile) {
                this.$store.dispatch('switchScreen', {
                    view_post_list: true,
                    view_post_display: false,
                })
            }
        },
    },
}
</script>

<template>
    <div>
        <div v-if="!post_loaded" class="d-flex justify-content-center mt-5">
            <div class="spinner-border" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <div v-if="post_loaded" class="app-post-display">
            <div class="back-group" v-if="in_mobile_mode" @click="switch_screen()">
                <font-awesome-icon class="back-icon" icon="chevron-left" size="2x"/>
                <h5>Back</h5>
            </div>
            <div>
                <h2>
                    {{ post.title }}
                </h2>
                <div class="post-top-row">
                    <div>
                        <user-name
                            :name="post.author_user_name"
                            :anonymous="post.author_anonymous"
                            :user-id="post.author_user_id"
                        ></user-name>
                        <formatted-date
                            :date-iso="post.created_at"
                        ></formatted-date>
                    </div>
                    <div>
                        <div class="ellipsis" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <font-awesome-icon icon="ellipsis-h" />
                        </div>
                        <div class="dropdown-menu dropdown-menu-right">
                            <button @click="edit_post()" class="dropdown-item" type="button">Edit</button>
                            <button @click="remove()" class="dropdown-item" type="button">Delete</button>
                        </div>
                    </div>
                </div>
                <div class="post-display-body" v-html="post.body"></div>
                    <div class="btn-groups">
                        <div class="left">
                            <button
                                class="btn btn-blue"
                                :class="user_is_teacher && !show_editor?'':'d-none'"
                                @click="pin(!post.pinned)"
                            >{{post.pinned ? "Unpin" : "Pin"}}</button>
                            <!-- <button
                                class="btn btn-blue"
                                :class="user_is_teacher && !show_editor?'':'d-none'"
                                @click="lock(!post.locked)"
                            >{{post.locked ? "Unlock" : "Lock"}}</button>
                        </div>

                        <div class="right" v-if="add_comment_allowed">
                            <button
                                class="btn btn-orange"
                                :class="!show_editor?'':'d-none'"
                                @click="show_editor=true">Comment</button>
                        </div>
                    </div>
                    <comment-create
                        v-if="show_editor"
                        @close_comment_editor="show_editor = false"
                        :parent_comment_id="null"
                        :post_id="this.post.id"
                    ></comment-create>
            </div>
            <div class="comments">
                <div v-for="comment in post_comments_with_parent_comment_id(null)">
                    <comment-display
                        :comment="comment"
                    ></comment-display>
                </div>
            </div>
        </div>
    </div>
</template>
