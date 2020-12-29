<script>
export default {
    data() {
        return {

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
            console.log('set pinned for this post to '+pinned);
            this.$store.getters.currently_viewed_post.pinned = pinned;
        },
        remove() {
            console.log('remove this post');
        },
        lock(locked) {
            console.log('set locked for this post to '+locked);
            this.$store.getters.currently_viewed_post.locked = locked;
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
                            :class="user_is_teacher?'':'d-none'"
                            @click="pin(!post.pinned)"
                        >{{post.pinned ? "Unpin" : "Pin"}}</button>
                        <button
                            class="btn btn-blue"
                            :class="user_is_teacher?'':'d-none'"
                            @click="remove()"
                        >Remove</button>
                        <button
                            class="btn btn-blue"
                            :class="user_is_teacher?'':'d-none'"
                            @click="lock(!post.locked)"
                        >{{post.locked ? "Unlock" : "Lock"}}</button>
                    </div>
                    
                    <div class="right">
                        <button class="btn btn-orange">Comment</button>
                    </div>
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
            <comment-create></comment-create>
        </div>
    </div>
</template>
