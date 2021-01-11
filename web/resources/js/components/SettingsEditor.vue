<script>
export default {
    data () {
        return {
            user_name: '',
            user_email: '',
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
        <h2>Settings</h2>
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
</template>
