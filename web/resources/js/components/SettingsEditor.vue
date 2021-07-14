<script>
export default {
    data () {
        return {
            user_name: '',
            user_email: '',
            post_types: [ {'name':'announcement'}, {'name':'question'} ],
        }
    },
    mounted() {
        this.user_name = this.$store.getters.user.name
        this.user_email = this.$store.getters.user.email
    }
}
</script>

<template>
    <div style="padding:20px;">
        <h2>My Info</h2>
        <form>
            <div class="form-group">
                <label for="name">My name:</label>
                <input type="text" class="form-control" id="name"
                       :value="user_name"
                       disabled
                >
            </div>
            <div class="form-group">
                <label for="email">My email address:</label>
                <input type="text" class="form-control" id="email"
                       :value="user_email"
                       disabled
                >
            </div>
        </form>

        <div v-if="false">
        <h2>Email Frequency</h2>
        <form>
            <b-form-group label="Receive an update of unread posts by email:" v-slot="{ ariaDescribedby }">
                <b-form-radio v-model="selected" :aria-describedby="ariaDescribedby" name="some-radios" value="A">Never</b-form-radio>
                <b-form-radio v-model="selected" :aria-describedby="ariaDescribedby" name="some-radios" value="B">Every two hours</b-form-radio>
                <b-form-radio v-model="selected" :aria-describedby="ariaDescribedby" name="some-radios" value="B">Every day</b-form-radio>
            </b-form-group>
        </form>
        </div>

        <div v-if="false">
        <h2>Instructor Settings</h2>
        <label for="closeat">Close course at:</label>
        (Date picker here, or option to remove an existing close date)<!--
        <b-form-datepicker id="closeat" v-model="value"></b-form-datepicker>
        <p>Value: '{{ value }}'</p>
        --><br><br>
        </div>

        <div v-if="false">
        <label for="posttypes">Post types:</label>
        (Some editor here to:
        <ul>
            <li>Allow people to set post type labels ('announcement', 'question', ...)</li>
            <li>Control which color the label appears as?</li>
            <li>Decide who can set a particular post type (e.g., only teachers can post announcements)</li>
        </ul>
        <!-- FIXME write this --> <br><br>
        </div>

        <div v-if="false">
        <h2>Course Overview</h2>
        Stuff here - number of posts, comments?  Downloads of per-student statistics?
        </div>
    </div>
</template>
