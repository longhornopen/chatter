export default {
  course_closed_mixin: {
    computed: {
      course_is_closed() {
        return this.$store.getters.course_is_closed;
      },
    }
  }
}
