<script>
import { useMainStore } from '@/store'

export default {
    setup() {
        const store = useMainStore()
        return { store }
    },
    name: 'HelpViewer',
    computed: {
        help_url() {
            return this.store.course_summary.help_url;
        },
        help_url_display() {
            return this.store.course_summary.help_url_text;
        },
    },
}
</script>

<template>
    <div>
        <p>
            Chatter is a discussion-board tool where students and instructors can
            communicate outside the classroom.
        </p>

        <h4>Students can:</h4>
        <ul>
            <li>Produce free-form threaded discussions, moderated by course instructors</li>
            <li>Ask questions and crowdsource info by writing Posts</li>
            <li>See what types of questions your peers have over course material</li>
            <li>Suggest answers by Commenting on Posts</li>
        </ul>

        <h4>Instructors can:</h4>
        <ul>
            <li>Post important announcements and class info</li>
            <li>Comment on student posts and answer questions</li>
            <li>Endorse posts with the best response</li>
            <li>Pin important posts so students can access them quickly</li>
        </ul>

        <p>
            Check out what’s new in your Post Dashboard to the left, where you’ll see All, Unread, and My Posts.
            Click a post to begin.
            <br>
            You can also search all Posts for content using the search function,
            or write a new Post using the 'Write a Post' button at the bottom.
        </p>

        <div v-if="help_url">
            <h4>Have questions or need help?</h4>
            <a :href="help_url" target="_blank">{{help_url_display}}</a>.
        </div>

        <hr>
        <div style="font-size:80%;">
            <a href="https://longhornopen.github.io/" target="_blank"><img src="/images/longhorn-open-logo.svg" alt="Longhorn Open logo" width="30" height="30"></a>
            Chatter is an open-source project from <a href="https://longhornopen.github.io/" target="_blank">Longhorn Open Ed Tech</a>.  Visit our site to learn more or to help!
        </div>
    </div>
</template>

<style lang="scss" scoped>
@import '../../sass/_variables.scss';

</style>
