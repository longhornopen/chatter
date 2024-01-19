<script>
import Modal from './Modal.vue'

export default {
    components: { Modal },
    data () {
        return {
            user_name: '',
            user_email: '',

            course: null,

            edited_course_user_mail_digest_frequency_minutes: null,
            course_user_mail_digest_frequency_save_state: '',

            course_close_datetime: null,
            course_close_date_save_state: '',

            course_post_tags: [],
            post_tag_bgcolor_choices: [
                {text:'Black', value:'#000000'},
                {text:'Dark Indigo', value:'#093145'},
                {text:'Indigo', value:'#3C6478'},
                {text:'Alice', value:'#107896'},
                {text:'Kelly', value:'#829356'},
                {text:'Coral', value:'#C2571A'},
                {text:'Ruby', value:'#9A2617'}
            ],
            post_tag_being_edited: {},
            post_tag_being_edited_posn: null,
            course_post_tags_save_state: '',
        }
    },
    computed: {
        user_is_instructor() {
            return this.$store.getters.user_is_teacher;
        },
        has_feature_mail_activity_digest() {
            return true; // FIXME refactor out
        },
        course_user_mail_digest_frequency_minutes() {
            return this.$store.getters.user.mail_digest_frequency_minutes;
        },
    },
    methods: {
        async save_course_user_mail_digest_frequency_minutes() {
            this.course_user_mail_digest_frequency_save_state = 'pending'
            await this.$store.dispatch('updateCourseUser', {mail_digest_frequency_minutes: this.edited_course_user_mail_digest_frequency_minutes})
            this.$bvToast.toast('Email frequency saved.', {title:'Save successful', autoHideDelay: 5000})
            this.course_user_mail_digest_frequency_save_state = ''
        },
        async save_course_close_date() {
            // assume times are always end-of-day for now; maybe add a timepicker control here later
            if (this.course_close_datetime) {
                this.course_close_datetime.setHours(23, 59, 0)
            }

            this.course_close_date_save_state = 'pending'
            await this.$store.dispatch('updateCourse', {close_date: this.course_close_datetime})
            this.$bvToast.toast('Course close date saved.', {title:'Save successful', autoHideDelay: 5000})
            this.course_close_date_save_state = ''
        },
        async save_course_post_tags() {
            this.course_post_tags_save_state = 'pending'
            await this.$store.dispatch('updateCourse', {post_tags: this.course_post_tags})
            this.$bvToast.toast('Post tags saved.', {title:'Save successful', autoHideDelay: 5000})
            this.course_post_tags_save_state = ''
        },
        add_post_tag() {
            this.post_tag_being_edited_posn = this.course_post_tags.length
            this.post_tag_being_edited = {
                'name':'label',
                'bgcolor':this.post_tag_bgcolor_choices[0].value,
                'fgcolor':'#FFFFFF',
                'teacher_only':false
            }
            this.$bvModal.show('edit_post_tag_modal')
        },
        edit_post_tag(post_tag_ix) {
            this.post_tag_being_edited_posn = post_tag_ix
            this.post_tag_being_edited = {...this.course_post_tags[post_tag_ix]}
            this.$bvModal.show('edit_post_tag_modal')
        },
        delete_post_tag(post_tag_ix) {
            this.$bvModal.msgBoxConfirm('Are you sure you want to delete this post tag?').then(ok => {
                if (ok) {
                    this.course_post_tags.splice(post_tag_ix, 1)
                    this.course_post_tags_save_state = 'enabled'
                }
            });
        },
        alter_post_tag_after_edit() {
            if (!this.post_tag_being_edited.name) { return; }
            this.course_post_tags.splice(this.post_tag_being_edited_posn, 1, this.post_tag_being_edited)
            this.course_post_tags_save_state = 'enabled'
        },
    },
    async mounted() {
        this.user_name = this.$store.getters.user.name
        this.user_email = this.$store.getters.user.email
        this.course = await this.$store.dispatch('getCourse')
        this.course_close_datetime = this.course.close_date ? new Date(this.course.close_date) : null
        this.course_post_tags = this.course.post_tags ?? []
        this.edited_course_user_mail_digest_frequency_minutes = this.course_user_mail_digest_frequency_minutes;
    }
}
</script>

<template>
    <div>
        <modal
            id="edit_post_tag_modal"
            @ok="alter_post_tag_after_edit"
        >
            <b-form>
                <b-form-group
                    label="Label:"
                    label-for="edit_post_tag"
                >
                    <b-form-input
                        id="edit_post_tag"
                        v-model="post_tag_being_edited.name"
                        required
                    ></b-form-input>
                </b-form-group>
                <b-form-group
                    label="Color:"
                    label-for="edit_post_color"
                >
                    <b-form-select
                        id="edit_post_color"
                        v-model="post_tag_being_edited.bgcolor"
                        :options="post_tag_bgcolor_choices"
                        required
                    ></b-form-select>
                </b-form-group>
                <b-form-group
                    label="Teacher Only:"
                    label-for="edit_post_teacher_only"
                >
                    <b-form-checkbox
                        id="edit_post_teacher_only"
                        v-model="post_tag_being_edited.teacher_only"
                    ></b-form-checkbox>
                </b-form-group>

                <b-form-group
                    label="Tag Preview"
                >
                    <div>
                        <b-badge :style="'background-color:'+post_tag_being_edited.bgcolor">{{post_tag_being_edited.name}}</b-badge>
                    </div>
                </b-form-group>
            </b-form>
        </modal>
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

        <div v-if="has_feature_mail_activity_digest">
            <b-card>
                <b-card-text>
                <h3>Email Updates</h3>
                <b-form>
                    <b-form-group label="Receive an update of new activity by email:" v-slot="{ ariaDescribedby }">
                        <b-form-radio-group
                            v-model="edited_course_user_mail_digest_frequency_minutes"
                            :aria-describedby="ariaDescribedby"
                            name="mail-digest-frequency"
                            @change="course_user_mail_digest_frequency_save_state='enabled'"
                        >
                            <b-form-radio value="-1">Never</b-form-radio>
                            <b-form-radio value="1440">Rarely <i>(every day)</i></b-form-radio>
                            <b-form-radio value="120">Often <i>(every two hours)</i></b-form-radio>
                            <b-form-radio value="15">As soon as possible <i>(every fifteen minutes)</i></b-form-radio>
                        </b-form-radio-group>
                    </b-form-group>
                </b-form>
                </b-card-text>
                <b-button
                    variant="primary"
                    :disabled="course_user_mail_digest_frequency_save_state!=='enabled'"
                    @click="save_course_user_mail_digest_frequency_minutes()"
                ><font-awesome-icon v-if="course_user_mail_digest_frequency_save_state==='pending'" icon="spinner" spin /> Save changes</b-button>
            </b-card>
        </div>

        <div v-if="user_is_instructor" style="margin-top:12px;">
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
            </b-card>

            <br>
            <b-card>
                <b-card-text>
                    <h3>Post Tags <b-button variant="secondary"
                                             size="sm"
                                             @click="add_post_tag()"
                    ><font-awesome-icon icon="plus"/> Add new</b-button></h3>
                    <table class="table">
                        <tbody>
                        <tr v-for="(post_tag,post_tag_ix) in course_post_tags">
                            <td>
                                <b-button variant="secondary"
                                          size="sm"
                                          @click="edit_post_tag(post_tag_ix)"
                                ><font-awesome-icon icon="edit"/> Edit
                                </b-button>
                                <b-button variant="outline-danger"
                                          size="sm"
                                          @click="delete_post_tag(post_tag_ix)"
                                ><font-awesome-icon icon="times"/> Delete
                                </b-button>

                            </td>
                            <td>
                                <b-badge :style="'background-color:'+post_tag.bgcolor">{{post_tag.name}}</b-badge>
                                <span v-if="post_tag.teacher_only">(teacher only)</span>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </b-card-text>
                <b-button
                    variant="primary"
                    :disabled="course_post_tags_save_state!=='enabled'"
                    @click="save_course_post_tags()"
                ><font-awesome-icon v-if="course_post_tags_save_state==='pending'" icon="spinner" spin /> Save changes</b-button>
            </b-card>

            <div v-if="false">
            <h2>Course Overview</h2>
            Stuff here - number of posts, comments?  Downloads of per-student statistics?
            </div>
        </div>
    </div>
    </div>
</template>

<style lang="scss" scoped>
@import '../../sass/_variables.scss';

</style>
