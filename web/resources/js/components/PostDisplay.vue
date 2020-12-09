<script>
export default {
    data() {
        return {
            //
        };
    }
}
</script>

<template>
    <div>
        <div style="border-left:solid 3px red;">
        <div style="font-weight:bold;font-size:120%;">What is Chatter?</div>
        <div style="font-style:italic;font-size:80%;">Chris, 12/08/2020 11:14AM</div>
        <div>So, what is this "Chatter" thing?  Does <b>anybody</b> know?</div>
        </div>
        <div style="padding-left:20px;">
            <div style="border-left:solid 2px green;margin-bottom:10px;"><comment-display></comment-display></div>
            <div style="border-left:solid 2px green;margin-bottom:10px;"><comment-display></comment-display></div>
            <div style="border-left:solid 2px green;margin-bottom:10px;"><comment-display></comment-display></div>
            <comment-create></comment-create>
        </div>
    </div>
</template>
