<script setup>
// make course.welcome_page from store a ref
import { ref } from 'vue';
import { useMainStore } from '../store'
import WysiwygViewer from "./WysiwygViewer.vue";
const store = useMainStore()

const welcomePage = ref(store.course_summary.welcome_page);
</script>

<template>
    <div style="padding:20px;">
        <wysiwyg-viewer v-model="welcomePage"></wysiwyg-viewer>
    </div>
</template>

<style lang="scss" scoped>
@import '../../sass/_variables.scss';

</style>
