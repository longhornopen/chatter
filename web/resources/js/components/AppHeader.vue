<script>
export default {
    data() {
        return {
            course_name: this.$store.getters.course_summary.name,
        };
    }
}
</script>

<template>
    <div class="d-flex justify-content-between">
        <div>{{ course_name }}</div>
        <div>Search: [______] (Search)</div>
        <div><i class="fas fa-cog"></i> <font-awesome-icon icon="cog"/> Settings</div>
    </div>
</template>
