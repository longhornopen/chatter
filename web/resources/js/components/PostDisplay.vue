<script>
export default {
    data() {
        return {
            show_editor: false,
        };
    },
    computed: {
        post() {
            return this.$store.getters.currently_viewed_post;
        },
        user_is_teacher() {
            return this.$store.getters.user.role === 'teacher';
        }
    },
    methods: {
        pin(pinned) {
            // console.log('set pinned for this post to '+pinned);
            this.$store.dispatch('pinPost', {
                post_id: this.$store.getters.currently_viewed_post.id,
                pinned: pinned
            });
        },
        remove() {
            console.log('remove this post');
        },
        lock(locked) {
            // console.log('set locked for this post to '+locked);
            this.$store.dispatch('lockPost', {
                post_id: this.$store.getters.currently_viewed_post.id,
                locked: locked
            });
        },
    },
}
</script>

<template>
    <div class="app-post-display">
        <div>
            <h2>
                {{ post.title }}
            </h2>
            <div>
                {{ post.author_user_name }} {{ post.created_at }}
            </div>
            <div class="post-display-body">
                {{ post.body }}
            </div>
            <!-- <div> -->
                <div class="btn-groups">
                    <div class="left">
                        <button
                            class="btn btn-blue"
                            :class="user_is_teacher && !show_editor?'':'d-none'"
                            @click="pin(!post.pinned)"
                        >{{post.pinned ? "Unpin" : "Pin"}}</button>
                        <button
                            class="btn btn-blue"
                            :class="user_is_teacher && !show_editor?'':'d-none'"
                            @click="remove()"
                        >Remove</button>
                        <button
                            class="btn btn-blue"
                            :class="user_is_teacher && !show_editor?'':'d-none'"
                            @click="lock(!post.locked)"
                        >{{post.locked ? "Unlock" : "Lock"}}</button>
                    </div>
                    
                    <div class="right">
                        <button 
                            class="btn btn-orange"
                            :class="!show_editor?'':'d-none'"
                            @click="show_editor=true">Comment</button>
                    </div>
                </div>
                <comment-create v-if="show_editor"></comment-create>
                    <div v-show="show_editor" class="editor-btn-group">
                        <button class="btn btn-gray" @click="show_editor=false">Cancel</button>
                        <button class="btn btn-orange">Submit</button>
                    </div>
                <!-- <div>
                    <button class="btn btn-orange">Comment</button>
                </div> -->
            <!-- </div> -->
        </div>
        <div class="comments">
            <div v-for="comment in post.comments">
                <comment-display :comment="comment"></comment-display>
            </div>
            <!-- <comment-create></comment-create> -->
            
        </div>
    </div>
</template>
