<script>
import Modal from './Modal.vue'
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'

export default {
    components: { Modal, VueDatePicker },
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
            post_tag_being_deleted_ix: null,
            course_post_tags_save_state: '',
        }
    },
    computed: {
        user_is_instructor() {
            return this.$store.getters.user_is_teacher;
        },
        course_user_mail_digest_frequency_minutes() {
            return this.$store.getters.user.mail_digest_frequency_minutes;
        },
    },
    methods: {
        async save_course_user_mail_digest_frequency_minutes() {
            this.course_user_mail_digest_frequency_save_state = 'pending'
            await this.$store.dispatch('updateCourseUser', {mail_digest_frequency_minutes: this.edited_course_user_mail_digest_frequency_minutes})
            window.addToast('Email frequency saved.', 'success');
            this.course_user_mail_digest_frequency_save_state = ''
        },
        async save_course_close_date() {
            if (this.course_close_datetime && typeof this.course_close_datetime === 'string') {
                let parts = this.course_close_datetime.split(/\D/);
                this.course_close_datetime = new Date(parts[0], parts[1]-1, parts[2], 23, 59, 0);
            }

            this.course_close_date_save_state = 'pending'
            await this.$store.dispatch('updateCourse', {close_date: this.course_close_datetime})
            window.addToast('Course close date saved.', 'success')
            this.course_close_date_save_state = ''
        },
        async save_course_post_tags() {
            this.course_post_tags_save_state = 'pending'
            await this.$store.dispatch('updateCourse', {post_tags: this.course_post_tags})
            window.addToast('Post tags saved.', 'success')
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
            this.$refs['edit_post_tag_modal'].show()
        },
        edit_post_tag(post_tag_ix) {
            this.post_tag_being_edited_posn = post_tag_ix
            this.post_tag_being_edited = {...this.course_post_tags[post_tag_ix]}
            this.$refs['edit_post_tag_modal'].show()
        },
        confirm_delete_post_tag(post_tag_ix) {
            this.post_tag_being_deleted_ix = post_tag_ix
            this.$refs['delete_post_tag_confirm_modal'].show()
        },
        delete_post_tag() {
            this.course_post_tags.splice(this.post_tag_being_deleted_ix, 1)
            this.course_post_tags_save_state = 'enabled'
            this.$refs['delete_post_tag_confirm_modal'].hide()
        },
        alter_post_tag_after_edit() {
            if (!this.post_tag_being_edited.name) { return; }
            this.course_post_tags.splice(this.post_tag_being_edited_posn, 1, this.post_tag_being_edited)
            this.course_post_tags_save_state = 'enabled'
            this.$refs['edit_post_tag_modal'].hide()
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
    <modal
        ref="delete_post_tag_confirm_modal"

    >
        <template v-slot:body>
            <p>Are you sure you want to delete this post tag?</p>
        </template>
        <template v-slot:footer>
            <button class="btn btn-primary" @click="delete_post_tag()">Ok</button>
        </template>
    </modal>
    <modal
        id="edit_post_tag_modal"
        ref="edit_post_tag_modal"
    >
        <template v-slot:body>
        <form>
            <div class="mb-3">
                <label for="edit_post_tag" class="form-label">Label:</label>
                <input type="text" class="form-control" id="edit_post_tag" v-model="post_tag_being_edited.name" required>
            </div>
            <div class="mb-3">
                <label for="edit_post_color" class="form-label">Color:</label>
                <select class="form-select" id="edit_post_color" v-model="post_tag_being_edited.bgcolor" required>
                    <option v-for="option in post_tag_bgcolor_choices" :key="option.value" :value="option.value">
                        {{ option.text }}
                    </option>
                </select>
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="edit_post_teacher_only" v-model="post_tag_being_edited.teacher_only">
                <label class="form-check-label" for="edit_post_teacher_only">Teacher Only:</label>
            </div>
            <div class="mb-3">
                <label class="form-label">Tag Preview</label>
                <div>
                    <span :style="'background-color:'+post_tag_being_edited.bgcolor" class="badge">{{post_tag_being_edited.name}}</span>
                </div>
            </div>
        </form>
        </template>
        <template v-slot:footer>
            <button class="btn btn-primary" @click="alter_post_tag_after_edit()">Ok</button>
        </template>
    </modal>
    <div style="padding:20px;">
        <div class="card mb-3">
            <div class="card-body">
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
            </div>
        </div>

        <div>
            <div class="card mb-3">
                <div class="card-body">
                <h3>Email Updates</h3>
                    <form>
                        <div>
                            <label for="mail-digest-frequency" class="form-label">Receive an update of new activity by email:</label>
                        </div>
                        <div class="form-check ms-3">
                            <input class="form-check-input" type="radio" name="mail-digest-frequency" id="mail-digest-frequency-1" value="-1" v-model="edited_course_user_mail_digest_frequency_minutes" @change="course_user_mail_digest_frequency_save_state='enabled'">
                            <label class="form-check-label" for="mail-digest-frequency-1">
                                Never
                            </label>
                        </div>
                        <div class="form-check ms-3">
                            <input class="form-check-input" type="radio" name="mail-digest-frequency" id="mail-digest-frequency-2" value="1440" v-model="edited_course_user_mail_digest_frequency_minutes" @change="course_user_mail_digest_frequency_save_state='enabled'">
                            <label class="form-check-label" for="mail-digest-frequency-2">
                                Rarely <i>(every day)</i>
                            </label>
                        </div>
                        <div class="form-check ms-3">
                            <input class="form-check-input" type="radio" name="mail-digest-frequency" id="mail-digest-frequency-3" value="120" v-model="edited_course_user_mail_digest_frequency_minutes" @change="course_user_mail_digest_frequency_save_state='enabled'">
                            <label class="form-check-label" for="mail-digest-frequency-3">
                                As soon as possible <i>(every two hours)</i>
                            </label>
                        </div>
                    </form>
                </div>
                <button class="btn btn-primary"
                        :disabled="course_user_mail_digest_frequency_save_state!=='enabled'"
                        @click="save_course_user_mail_digest_frequency_minutes()"
                ><font-awesome-icon v-if="course_user_mail_digest_frequency_save_state==='pending'" icon="spinner" spin /> Save changes</button>
            </div>
        </div>

        <div v-if="user_is_instructor" style="margin-top:12px;">
            <h2>Instructor Settings</h2>
            <div class="card mb-1">
                <div class="card-body">
                    <h3>Course close date</h3>
                    <p>Closing a course will remove students' ability to create or edit posts and comments.  Students will not be able to participate in this course after the date listed here.</p>
                    <label for="closeat">Close course at:</label>
                    <div class="input-group input-group-sm">
                        <VueDatePicker v-model="course_close_datetime"
                                       :time-picker-inline="true"
                                       :is-24="false"
                                       @update:model-value="course_close_date_save_state='enabled'"
                        ></VueDatePicker>
                    </div>
                </div>
                <button class="btn btn-primary" :disabled="course_close_date_save_state!=='enabled'" @click="save_course_close_date()">
                    <span v-if="course_close_date_save_state==='pending'" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    Save changes
                </button>
            </div>

            <br>
            <div class="card mb-3">
                <div class="card-body">
                    <h3>Post Tags <button class="btn btn-sm btn-secondary"
                                          @click="add_post_tag()"
                    ><font-awesome-icon icon="plus"/> Add new</button></h3>
                    <table class="table">
                        <tbody>
                        <tr v-for="(post_tag,post_tag_ix) in course_post_tags">
                            <td>
                                <button class="btn btn-sm btn-secondary"
                                        @click="edit_post_tag(post_tag_ix)"
                                ><font-awesome-icon icon="edit"/> Edit
                                </button>
                                <button class="btn btn-sm btn-outline-danger"
                                          @click="confirm_delete_post_tag(post_tag_ix)"
                                ><font-awesome-icon icon="times"/> Delete
                                </button>

                            </td>
                            <td>
                                <span class="badge" :style="'background-color:'+post_tag.bgcolor">{{post_tag.name}}</span>
                                <span v-if="post_tag.teacher_only" class="ms-1">(teacher only)</span>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <button
                    class="btn btn-primary"
                    :disabled="course_post_tags_save_state!=='enabled'"
                    @click="save_course_post_tags()"
                ><font-awesome-icon v-if="course_post_tags_save_state==='pending'" icon="spinner" spin /> Save changes</button>
            </div>
        </div>
    </div>
</template>

<style lang="scss" scoped>
@import '../../sass/_variables.scss';

</style>
