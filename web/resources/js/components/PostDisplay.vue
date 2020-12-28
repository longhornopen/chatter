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
        },
        remove() {
            console.log('remove this post');
        },
        lock(locked) {
            console.log('set locked for this post to '+locked);
        },
    },
}
</script>

<template>
    <div class="app-post-display">
        <div>
            <div>
                {{ post.title }}
            </div>
            <div>
                {{ post.author_user_name }} {{ post.created_at }}
            </div>
            <div>
                {{ post.body }}
            </div>
            <div>
                <div>
                    <button
                        class="btn"
                        :class="user_is_teacher?'':'d-none'"
                        @click="pin(true)"
                    >Pin</button>
                    <button
                        class="btn"
                        :class="user_is_teacher?'':'d-none'"
                        @click="pin(false)"
                    >Unpin</button>
                    <button
                        class="btn"
                        :class="user_is_teacher?'':'d-none'"
                        @click="remove()"
                    >Remove</button>
                    <button
                        class="btn"
                        :class="user_is_teacher?'':'d-none'"
                        @click="lock(true)"
                    >Lock</button>
                    <button
                        class="btn"
                        :class="user_is_teacher?'':'d-none'"
                        @click="lock(false)"
                    >Unlock</button>
                </div>
                <div>
                    <button class="btn">Comment</button>
                </div>
            </div>
        </div>
        <div style="padding-left:20px;">
            <div v-for="comment in post.comments">
                <comment-display :comment="comment"></comment-display>
            </div>
            <comment-create></comment-create>
        </div>
    </div>
</template>
