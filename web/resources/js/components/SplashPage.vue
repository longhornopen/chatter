<script>
export default {
    data() {
        return {

        };
    },
    computed: {
        help_url() {
            return this.$store.state.course_summary.help_url;
        },
        help_url_display() {
            return this.$store.state.course_summary.help_url_text;
        },
    }
}
</script>

<template>
    <div style="padding:20px;">

        <h2>Welcome to Chatter!</h2>

        <p>
            Chatter is a discussion-board tool where students and instructors can
            communicate outside the classroom.
        </p>

        <p>
        Students can:
        </p>
        <ul>
            <li>Produce free-form threaded discussions, moderated by course instructors</li>
            <li>Ask questions and crowdsource info by writing Posts</li>
            <li>See what types of questions your peers have over course material</li>
            <li>Suggest answers by Commenting on Posts</li>
        </ul>

        <p>
        Instructors can:
        </p>
        <ul>
            <li>Post important announcements and class info</li>
            <li>Comment on student posts and answer questions</li>
            <li>Endorse posts with the best response</li>
            <li>Pin important posts so students can access them quickly</li>
        </ul>

        <p>
            Check out what’s new in your Post Dashboard to the left, where you’ll see All, Pinned, Unread, and My Posts.
            Click a post to begin.
            <br>
            You can also search all Posts for content using the search function,
            or write a new Post using the 'Write a Post' button at the bottom.
        </p>

        <p v-if="help_url">
            Have questions or need help?
            <a :href="help_url" target="_blank">{{help_url_display}}</a>.
        </p>
    </div>
</template>
