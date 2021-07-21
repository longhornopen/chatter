<script>
export default {
    data () {
        return {
            user_name: '',
            user_email: '',

            course_close_datetime: null,
            course_close_date_save_state: '',

            post_types: [ {'name':'announcement'}, {'name':'question'} ],
        }
    },
    computed: {
        user_is_instructor() {
            return this.$store.getters.user_is_teacher;
        },
    },
    methods: {
        async save_course_close_date() {
            // assume times are always end-of-day for now; maybe add a timepicker control here later
            if (this.course_close_datetime) {
                this.course_close_datetime.setHours(23, 59, 0);
            }

            this.course_close_date_save_state = 'pending';
            await this.$store.dispatch('updateCourse', {close_date: this.course_close_datetime});
            this.course_close_date_save_state = 'complete';
            setTimeout(() => this.course_close_date_save_state = '', 5000);
        }
    },
    async mounted() {
        this.user_name = this.$store.getters.user.name
        this.user_email = this.$store.getters.user.email
        let course = await this.$store.dispatch('getCourse')
        this.course_close_datetime = course.close_date ? new Date(course.close_date) : null
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

        <div v-if="user_is_instructor">
            <h2>Instructor Settings</h2>
            <b-card>
                <b-card-text>
                    <h3>Course close date</h3>
                    <p>Closing a course will remove students' ability to create or edit posts and comments.  Students will not be able to participate in this course after the date listed here.</p>
                    <label for="closeat">Close course at end of day:</label>
                    <b-input-group size="sm">
                        <b-form-datepicker id="closeat"
                                           v-model="course_close_datetime"
                                           value-as-date
                                           reset-button
                                           label-reset-button="Clear"
                                           @input="course_close_date_save_state='enabled'"
                        ></b-form-datepicker>
                    </b-input-group>
                </b-card-text>
                <b-button
                    variant="primary"
                    :disabled="course_close_date_save_state!=='enabled'"
                    @click="save_course_close_date()"
                ><font-awesome-icon v-if="course_close_date_save_state==='pending'" icon="spinner" spin /> Save changes</b-button>
                <b-badge variant="success" v-if="course_close_date_save_state==='complete'">Saved!</b-badge>
            </b-card>

            <div v-if="false">
            <label for="posttypes">Post types:</label>
            (Some editor here to:
            <ul>
                <li>Allow people to set post type labels ('announcement', 'question', ...)</li>
                <li>Control which color the label appears as?</li>
                <li>Decide who can set a particular post type (e.g., only teachers can post announcements)</li>
            </ul>
            </div>

            <div v-if="false">
            <h2>Course Overview</h2>
            Stuff here - number of posts, comments?  Downloads of per-student statistics?
            </div>
        </div>
    </div>
</template>
